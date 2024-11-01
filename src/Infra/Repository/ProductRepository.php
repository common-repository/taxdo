<?php

namespace TaxDo\WooCommerce\Infra\Repository;

use Exception;
use TaxDo\WooCommerce\Domain\TaxClass\TaxClass;
use TaxDo\WooCommerce\App\AssignProductTaxClass;

class ProductRepository
{
	public function __construct(
		private TaxClassRepository $tax_class_repository
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function find_tax_class(int $product_id): ?TaxClass
	{
		$tax_class_id = $this->find_tax_class_id($product_id);
		if (!$tax_class_id) return null;

		return $this->tax_class_repository->get($tax_class_id);
	}

	public function find_tax_class_id(int $product_id)
	{
		$product = wc_get_product($product_id);
		$id = $product->get_meta(AssignProductTaxClass::KEY_IN_PRODUCT);

		return '' === $id ? null : $id;
	}
}
