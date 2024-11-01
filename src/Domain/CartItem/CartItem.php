<?php


namespace TaxDo\WooCommerce\Domain\CartItem;


use TaxDo\WooCommerce\Domain\CartItem\Value\ItemType;

final class CartItem
{
	public function __construct(
		public string   $id,
		public string   $product_id,
		public string   $title,
		public ItemType $type,
		public ?int     $tax_class_id,
		public ?int     $sub_tax_class_id,
		public float    $price,
		public int      $quantity_of_products,
		public ?float   $tax_amount,
		public float    $discount,
		public string   $discount_type,
		public string   $description
	)
	{
	}

	public function as_array(): array
	{
		$data = [
			'id' => $this->id,
			'product_id' => $this->product_id,
			'product_name' => $this->title,
			'item_type' => $this->type->as_string(),
			'price' => $this->price,
			'count' => $this->quantity_of_products,
			'tax_amount' => $this->tax_amount === null ? 0 : $this->tax_amount,
			'discount' => $this->discount,
			'discount_type' => $this->discount_type,
			'description' => $this->description,
		];

		if (!is_null($this->tax_class_id)) {
			$data['tax_class_id'] = $this->tax_class_id;
		}

		if (!is_null($this->sub_tax_class_id)) {
			$data['sub_tax_class_id'] = $this->sub_tax_class_id;
		}

		return $data;
	}

	public function should_its_tax_be_calculated_by_tax_do(): bool
	{
		return $this->tax_amount == -1;
	}

	public function identify_as(string $id): bool
	{
		return $this->id === $id;
	}
}
