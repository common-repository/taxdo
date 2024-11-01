<?php

namespace TaxDo\WooCommerce\App;

use WP_REST_Server;
use WP_REST_Response;
use WP_REST_Controller;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;

class SubTaxClasses extends WP_REST_Controller
{
	public function __construct(
		private Client $taxdo_client
	)
	{
	}

	public function register_routes()
	{
		register_rest_route('taxdo/v1', '/states/(?P<state_code>[a-zA-Z]+)/sub-tax-classes', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_store_rest'],
			'permission_callback' => '__return_true',
		]);
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function get_store_rest($request): WP_REST_Response
	{
		$store_code = sanitize_text_field($request['state_code']);
		$sub_tax_classes = $this->taxdo_client->get_sub_tax_classes_of_state($store_code);

		$response = new WP_REST_Response($sub_tax_classes);
		$response->set_status(200);

		return $response;
	}
}
