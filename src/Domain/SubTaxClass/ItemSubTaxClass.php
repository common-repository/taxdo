<?php


namespace TaxDo\WooCommerce\Domain\SubTaxClass;


final class ItemSubTaxClass
{
	public function __construct(
		private ?string $customer_selected_sub_tax_class_id,
		private int     $product_id,
		private string  $item_key,
		private ?int    $taxdo_calc_sub_tax_class_id = null
	)
	{
	}

	public static function from_array(array $data): self
	{
		return new self(
			$data['customer_selected_sub_tax_class_id'],
			$data['product_id'],
			$data['item_key'],
			$data['taxdo_calc_sub_tax_class_id']);
	}

	public function customer_selected(): ?string
	{
		return $this->customer_selected_sub_tax_class_id;
	}

	public function for_invoice(): ?int
	{
		return $this->taxdo_calc_sub_tax_class_id ?? $this->for_preprocess();
	}

	public function for_preprocess(): ?int
	{
		return $this->customer_selected_sub_tax_class_id == -1 ? null : (int)$this->customer_selected_sub_tax_class_id;
	}

	public function product_id(): int
	{
		return $this->product_id;
	}

	public function as_array(): array
	{
		return [
			'customer_selected_sub_tax_class_id' => $this->customer_selected_sub_tax_class_id,
			'product_id' => $this->product_id,
			'item_key' => $this->item_key,
			'taxdo_calc_sub_tax_class_id' => $this->taxdo_calc_sub_tax_class_id
		];
	}
}
