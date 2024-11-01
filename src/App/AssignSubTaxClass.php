<?php

namespace TaxDo\WooCommerce\App;

use TaxDo\WooCommerce\Domain\SubTaxClass\ItemSubTaxClass;
use TaxDo\WooCommerce\Infra\Repository\CartItemRepository;
use TaxDo\WooCommerce\Domain\SubTaxClass\Value\ItemSubTaxClasses;

class AssignSubTaxClass
{
	public function __construct(
		private CartItemRepository $cart_item_repository
	)
	{
	}

	public function execute(array $item_sub_tax_classes): void
	{
		$item_sub_tax_classes_array = [];
		foreach ($item_sub_tax_classes as $item_sub_tax_class) {
			$item_sub_tax_classes_array[] = new ItemSubTaxClass(
				$item_sub_tax_class['value'],
				$item_sub_tax_class['id'],
				$item_sub_tax_class['key'],
				null
			);
		}
		$this->cart_item_repository->persist_item_sub_tax_class_ids(ItemSubTaxClasses::from_array($item_sub_tax_classes_array));
	}
}
