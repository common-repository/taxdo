<?php


namespace TaxDo\WooCommerce\Infra\TaxDo;


use TaxDo\WooCommerce\Domain\DomainException;

class CommunicationFailed extends DomainException
{
	public const COMMUNICATION_FAILED_MESSAGES = [
		1011 => 'Certificate upload has been failed. Please check your zip code and try again.',
		1014 => 'Tax calculation failed: Invalid address provided.',
		1036 => 'The certificate code is invalid or has expired.',
		'CALCULATING_TAX' => "Tax can't be calculated."
	];

	private const CALCULATING_TAX = 'CALCULATING_TAX';

	private ?string $task = null;

	public function __construct(string $message, array $detail = [], ?string $task = null)
	{
		parent::__construct($message, $detail);
		$this->task = $task;
	}

	public static function to_calculate_tax($response, string $url, array $request): self
	{
		return self::via_http(self::COMMUNICATION_FAILED_MESSAGES['CALCULATING_TAX'], $response, $url, $request,
			self::CALCULATING_TAX);
	}

	public static function via_http(string $message, $response, string $url, array $request, ?string $task = null): self
	{
		$detail = ['message' => $message, 'url' => $url, 'request' => $request];

		switch (is_wp_error($response)) {
			case true:
				$detail['wordpressError'] = $response->get_error_message();
				break;

			case false:
				$responseBody = json_decode(wp_remote_retrieve_body($response), true);
				$responseBody = $responseBody ?? [];
				if (array_key_exists('data', $responseBody)) {
					if (array_key_exists('message', $responseBody['data'])) {
						$message .= ' ' . $responseBody['data']['message'];
					}
					if (array_key_exists('code', $responseBody['data'])) {
						$customMessage = self::find_custom_message_from_response_code($responseBody['data']['code']);
						if (!is_null($customMessage)) {
							$message = $customMessage;
						}
					}
				}
				$detail['responseBody'] = $responseBody;
				$detail['responseCode'] = wp_remote_retrieve_response_code($response);
				break;

			default:
				break;
		}

		return new self($message, $detail, $task);
	}

	private static function find_custom_message_from_response_code(int $code): ?string
	{
		if (!array_key_exists($code, self::COMMUNICATION_FAILED_MESSAGES)) {
			return null;
		}

		return self::COMMUNICATION_FAILED_MESSAGES[$code];
	}
}
