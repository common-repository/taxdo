<?php

namespace TaxDo\WooCommerce\Infra\Listener;


use TaxDo\WooCommerce\App\UpdateSettings;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use Automattic\WooCommerce\Utilities\FeaturesUtil;
use TaxDo\WooCommerce\App\TaxdoIntegrationSettings;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;

class IntegrationListener implements HookListener
{
	public function __construct(
		private UpdateSettings $update_settings
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_always(),
				'woocommerce_integrations',
				[$this, 'add_integration']
			),
			new HookData(
				HookType::action_always(),
				'before_woocommerce_init',
				[$this, 'before_woocommerce_init']
			),
			new HookData(
				HookType::action_always(),
				'woocommerce_save_settings_integration_taxdo',
				[$this->update_settings, 'execute']
			),
			// Legacy
			new HookData(
				HookType::action_conditional(),
				'wp_enqueue_scripts',
				[$this, 'enqueue_scripts']
			)
		]);
	}

	public function add_integration(array $load_integrations): array
	{
		$load_integrations[] = TaxdoIntegrationSettings::class;

		return $load_integrations;
	}

	public function before_woocommerce_init()
	{
		if (class_exists(FeaturesUtil::class)) {
			FeaturesUtil::declare_compatibility('custom_order_tables', TAX_DO_PATH, true);
		}
	}

	public function enqueue_scripts()
	{
		if (is_cart() || is_checkout()) {
			wp_enqueue_script('taxdo-cart-script', TAX_DO_URL . 'assets/js/cart.min.js', array('jquery'), '1.2',
				true);
			wp_localize_script('taxdo-cart-script', 'cart_object',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce' => wp_create_nonce('taxdo-cart')
				)
			);

			wp_enqueue_style('taxdo-cart-style', TAX_DO_URL . 'assets/css/cart.min.css', false, '1.2', 'all');
		}
	}
}
