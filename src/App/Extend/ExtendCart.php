<?php

namespace TaxDo\WooCommerce\App\Extend;

use Exception;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema;
use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;


class ExtendCart
{

	const IDENTIFIER = 'taxdo_certificate';

	public function __construct(
		private CertificateRepository $certificate_repository
	)
	{
	}

	public function extend_store(ExtendSchema $extend_rest_api): void
	{
		$extend_rest_api->register_endpoint_data(
			array(
				'endpoint' => CartSchema::IDENTIFIER,
				'namespace' => self::IDENTIFIER,
				'data_callback' => [$this, 'extend_cart_data'],
				'schema_callback' => [$this, 'extend_cart_schema'],
				'schema_type' => ARRAY_A,
			)
		);
	}

	/**
	 * @throws Exception
	 */
	public function extend_cart_data()
	{
		$certificate = $this->certificate_repository->find();

		return [
			'certificate' => $certificate?->code(),
			'valid_state' => $certificate?->address()->state()?->name(),
		];
	}

	public function extend_cart_schema()
	{
		return array(
			'certificate' => array(
				'description' => __('Taxdo certificate code.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
			'valid_state' => array(
				'description' => __('The Certificate is valid for state.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			)
		);
	}
}
