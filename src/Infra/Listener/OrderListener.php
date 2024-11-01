<?php

namespace TaxDo\WooCommerce\Infra\Listener;

use Exception;
use TaxDo\WooCommerce\App\CalcOrderItemTax;
use TaxDo\WooCommerce\App\Invoice\CreateInvoice;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\App\Invoice\CompletePayment;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;

class OrderListener implements HookListener
{
	public function __construct(
		private CreateInvoice    $create_invoice,
		private CompletePayment  $complete_payment,
		private CalcOrderItemTax $cal_order_item_tax,
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			// Legacy
			new HookData(
				HookType::action_conditional(),
				'woocommerce_checkout_order_processed',
				[$this, 'legacy_create_invoice'],
				10,
				1
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_store_api_checkout_order_processed',
				[$this->create_invoice, 'execute'],
				10,
				1
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_order_status_completed',
				[$this->complete_payment, 'execute'],
				10,
				2
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_payment_complete',
				[$this->complete_payment, 'execute'],
				10,
				2
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_order_item_after_calculate_taxes',
				[$this->cal_order_item_tax, 'execute'],
				10,
				2
			),
		]);
	}

	/**
	 * @throws Exception
	 */
	public function legacy_create_invoice(int $order_id)
	{
		$order = wc_get_order($order_id);
		$this->create_invoice->execute($order);
	}
}
