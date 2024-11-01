<?php


namespace TaxDo\WooCommerce\Domain\SubTaxClass\Legacy\Service;


use TaxDo\WooCommerce\Domain\SubTaxClass\Value\SubTaxClasses;

interface SubTaxClassRepository
{
	public function find_of_tax_class_in_state(int $tax_class_id, string $state_id): SubTaxClasses;
	public function delete_cache(?string $prefix): void;
}
