<?php

namespace TaxDo\WooCommerce\Infra\Listener\Legacy;

use TaxDo\WooCommerce\App\Legacy\ValidateZipCode;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\Infra\Repository\CartItemRepository;
use TaxDo\WooCommerce\App\Legacy\Forms\SubTaxClassesInput;

class AddressListener implements HookListener
{

	public function __construct(
		private CartItemRepository $cart_item_repository,
		private ValidateZipCode $validate_zip_code
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_conditional(),
				'woocommerce_checkout_update_order_review',
				[$this, 'on_order_review_updated']
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_before_customer_object_save',
				[$this->validate_zip_code, 'execute'],
				10,
				2
			)
		]);
	}

	public function on_order_review_updated(string $posted_data): void
	{
		parse_str($posted_data, $output);
		$this->cart_item_repository->persist_item_sub_tax_class_ids(SubTaxClassesInput::from_posted_data($output));
	}
}
