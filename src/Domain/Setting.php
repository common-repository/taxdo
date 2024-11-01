<?php


namespace TaxDo\WooCommerce\Domain;

class Setting
{
	public const API_LIVE_MODE = 'live';
	public const API_SANDBOX_MODE = 'sandbox';
	public const OPTION_KEY = 'woocommerce_taxdo_settings';
	private const ENABLING_KEY = 'active_taxdo';
	private const SHOWING_CERTIFICATE_CODE_INPUT_KEY = 'certificate_code';
	private const SHOWING_UPLOAD_CERTIFICATE_INPUT_KEY = 'certificate_taxdo_upload';
	private const SENDING_INVOICE_EMAILS_KEY = 'email_invoice_taxdo';
	private const DEBUG_MODE = 'debug_mode';
	private const ACTIVATED_VALUE = 'yes';

	public static function source(): string
	{
		$prefix = 'WooCommerce';
		$setting = get_option(self::OPTION_KEY, null);
		if (is_null($setting)) {
			return $prefix;
		}

		return sprintf('%s : %s', $prefix, $setting['store_name']);
	}

	public function is_debug_mode(): bool
	{
		$setting = get_option(self::OPTION_KEY, null);
		if (is_null($setting)) {
			return false;
		}

		return self::ACTIVATED_VALUE === $setting[self::DEBUG_MODE];
	}

	public function should_calculate_tax_using_taxdo(): bool
	{
		$setting = get_option(self::OPTION_KEY, null);
		if (is_null($setting)) {
			return false;
		}

		return self::ACTIVATED_VALUE === $setting[self::ENABLING_KEY];
	}

	public function show_upload_certificate_input(): bool
	{
		$setting = get_option(self::OPTION_KEY, null);
		if (is_null($setting)) {
			return false;
		}

		return self::ACTIVATED_VALUE === $setting[self::SHOWING_UPLOAD_CERTIFICATE_INPUT_KEY];
	}

	public function show_certificate_code_input(): bool
	{
		$setting = get_option(self::OPTION_KEY, null);
		if (is_null($setting)) {
			return false;
		}

		return self::ACTIVATED_VALUE === $setting[self::SHOWING_CERTIFICATE_CODE_INPUT_KEY];
	}

	public function should_send_invoice_emails(): bool
	{
		$setting = get_option(self::OPTION_KEY, null);
		if (is_null($setting)) {
			return false;
		}

		return self::ACTIVATED_VALUE === $setting[self::SENDING_INVOICE_EMAILS_KEY];
	}

	public function get_credential(): array
	{
		$setting = get_option(self::OPTION_KEY, null);

		if (is_null($setting)) {
			return [
				'mode' => self::API_LIVE_MODE,
				'taxdo_id' => '',
				'api_key' => ''
			];
		}

		return [
			'mode' => (isset($setting['sandbox']) and $setting['sandbox'] === 'yes')
				? self::API_SANDBOX_MODE
				: self::API_LIVE_MODE,
			'taxdo_id' => $setting['taxdo_id'],
			'api_key' => $setting['api_key']
		];
	}
}
