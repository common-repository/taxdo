<?php


namespace TaxDo\WooCommerce\App\Legacy\Forms;


use TaxDo\WooCommerce\Domain\SubTaxClass\ItemSubTaxClass;
use TaxDo\WooCommerce\Domain\SubTaxClass\Value\ItemSubTaxClasses;

final class SubTaxClassesInput
{
	private const PREFIX = 'sub_tax_taxDo_';

	public static function from_posted_data(array $request): ItemSubTaxClasses
	{
		$sub_tax_classes = [];

		foreach ($request as $key => $value) {
			if (str_contains($key, self::PREFIX)) {
				$product_id = str_replace(self::PREFIX, "", wc_clean(wp_unslash((sanitize_text_field($key)))));
				// TODO: fix item_key
				$sub_tax_classes[] = new ItemSubTaxClass(wc_clean(wp_unslash(sanitize_text_field($value))), $product_id, '');
			}
		}

		return ItemSubTaxClasses::from_array($sub_tax_classes);
	}

	public static function id_for_product(int $productId): string
	{
		return sprintf('%s%d', self::PREFIX, $productId);
	}
}
