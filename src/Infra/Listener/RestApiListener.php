<?php

namespace TaxDo\WooCommerce\Infra\Listener;


use TaxDo\WooCommerce\App\SubTaxClasses;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\App\UploadCertificate\UploadCertificate;

class RestApiListener implements HookListener
{
	public function __construct(
		private SubTaxClasses     $sub_tax_classes,
		private UploadCertificate $upload_certificate
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_conditional(),
				'rest_api_init',
				[$this, 'rest_api_init']
			)
		]);
	}

	public function rest_api_init()
	{
		$this->sub_tax_classes->register_routes();
		$this->upload_certificate->register_routes();
	}
}
