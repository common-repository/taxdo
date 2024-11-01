<?php

namespace TaxDo\WooCommerce\App;

use WC_Cart;
use TaxDo\WooCommerce\App\PreProcess\CalculatedCartItems;

class GetItemTaxRate
{

	public const TAXDO_ASSESSMENT_KEY = 'taxdo_assessment';

	public function execute(array $item_tax_rates, $item, WC_Cart $cart): array
	{

		$calculated_tax_items = WC()->session->get(self::TAXDO_ASSESSMENT_KEY, false);

		if (!$calculated_tax_items) {
			return $item_tax_rates;
		}

		/**
		 * @var CalculatedCartItems
		 */
		$calculated_tax_items = $calculated_tax_items['assessment'];

		$calculatedTaxItem = $calculated_tax_items->get_by_item_id($item->key);

		if ($calculatedTaxItem && $calculatedTaxItem->should_be_applied()) {
			$item_tax_rates = array();
			$item_tax_rates['taxdo'] = array(
				'rate' => (float)$calculatedTaxItem->tax_rate() * 100,
				'label' => 'taxdo',
				'shipping' => 'no',
				'compound' => 'yes',
				'taxdo' => 'yes',
			);
		}

		return $item_tax_rates;
	}
}
