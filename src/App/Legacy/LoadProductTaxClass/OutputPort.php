<?php


namespace TaxDo\WooCommerce\App\Legacy\LoadProductTaxClass;


interface OutputPort
{
	public function present(string $tax_class_name): void;
}
