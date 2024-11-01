<?php

namespace TaxDo\WooCommerce\App;

use TaxDo\WooCommerce\Domain\TaxClass\TaxClass;

class AssignProductTaxClass
{
	public const KEY_IN_PRODUCT = '_tax_do_class_id';

	public function execute(int $product_id, int $tax_class_id): void
	{
		$product = wc_get_product($product_id);
		if (!$product) return;

		if (!TaxClass::is_valid_id($tax_class_id)) {
			$product->delete_meta_data(self::KEY_IN_PRODUCT);
		} else {
			$product->add_meta_data(self::KEY_IN_PRODUCT, $tax_class_id, true);
		}

		$product->save();
	}
}
