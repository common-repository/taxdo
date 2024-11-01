<?php

namespace TaxDo\WooCommerce\Infra\Repository;


use Exception;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Domain\TaxClass\TaxClass;
use TaxDo\WooCommerce\Domain\TaxClass\TaxClasses;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;


class TaxClassRepository
{
	private const CURRENT_TAX_CLASSES_CACHE_KEY = 'taxdo_current_tax_classes';
	private const OPTION_KEY = 'taxdo_woocommerce_tax_classes';

	public function __construct(
		private Client $client
	)
	{
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function get_active_tax_classes(): TaxClasses
	{
		$active_tax_classes = $this->load_active_tax_classes_from_cache();

		if (is_null($active_tax_classes)) {
			$active_tax_classes = $this->get_tax_classes_from_taxdo();
			$local_tax_classes = $this->all();

			$mergedTaxClasses = $local_tax_classes->merge($active_tax_classes);

			$this->persist_tax_classes($mergedTaxClasses);
			$this->cache_active_tax_classes($active_tax_classes);
		}

		return $active_tax_classes;
	}

	private function load_active_tax_classes_from_cache(): ?TaxClasses
	{
		$pureTaxClasses = get_transient(self::CURRENT_TAX_CLASSES_CACHE_KEY);
		if ($pureTaxClasses) {
			return TaxClasses::from_pure_array($pureTaxClasses);
		}

		return null;
	}

	/**
	 * @throws CommunicationFailed
	 */
	private function get_tax_classes_from_taxdo(): TaxClasses
	{
		$tax_classes = [];
		$taxdo_universal_tax_classes = $this->client->get_universal_tax_classes()['data'];
		foreach ($taxdo_universal_tax_classes as $tax_class_data) {
			$country_data = [];
			foreach ($tax_class_data['tax_classes'] as $tax_class) {
				if ($tax_class['country_code'] == 'US') {
					$country_data['us_id'] = $tax_class['id'];
					$country_data['us_name'] = $tax_class['name'];
				}
				if ($tax_class['country_code'] == 'CA') {
					$country_data['ca_id'] = $tax_class['id'];
					$country_data['ca_name'] = $tax_class['name'];
				}
			}
			$tax_classes[] = new TaxClass(
				$tax_class_data['id'],
				$tax_class_data['name'],
				$country_data['us_id'],
				$country_data['us_name'],
				$country_data['ca_id'],
				$country_data['ca_name']);
		}

		return TaxClasses::from_array($tax_classes);
	}

	/**
	 * Include active and archived tax classes
	 *
	 * @return TaxClasses
	 */
	private function all(): TaxClasses
	{
		$tax_classes = get_option(self::OPTION_KEY, []);

		return TaxClasses::from_array($tax_classes);
	}

	private function persist_tax_classes(TaxClasses $tax_classes): void
	{
		update_option(self::OPTION_KEY, $tax_classes->as_array());
	}

	private function cache_active_tax_classes(TaxClasses $tax_classes): void
	{
		$pureTaxClasses = [];
		foreach ($tax_classes->as_array() as $tax_class) {
			$pureTaxClasses[] = $tax_class->as_array();
		}
		set_transient(self::CURRENT_TAX_CLASSES_CACHE_KEY, $pureTaxClasses, DAY_IN_SECONDS);
	}

	/**
	 * @throws Exception
	 */
	public function get(int $id): TaxClass
	{
		$tax_classes = $this->all();
		return $tax_classes->get($id);
	}
}
