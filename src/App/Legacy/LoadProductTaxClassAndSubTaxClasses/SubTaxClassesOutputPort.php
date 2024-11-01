<?php


namespace TaxDo\WooCommerce\App\Legacy\LoadProductTaxClassAndSubTaxClasses;


use TaxDo\WooCommerce\Domain\SubTaxClass\ItemSubTaxClass;
use TaxDo\WooCommerce\Domain\SubTaxClass\Value\SubTaxClasses;

interface SubTaxClassesOutputPort
{
	public function present(SubTaxClasses $sub_tax_classes, ?ItemSubTaxClass $selected_sub_tax_class, int $product_id): void;
}
