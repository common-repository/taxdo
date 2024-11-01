<?php


namespace TaxDo\WooCommerce\Infra\Repository\Legacy\SubTaxClass;


use TaxDo\WooCommerce\Domain\SubTaxClass\Value\SubTaxClasses;
use TaxDo\WooCommerce\Domain\SubTaxClass\Legacy\Service\SubTaxClassRepository;

final class SubTaxClassRepositoryUsingCache implements SubTaxClassRepository
{
	private const OPTION_KEY = 'taxdo-woocommerce_sub_tax_classes';
	private const TTL_IN_SEC = 43200; // 60 * 60 * 12 seconds

	private TaxDoLoadTaxClasses $repository;

	public function __construct(TaxDoLoadTaxClasses $repository)
	{
		$this->repository = $repository;
	}

	public function find_of_tax_class_in_state(int $tax_class_id, string $state_id): SubTaxClasses
	{
		$sub_tax_classes = get_transient($this->get_cache_key($state_id));

		if (!$sub_tax_classes) {
			$sub_tax_classes = $this->repository->find_of_tax_class_in_state($state_id);
			set_transient($this->get_cache_key($state_id), $sub_tax_classes, self::TTL_IN_SEC);
		}

		return $sub_tax_classes->for_tax_class($tax_class_id);
	}

	public function delete_cache(?string $prefix = null): void
	{
		$prefix = $prefix ?? $this->get_cache_prefix_key();
		foreach ($this->get_transient_keys_with_prefix($prefix) as $key) {
			delete_transient($key);
		}
	}

	private function get_transient_keys_with_prefix($prefix): array
	{
		global $wpdb;

		$prefix = $wpdb->esc_like('_transient_' . $prefix);
		$sql = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE '%s'";
		$keys = $wpdb->get_results($wpdb->prepare($sql, $prefix . '%'), ARRAY_A);

		if (is_wp_error($keys)) {
			return [];
		}

		return array_map(function ($key) {
			// Remove '_transient_' from the option name.
			return substr($key['option_name'], strlen('_transient_'));
		}, $keys);
	}

	private function get_cache_key(string $state_id): string
	{
		return sprintf("%s_%s", self::OPTION_KEY, $state_id);
	}

	private function get_cache_prefix_key(): string
	{
		return sprintf("%s_", self::OPTION_KEY);
	}

}
