<?php


namespace TaxDo\WooCommerce\Domain\TaxClass;


use Exception;

final class TaxClasses
{
	/**
	 * @var TaxClass[]
	 */
	private array $tax_classes = [];

	/**
	 * TaxClasses constructor.
	 *
	 * @param TaxClass[] $tax_classes
	 */
	private function __construct(array $tax_classes)
	{
		foreach ($tax_classes as $tax_class) {
			$this->add_tax_class($tax_class);
		}
	}

	/**
	 * @param TaxClass $tax_class
	 */
	private function add_tax_class(TaxClass $tax_class): void
	{
		$this->tax_classes[] = $tax_class;
	}

	/**
	 * @param array $pure_tax_classes
	 *
	 * @return static
	 */
	public static function from_pure_array(array $pure_tax_classes): self
	{
		$tax_classes = [];
		foreach ($pure_tax_classes as $pure) {
			$tax_classes[] = new TaxClass(
				$pure['id'],
				$pure['name'],
				$pure['us_id'],
				$pure['us_name'],
				$pure['ca_id'],
				$pure['ca_name']);
		}

		return new self($tax_classes);
	}

	/**
	 * @return TaxClass[]
	 */
	public function as_array(): array
	{
		return $this->tax_classes;
	}

	public function merge(self $other): self
	{
		$other_tax_classes = $other->tax_classes;
		$merged_tax_classes = [];
		foreach ($this->tax_classes as $tax_class) {
			foreach ($other_tax_classes as $i => $other) {
				if ($other->is_equal_to($tax_class)) {
					unset($other_tax_classes[$i]);
					$tax_class = $other;
					break;
				}
			}

			$merged_tax_classes[] = $tax_class;
		}

		return self::from_array(array_merge($merged_tax_classes, $other_tax_classes));
	}

	/**
	 * @param TaxClass[] $tax_classes
	 *
	 * @return static
	 */
	public static function from_array(array $tax_classes): self
	{
		return new self($tax_classes);
	}

	/**
	 * @throws Exception
	 */
	public function get(string $id): TaxClass
	{
		foreach ($this->tax_classes as $tax_class) {
			if ($id === (string)$tax_class->id()) {
				return $tax_class;
			}
		}

		// TODO: Throw a custom exception.
		throw new Exception('No tax class was set with id ' . $id);
	}
}
