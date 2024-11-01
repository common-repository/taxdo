<?php

namespace TaxDo\WooCommerce\Infra\Listener;

use WC_Cart;
use DateTime;
use WC_Order;
use WP_Error;
use Exception;
use TaxDo\WooCommerce\App\GetItemTaxRate;
use TaxDo\WooCommerce\App\PreProcess\PreProcess;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\App\Legacy\FixLineItemTax\FixLineItemTax;


class CalcTotalListener implements HookListener
{

	public function __construct(
		private PreProcess     $pre_process,
		private GetItemTaxRate $get_item_tax_rate,
		private FixLineItemTax $fix_line_item_tax
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_conditional(),
				'woocommerce_before_calculate_totals',
				[$this, 'preprocess']
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_cart_totals_get_item_tax_rates',
				[$this, 'get_item_tax_rate'],
				10,
				3
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_after_checkout_validation',
				[$this, 'validate_checkout'],
				10,
				2
			),
			// Legacy
			new HookData(
				HookType::action_conditional(),
				'woocommerce_checkout_create_order',
				[$this, 'on_checkout_create_order'],
				10,
				2
			),
		]);
	}

	/**
	 * @throws Exception
	 */
	public function preprocess(WC_Cart $cart): void
	{
		WC()->session->__unset(GetItemTaxRate::TAXDO_ASSESSMENT_KEY);

		$calculated_tax_items = $this->pre_process->execute();

		if ($calculated_tax_items) {
			WC()->session->set(GetItemTaxRate::TAXDO_ASSESSMENT_KEY, [
				'assessment' => $calculated_tax_items,
				'calculated_at' => new DateTime()
			]);
		}
	}

	function get_item_tax_rate(array $item_tax_rates, $item, WC_Cart $cart): array
	{
		return $this->get_item_tax_rate->execute($item_tax_rates, $item, $cart);
	}

	function on_checkout_create_order(WC_Order $order, array $data)
	{
		$this->fix_line_item_tax->execute($order);
	}

	function validate_checkout(array &$data, WP_Error &$errors)
	{
		if (!WC()->session->get(GetItemTaxRate::TAXDO_ASSESSMENT_KEY, false)) {
			$errors->add('tax', 'Tax calculation is currently unavailable due to invalid or incomplete entered data. Please review your information before proceeding with the order.');
		}
	}
}
