<?php

namespace TaxDo\WooCommerce\App;

use WC_Order_Item_Product;
use TaxDo\WooCommerce\App\PreProcess\CalculatedCartItems;

class CalcOrderItemTax
{
	public function execute(WC_Order_Item_Product $item, $calculate_tax_for)
	{
		$calculated_tax_items = WC()->session->get(GetItemTaxRate::TAXDO_ASSESSMENT_KEY, false);

		if (!$calculated_tax_items) return;
		/**
		 * @var CalculatedCartItems
		 */
		$calculated_tax_items = $calculated_tax_items['assessment'];
		if (!$calculated_tax_items) return;

		$calculatedTaxItem = $calculated_tax_items->get_by_product_id($item->get_product_id());

		if ($calculatedTaxItem && $calculatedTaxItem->should_be_applied()) {

			$item->set_taxes(
				array(
					'total' => ["0" => $calculatedTaxItem->tax_amount()],
					'subtotal' => ["0" => $calculatedTaxItem->tax_amount()],
				)
			);

			$item->add_meta_data('_taxdo_added', 'yes', true);
			$item->save_meta_data();
		}
	}
}
