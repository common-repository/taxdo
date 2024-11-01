<?php

namespace TaxDo\WooCommerce\App\Legacy;

class UpdateBillingAddress
{
	public function execute(): void
	{
		$tax_based_on = get_option('woocommerce_tax_based_on');
		if ('billing' !== $tax_based_on) {
			return;
		}

		$customer = WC()->customer;

		if (
			$customer->get_shipping_postcode() === $customer->get_billing_postcode()
			and $customer->get_shipping_state() === $customer->get_billing_state()
		) {
			return;
		}

		$customer->set_billing_country($customer->get_shipping_country());
		$customer->set_billing_state($customer->get_shipping_state());
		$customer->set_billing_city($customer->get_shipping_city());
		$customer->set_billing_address_1($customer->get_shipping_address_1());
		$customer->set_billing_address_2($customer->get_shipping_address_2());
		$customer->set_billing_postcode($customer->get_shipping_postcode());
		$customer->save();
	}
}
