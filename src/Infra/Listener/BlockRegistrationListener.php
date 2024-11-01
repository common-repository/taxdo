<?php

namespace TaxDo\WooCommerce\Infra\Listener;

use Exception;
use TaxDo\WooCommerce\App\ApplyCertificate;
use TaxDo\WooCommerce\App\AssignSubTaxClass;
use TaxDo\WooCommerce\App\RemoveCertificate;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Block\TaxClassBlockIntegration;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\Block\ApplyCertificateBlockIntegration;
use Automattic\WooCommerce\StoreApi\Exceptions\RouteException;


class BlockRegistrationListener implements HookListener
{

	public function __construct(
		private AssignSubTaxClass $assign_sub_tax_class,
		private ApplyCertificate  $apply_certificate,
		private RemoveCertificate $remove_certificate,
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_conditional(),
				'woocommerce_blocks_loaded',
				[$this, 'register_blocks']
			),
		]);
	}

	public function register_blocks()
	{
		add_action(
			'woocommerce_blocks_cart_block_registration',
			function ($integration_registry) {
				$integration_registry->register(new TaxClassBlockIntegration());
				$integration_registry->register(new ApplyCertificateBlockIntegration());
			}
		);
		add_action(
			'woocommerce_blocks_checkout_block_registration',
			function ($integration_registry) {
				$integration_registry->register(new TaxClassBlockIntegration());
				$integration_registry->register(new ApplyCertificateBlockIntegration());
			}
		);

		woocommerce_store_api_register_update_callback(
			[
				'namespace' => 'taxdo',
				'callback' => [$this, 'handle_store_api'],
			]
		);
	}

	/**
	 * @throws RouteException
	 */
	function handle_store_api($data)
	{
		try {
			match ($data['action']) {
				'change-sub-tax-class' => $this->assign_sub_tax_class->execute($data['item_sub_tax_classes']),
				'apply-certificate' => $this->apply_certificate->execute($data['certificate']),
				'remove-certificate' => $this->remove_certificate->execute()
			};
		} catch (CommunicationFailed $e) {
			throw new RouteException('error', $e->getMessage());
		} catch (Exception $e) {
			throw new RouteException('error', 'Server error');
		}
	}
}
