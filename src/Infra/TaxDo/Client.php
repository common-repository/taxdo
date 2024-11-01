<?php


namespace TaxDo\WooCommerce\Infra\TaxDo;

use WP_Error;

final class Client
{
	private const UNIVERSAL_TAX_CLASSES_PATH = 'tp/v1/tax-classes';
	private const SUB_TAX_CLASSES_PATH = 'tp/v1/sub-tax-classes';
	private const PRE_PROCESS_PATH = 'tp/v1/invoices/pre-process';
	private const INVOICE_PATH = 'tp/v1/invoices';
	private const PAYMENT_PATH = 'tp/v1/invoices/payments';
	private const ADDRESS_BY_CERTIFICATE_TOKEN_PATH = 'tp/v1/certificate';
	private const CONNECT = 'tp/v1/connect';
	private const VALIDATE_ZIPCODE = 'tp/v1/addresses/validate-zipcode';
	private const COLLECTED_CERTIFICATE_PATH = 'tp/v1/certificate';

	private string $base_url;
	private string $tenant_id;
	private string $token;

	public function __construct(string $base_url, string $tenant_id, string $token)
	{
		$this->base_url = $base_url;
		$this->tenant_id = $tenant_id;
		$this->token = $token;
	}

	public function get_universal_tax_classes(): array
	{
		$url = $this->tenant_url(self::UNIVERSAL_TAX_CLASSES_PATH);
		$req = [
			'headers' => $this->default_headers()
		];
		$response = wp_remote_get($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Couldn't get tax classes.", $response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	private function tenant_url(string $path): string
	{
		return sprintf('%s/%s', $this->base_url, $path);
	}

	private function default_headers(): array
	{
		return [
			'Authorization' => sprintf('Bearer %s', $this->token),
			'X-space-id' => $this->tenant_id,
			'Accept' => 'application/json',
		];
	}

	/**
	 * @param array|WP_Error $response
	 *
	 * @return bool
	 */
	private function is_response_ok($response): bool
	{
		$status_code = wp_remote_retrieve_response_code($response);

		return !is_wp_error($response) && 200 <= $status_code && 300 > $status_code;
	}

	private function json_response_body(array $response): array
	{
		return json_decode(wp_remote_retrieve_body($response), true);
	}

	public function get_sub_tax_classes_of_tax_class(int $tax_class_id, string $state_code): array
	{
		$url = sprintf('%s?state_code=%s&tax_class_ids[]=%d', $this->tenant_url(self::SUB_TAX_CLASSES_PATH), $state_code, $tax_class_id);
		$req = [
			'headers' => $this->default_headers(),
		];
		$response = wp_remote_get($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Couldn't get sub tax classes.", $response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	public function get_sub_tax_classes_of_state(string $state_code): array
	{
		$url = sprintf('%s?state_code=%s', $this->tenant_url(self::SUB_TAX_CLASSES_PATH), $state_code);
		$req = [
			'headers' => $this->default_headers(),
		];
		$response = wp_remote_get($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Couldn't get sub tax classes.", $response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	public function pre_process(?string $certificateCode, array $address, array $client, array $items, string $source):
	array
	{
		$url = $this->tenant_url(self::PRE_PROCESS_PATH);

		$body = [
			'assessment' => [
				'address' => $address,
				'client' => $client,
				'items' => $items,
			],
			'source' => $source,
		];
		if ($certificateCode) {
			$body['exemption_code'] = $certificateCode;
		}

		$req = [
			'headers' => $this->default_headers(),
			'body' => $body,
		];
		$response = wp_remote_post($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::to_calculate_tax($response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	public function create_invoice(?string $certificateCode, array $address, array $client, array $items, string $source): array
	{
		$url = $this->tenant_url(self::INVOICE_PATH);
		$body = [
			'assessment' => [
				'address' => $address,
				'client' => $client,
				'items' => $items,
			],
			'source' => $source,
		];
		if ($certificateCode) {
			$body['exemption_code'] = $certificateCode;
		}

		$req = [
			'headers' => $this->default_headers(),
			'body' => $body,
		];

		$response = wp_remote_post($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Couldn't create the invoice.", $response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	public function add_payment(array $data): void
	{
		$url = $this->tenant_url(self::PAYMENT_PATH);
		$req = [
			'headers' => $this->default_headers(),
			'body' => $data,
		];
		$response = wp_remote_post($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Couldn't mark the invoice as paid.", $response, $url, $req);
		}
	}

	public function connect(): void
	{
		$url = $this->tenant_url(self::CONNECT);
		$req = [
			'headers' => $this->default_headers(),
			'body' => [],
		];
		$response = wp_remote_post($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("The API key or TaxDo ID you provided is not valid for the current environment. Please double-check your credentials and try again.", [], $url, $req);
		}
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function is_valid_post_code(string $post_code, string $state_code): bool
	{
		$url = sprintf('%s?zip_code=%s&state_code=%s', $this->tenant_url(self::VALIDATE_ZIPCODE), $post_code,
			$state_code);
		$req = [
			'headers' => $this->default_headers()
		];
		$response = wp_remote_get($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Zip code validation has been failed.", [], $url, $req);
		}

		$body =  $this->json_response_body($response);

		return $body['data']['is_valid'];
	}

	public function get_address_by_certificate_token(string $token): array
	{
		$url = sprintf('%s/%s', $this->tenant_url(self::ADDRESS_BY_CERTIFICATE_TOKEN_PATH), $token);
		$req = [
			'headers' => $this->default_headers(),
		];
		$response = wp_remote_get($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("The exemption code is not valid.", $response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	public function collect_certificate(array $data, string $file_path): array
	{
		[$body, $headers] = $this->get_body_and_header_for_uploading_afile($data, $file_path);

		$url = $this->tenant_url(self::COLLECTED_CERTIFICATE_PATH);
		$req = [
			'headers' => array_merge($this->default_headers(), $headers),
			'body' => $body,
		];
		$response = wp_remote_post($url, $req);

		if (!$this->is_response_ok($response)) {
			throw CommunicationFailed::via_http("Couldn't collect certificate data.", $response, $url, $req);
		}

		return $this->json_response_body($response);
	}

	private function get_body_and_header_for_uploading_afile(array $post_fields, string $local_file): array
	{
		$boundary = wp_generate_password(24);
		$headers = array(
			'content-type' => 'multipart/form-data; boundary=' . $boundary,
		);
		$payload = '';
// First, add the standard POST fields:
		foreach ($post_fields as $name => $value) {
			$payload .= '--' . $boundary;
			$payload .= "\r\n";
			$payload .= 'Content-Disposition: form-data; name="' . $name .
				'"' . "\r\n\r\n";
			$payload .= $value;
			$payload .= "\r\n";
		}
// Upload the file
		if ($local_file) {
			$payload .= '--' . $boundary;
			$payload .= "\r\n";
			$payload .= 'Content-Disposition: form-data; name="' . 'files[]' .
				'"; filename="' . basename($local_file) . '"' . "\r\n";
			$payload .= "\r\n";
			$payload .= file_get_contents($local_file);
			$payload .= "\r\n";
		}
		$payload .= '--' . $boundary . '--';

		return [$payload, $headers];
	}
}
