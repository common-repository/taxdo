<?php

namespace TaxDo\WooCommerce\App\Legacy\FixLineItemTax;

use WC_Order;

class FixLineItemTax
{

	public function execute(WC_Order $order)
	{
		$order_items = $order->get_items('line_item');

		$order->remove_order_items('line_item');
		foreach ($order_items as $key => $item) {
			$tax_rates = [];
			$taxes = $item->get_taxes();
			foreach ($taxes as $type => $tax) {
				if (isset($tax['taxdo'])) {
					$tax_rate = $tax['taxdo'];
					unset($tax['taxdo']);
					$tax['0'] = $tax_rate;
				}
				$tax_rates[$type] = $tax;
			}
			$item->set_taxes($tax_rates);
			$order->add_item($item);
		}
	}
}
