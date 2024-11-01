<?php


namespace TaxDo\WooCommerce\App\PreProcess;


final class CalculatedCartItem
{
	public function __construct(
		private string $item_key,
		private int    $product_id,
		private float  $tax_amount,
		private ?float $tax_rate,
		private ?int   $sub_tax_class_id,
		private bool   $has_calculated_by_tax_do
	)
	{
	}

	public function item_key(): string
	{
		return $this->item_key;
	}

	public function product_id(): string
	{
		return $this->product_id;
	}

	public function tax_amount(): float
	{
		return $this->tax_amount;
	}

	public function tax_rate(): ?float
	{
		return $this->tax_rate;
	}

	public function sub_tax_class_id(): ?int
	{
		return $this->sub_tax_class_id;
	}

	public function should_be_applied(): bool
	{
		return $this->has_calculated_by_tax_do && $this->tax_rate >= 0;
	}
}
