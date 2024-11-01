<?php


namespace TaxDo\WooCommerce\Infra\Repository;


use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Domain\Customer\Value\State;
use TaxDo\WooCommerce\Domain\Customer\Value\Address;
use TaxDo\WooCommerce\Domain\Certificate\Certificate;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;

final class CertificateRepository
{
	private const SESSION_KEY = 'tax_do_certificate';

	public function __construct(private Client $client)
	{
	}

	public function find(): ?Certificate
	{
		$certificate = WC()->session->get(self::SESSION_KEY);
		if (is_null($certificate)) {
			return null;
		}

		return $certificate;
	}

	public function save(Certificate $certificate): void
	{
		WC()->session->set(self::SESSION_KEY, $certificate);
	}

	public function delete(): void
	{
		WC()->session->set(self::SESSION_KEY, null);
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function find_by_code(string $code): ?Certificate
	{
		$address = $this->client->get_address_by_certificate_token($code);
		$address_data = $address['data'];

		return new Certificate(
			$code,
			new Address(
				"",
				$address_data['country_code'],
				$address_data['state_code'],
				$address_data['city'],
				$address_data['zip_code'],
				$address_data['address']
			)
		);
	}
}
