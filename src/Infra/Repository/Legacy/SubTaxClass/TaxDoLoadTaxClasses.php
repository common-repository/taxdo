<?php


namespace TaxDo\WooCommerce\Infra\Repository\Legacy\SubTaxClass;


use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Domain\SubTaxClass\SubTaxClass;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;
use TaxDo\WooCommerce\Domain\SubTaxClass\Value\SubTaxClasses;

final class TaxDoLoadTaxClasses
{
	private Client $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function find_of_tax_class_in_state(string $state_id): SubTaxClasses
	{
		$sub_tax_classes_data = $this->client->get_sub_tax_classes_of_state($state_id);

		$sub_tax_classes = [];
		$tax_classes = $sub_tax_classes_data['data'] ?? [];
		foreach ($tax_classes as $tax_class) {
			foreach ($tax_class['sub_tax_classes'] as $sub) {
				$sub_tax_classes[] = new SubTaxClass($sub['id'], $sub['name'], $tax_class['tax_class_id']);
			}
		}

		return SubTaxClasses::from_array($sub_tax_classes);
	}
}
