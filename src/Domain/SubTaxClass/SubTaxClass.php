<?php


namespace TaxDo\WooCommerce\Domain\SubTaxClass;


final class SubTaxClass
{
	private int $id;
	private string $name;
	private int $tax_class_id;

	public function __construct(int $id, string $name, int $tax_class_id)
	{
		$this->id = $id;
		$this->name = $name;
		$this->tax_class_id = $tax_class_id;
	}

	public function id(): int
	{
		return $this->id;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function tax_class_id(): int
	{
		return $this->tax_class_id;
	}
}
