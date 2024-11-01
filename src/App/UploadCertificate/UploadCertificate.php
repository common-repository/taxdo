<?php

namespace TaxDo\WooCommerce\App\UploadCertificate;

use WP_Error;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Controller;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;

class UploadCertificate extends WP_REST_Controller
{
	public function __construct(
		private Client $taxdo_client
	)
	{
	}

	public function register_routes()
	{
		register_rest_route('taxdo/v1', '/certificate-request', [
			'methods' => WP_REST_Server::CREATABLE,
			'callback' => [$this, 'get_store_rest'],
			'permission_callback' => '__return_true',
		]);
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function get_store_rest(WP_REST_Request $request): WP_REST_Response
	{
		$certificate_request = UploadCertificateInput::get_certificate_data(
			$request->get_params(),
			$request->get_file_params()
		);

		try {
			$response = $this->taxdo_client->collect_certificate($certificate_request->as_array(), $certificate_request->file_path());
			$response = new WP_REST_Response(['reference' => $response['data']['ref_no']]);
			$response->set_status(200);
		} catch (CommunicationFailed $e) {
			$response = new WP_Error('error', $e->getMessage(), ['status' => 400]);
		}

		return is_wp_error($response) ? $this->error_to_response($response) : $response;
	}

	protected function error_to_response($error)
	{
		$error_data = $error->get_error_data();
		$status = isset($error_data, $error_data['status']) ? $error_data['status'] : 500;
		$errors = [];

		foreach ((array)$error->errors as $code => $messages) {
			foreach ((array)$messages as $message) {
				$errors[] = array(
					'code' => $code,
					'message' => $message,
					'data' => $error->get_error_data($code),
				);
			}
		}

		$data = array_shift($errors);

		if (count($errors)) {
			$data['additional_errors'] = $errors;
		}

		return new WP_REST_Response($data, $status);
	}
}
