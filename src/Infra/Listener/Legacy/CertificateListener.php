<?php

namespace TaxDo\WooCommerce\Infra\Listener\Legacy;


use TaxDo\WooCommerce\App\ApplyCertificate;
use TaxDo\WooCommerce\App\RemoveCertificate;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs\ShowCertificateInputs;

class CertificateListener implements HookListener
{
	public function __construct(
		private ApplyCertificate      $apply_certificate_use_case,
		private ShowCertificateInputs $show_certificate_inputs_use_case,
		private RemoveCertificate     $remove_certificate
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_conditional(),
				'wp_ajax_taxdo_apply_certificate',
				[$this, 'taxdo_apply_certificate']
			),
			new HookData(
				HookType::action_conditional(),
				'wp_ajax_nopriv_taxdo_apply_certificate',
				[$this, 'taxdo_apply_certificate']
			),
			new HookData(
				HookType::action_conditional(),
				'wp_ajax_taxdo_remove_certificate',
				[$this, 'remove_certificate']
			),
			new HookData(
				HookType::action_conditional(),
				'wp_ajax_nopriv_taxdo_remove_certificate',
				[$this, 'remove_certificate']
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_after_cart_contents',
				[$this->show_certificate_inputs_use_case, 'certificate_code']
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_before_cart_collaterals',
				[$this->show_certificate_inputs_use_case, 'upload_certificate']
			),
			new HookData(
				HookType::action_conditional(),
				'woocommerce_cart_totals_before_order_total',
				[$this->show_certificate_inputs_use_case, 'show_applied_certificate_code']
			)
		]);
	}

	function taxdo_apply_certificate()
	{
		check_ajax_referer('taxdo-cart', 'security');
		if (array_key_exists('taxdo_cert_code', $_POST)) {
			$this->apply_certificate_use_case->execute(wc_clean(wp_unslash(sanitize_text_field($_POST['taxdo_cert_code']))), true);
		}

		wp_send_json_success();
	}

	function remove_certificate()
	{
		check_ajax_referer('taxdo-cart', 'security');
		$this->remove_certificate->execute();
		wc_add_notice(__('Exemption code has been removed.', 'taxdo'), 'success');

		wp_send_json_success();
	}
}
