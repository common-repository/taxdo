<?php


namespace TaxDo\WooCommerce\Domain\SubTaxClass\Value;


use TaxDo\WooCommerce\Domain\SubTaxClass\ItemSubTaxClass;

final class ItemSubTaxClasses
{
	/**
	 * @var ItemSubTaxClass[]
	 */
	private array $sub_classes = [];

	/**
	 * @param ItemSubTaxClass[] $classes
	 */
	private function __construct(array $classes)
	{
		foreach ($classes as $class) {
			$this->add_class($class);
		}
	}

	private function add_class(ItemSubTaxClass $class): void
	{
		$this->sub_classes[] = $class;
	}

	/**
	 * @param ItemSubTaxClass[] $classes
	 *
	 * @return $this
	 */
	public static function from_array(array $classes): self
	{
		return new self($classes);
	}

	public function for_product($product_id): ?ItemSubTaxClass
	{
		foreach ($this->sub_classes as $sub_tax_class) {
			if ($product_id == $sub_tax_class->product_id()) {
				return $sub_tax_class;
			}
		}

		return null;
	}

	public function as_pure_array(): array
	{
		$pure_array = [];
		foreach ($this->sub_classes as $class) {
			$pure_array[] = $class->as_array();
		}

		return $pure_array;
	}
}
