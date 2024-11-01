<?php

namespace TaxDo\WooCommerce\App;

use WC_Admin_Settings;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\App\PreProcess\PreProcess;
use TaxDo\WooCommerce\Infra\TaxDo\ClientFactory;
use TaxDo\WooCommerce\App\Legacy\ValidateZipCode;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;
use TaxDo\WooCommerce\Domain\SubTaxClass\Legacy\Service\SubTaxClassRepository;

class UpdateSettings
{
	public function __construct(
		private SubTaxClassRepository $sub_tax_class_repository,
		private ClientFactory         $client_factory,
		private Setting				  $setting
	)
	{
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function execute($shouldSave)
	{
		if (!$shouldSave) {
			return $shouldSave;
		}

		if (empty($_POST['woocommerce_taxdo_sandbox'])) {
			$mode = Setting::API_LIVE_MODE;
		} else {
			$mode = sanitize_text_field($_POST['woocommerce_taxdo_sandbox']) == '1' ? Setting::API_SANDBOX_MODE :
				Setting::API_LIVE_MODE;
		}

		$taxdo_id = !empty($_POST['woocommerce_taxdo_taxdo_id'])
			? sanitize_text_field($_POST['woocommerce_taxdo_taxdo_id'])
			: null;

		$api_key = !empty($_POST['woocommerce_taxdo_api_key'])
			? sanitize_text_field($_POST['woocommerce_taxdo_api_key'])
			: null;


		if ($taxdo_id and $api_key) {
			delete_transient('taxdo_current_tax_classes');
			$this->sub_tax_class_repository->delete_cache();
			$this->sub_tax_class_repository->delete_cache(PreProcess::PRE_PROCESS_CACHE_PREFIX_KEY);
			$this->sub_tax_class_repository->delete_cache(ValidateZipCode::ZIP_CODE_VALIDATION_CACHE_PREFIX_KEY);

			try {
				$this->client_factory->make($mode, $taxdo_id, $api_key)->connect();
			} catch (CommunicationFailed $exception) {
				$credential = $this->setting->get_credential();
				$_POST['woocommerce_taxdo_taxdo_id'] = $credential['taxdo_id'];
				$_POST['woocommerce_taxdo_api_key'] = $credential['api_key'];


				WC_Admin_Settings::add_error( sprintf(__( 'The API key or TaxDo ID you provided is not valid for the current environment (%s). Please double-check your credentials and try again.', 'taxdo' ), $mode) );
			}
		}

		return $shouldSave;
	}
}
