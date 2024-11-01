<?php

namespace TaxDo\WooCommerce\App;


use Exception;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Domain\Certificate\Certificate;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;
use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;


final class ApplyCertificate
{
	public function __construct(
		private CertificateRepository $certificate_repository,
		private Setting               $setting,
	)
	{
	}

	public function execute(string $code, bool $legacy = false): void
	{
		if (!$this->setting->show_certificate_code_input()) {
			throw new Exception('Can not apply certificate duo to setting.');
		}
		$this->certificate_repository->delete();

		if ($legacy) {
			try {
				$certificate = $this->certificate_repository->find_by_code($code);
				if (is_null($certificate)) {
					wc_add_notice(__('Sorry, your exemption code has not registered.', 'taxdo'), 'error');
					return;
				}
			} catch (CommunicationFailed $exception) {
				wc_add_notice($exception->getMessage(), 'error');
				return;
			}

			$this->certificate_repository->save($certificate);
			$this->setAddress($certificate);
			wc_add_notice(__('Your exemption code has been applied successfully.', 'taxdo'), 'success');
			return;
		}

		$certificate = $this->certificate_repository->find_by_code($code);

		$this->certificate_repository->save($certificate);
		$this->setAddress($certificate);
	}

	private function setAddress(Certificate $certificate)
	{
		$address = $certificate->address();

		WC()->customer->set_props(
			array(
				'billing_country' => $address->country_code(),
				'billing_state' => $address->state(),
				'billing_postcode' => $address->zipCode(),
				'billing_city' => $address->city(),
				'billing_address_1' => $address->address(),
				'billing_address_2' => null,

				'shipping_country' => $address->country_code(),
				'shipping_state' => $address->state(),
				'shipping_postcode' => $address->zipCode(),
				'shipping_city' => $address->city(),
				'shipping_address_1' => $address->address(),
				'shipping_address_2' => null,
			)
		);
	}
}
