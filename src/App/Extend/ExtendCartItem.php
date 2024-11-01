<?php

namespace TaxDo\WooCommerce\App\Extend;

use Exception;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;
use TaxDo\WooCommerce\Infra\Repository\ProductRepository;
use Automattic\WooCommerce\StoreApi\Schemas\V1\CartItemSchema;


class ExtendCartItem
{

	const IDENTIFIER = 'taxdo_tax_class';

	public function __construct(
		private ProductRepository $product_repository)
	{
	}

	public function extend_store(ExtendSchema $extend_rest_api): void
	{
		$extend_rest_api->register_endpoint_data(
			array(
				'endpoint' => CartItemSchema::IDENTIFIER,
				'namespace' => self::IDENTIFIER,
				'data_callback' => [$this, 'extend_cart_item_data'],
				'schema_callback' => [$this, 'extend_cart_item_schema'],
				'schema_type' => ARRAY_A,
			)
		);
	}

	/**
	 * @throws Exception
	 */
	public function extend_cart_item_data($cart_item)
	{
		$product = $cart_item['data'];
		$tax_class = $this->product_repository->find_tax_class($product->get_id());
		return [
			'tc_id' => $tax_class?->id(),
			'tc_us_id' => $tax_class?->us_id,
			'tc_ca_id' => $tax_class?->ca_id,
			'tc_name' => $tax_class?->name(),
			'tc_us_name' => $tax_class?->us_name,
			'tc_ca_name' => $tax_class?->ca_name,
		];
	}

	public function extend_cart_item_schema()
	{
		return array(
			'tc_id' => array(
				'description' => __('Taxdo universal tax class id.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
			'tc_us_id' => array(
				'description' => __('Taxdo us tax class id.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
			'tc_ca_id' => array(
				'description' => __('Taxdo ca tax class id.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
			'tc_name' => array(
				'description' => __('Taxdo universal tax class name.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
			'tc_us_name' => array(
				'description' => __('Taxdo us tax class name.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
			'tc_ca_name' => array(
				'description' => __('Taxdo ca tax class name.', 'taxdo'),
				'type' => array('string', 'null'),
				'context' => array('view', 'edit'),
				'readonly' => true,
			),
		);
	}
}
