<?php


namespace TaxDo\WooCommerce\Domain\SubTaxClass\Value;


use TaxDo\WooCommerce\Domain\SubTaxClass\SubTaxClass;

final class SubTaxClasses
{
	/**
	 * @var SubTaxClass[]
	 */
	private array $classes = [];

	/**
	 * SubTaxClasses constructor.
	 *
	 * @param SubTaxClass[] $classes
	 */
	private function __construct(array $classes)
	{
		foreach ($classes as $class) {
			$this->add_class($class);
		}
	}

	private function add_class(SubTaxClass $class): void
	{
		$this->classes[] = $class;
	}

	public static function empty(): self
	{
		return new self([]);
	}

	/**
	 * @return SubTaxClass[]
	 */
	public function as_array(): array
	{
		return $this->classes;
	}

	public function is_empty(): bool
	{
		return 0 === count($this->classes);
	}

	public function for_tax_class(int $tax_class_id): self
	{
		return self::from_array(array_filter(
			$this->classes,
			static function (SubTaxClass $sub_tax_class) use ($tax_class_id) {
				return $tax_class_id === $sub_tax_class->tax_class_id();
			}
		));
	}

	/**
	 * @param SubTaxClass[] $classes
	 *
	 * @return $this
	 */
	public static function from_array(array $classes): self
	{
		return new self($classes);
	}
}
