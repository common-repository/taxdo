<?php

namespace TaxDo\WooCommerce\App\Legacy;

use WC_Customer;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Infra\WordPress\ErrorHandler;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;

class ValidateZipCode
{
	public const ZIP_CODE_VALIDATION_CACHE_PREFIX_KEY = 'taxdo_zip_code';

	public function __construct(
		private Client $clint
	)
	{
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function execute(WC_Customer $newCustomer, $prevCustomer): void
	{
		$tax_based_on = get_option('woocommerce_tax_based_on');

		if ('billing' === $tax_based_on) {
			$state = $newCustomer->get_billing_state();
			$post_code = $newCustomer->get_billing_postcode();
			$country = $newCustomer->get_billing_country();
		} elseif ('shipping'  === $tax_based_on) {
			$state = $newCustomer->get_shipping_state();
			$post_code = $newCustomer->get_shipping_postcode();
			$country = $newCustomer->get_shipping_country();
		} else {
			return;
		}

		if ('US' !== $country) {
			return;
		}

		if (strlen($post_code) == 0 or strlen($state) == 0) {
			return;
		}

		$cache_key = sprintf('%s_%s_%s', self::ZIP_CODE_VALIDATION_CACHE_PREFIX_KEY, $post_code, $state);
		$isValid = get_transient($cache_key);

		if (!$isValid) {
			$result = $this->clint->is_valid_post_code($post_code, $state);

			$isValid = $result ? 'yes' : 'no';
			set_transient($cache_key, $isValid, HOUR_IN_SECONDS);
		}

		if ('yes' === $isValid) {
			return;
		}

		if ('billing' === $tax_based_on) {
			$newCustomer->set_billing_postcode(null);
		} elseif ('shipping'  === $tax_based_on) {
			$newCustomer->set_shipping_postcode(null);
		}

		$state_name = WC()->countries->get_states("US")[$state];

		$err = sprintf(__('Zip code (%s) is invalid in %s.', 'taxdo') , $post_code, $state_name);

		if (!ErrorHandler::has_notice($err,'error')) {
			wc_add_notice($err, 'error');
			ErrorHandler::set_last_error($err);
		}
	}
}
