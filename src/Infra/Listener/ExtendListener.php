<?php

namespace TaxDo\WooCommerce\Infra\Listener;

use TaxDo\WooCommerce\App\Extend\ExtendCart;
use Automattic\WooCommerce\StoreApi\StoreApi;
use TaxDo\WooCommerce\App\Extend\ExtendCartItem;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema;


class ExtendListener implements HookListener
{
	public function __construct(
		private ExtendCartItem $extend_cart_item,
		private ExtendCart     $extend_cart
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_conditional(),
				'woocommerce_blocks_loaded',
				[$this, 'extend_card_item']
			),
		]);
	}

	public function extend_card_item()
	{
		$extend = StoreApi::container()->get(ExtendSchema::class);
		$this->extend_cart_item->extend_store($extend);
		$this->extend_cart->extend_store($extend);
	}
}
