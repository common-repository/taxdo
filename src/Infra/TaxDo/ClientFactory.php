<?php


namespace TaxDo\WooCommerce\Infra\TaxDo;


use TaxDo\WooCommerce\Domain\Setting;

class ClientFactory
{

	public function __construct(
		private string  $tax_do_base_url,
		private string  $sandbox_tax_do_base_url,
		private Setting $setting)
	{
	}

	public function make(string $mode = null, string $taxdoId = null, string $apiKey = null): Client
	{
		if (!$mode) {
			$credential = $this->setting->get_credential();
			$mode = $credential['mode'];
			$taxdoId = $credential['taxdo_id'];
			$apiKey = $credential['api_key'];

		}
		$url = $mode === Setting::API_LIVE_MODE ? $this->tax_do_base_url : $this->sandbox_tax_do_base_url;

		return new Client(
			$url,
			$taxdoId,
			$apiKey
		);
	}
}
