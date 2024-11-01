<?php


namespace TaxDo\WooCommerce\Infra\Listener\Legacy;

use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\App\Legacy\UpdateBillingAddress;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClassAndSubTaxClasses\LoadProductTaxClassAndSubTaxClasses;

final class CartItemListener implements HookListener
{
	public function __construct(
		private LoadProductTaxClassAndSubTaxClasses $load_product_tax_class_and_sub_tax_classes_use_case,
		private UpdateBillingAddress                $update_billing_address
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::filter_conditional(),
				'woocommerce_checkout_cart_item_quantity',
				[$this, 'on_cart_item_loaded_in_checkout_page'],
				10,
				2
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_calculated_shipping',
				[$this->update_billing_address, 'execute']
			),
		]);
	}

	public function on_cart_item_loaded_in_checkout_page(string $product_quantity, array $cart_item_data): void
	{
		$this->load_product_tax_class_and_sub_tax_classes_use_case->execute($cart_item_data['product_id']);
	}
}
