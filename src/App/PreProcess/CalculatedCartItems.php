<?php


namespace TaxDo\WooCommerce\App\PreProcess;


final class CalculatedCartItems
{
	/**
	 * @var CalculatedCartItem[]
	 */
	private array $items = [];

	/**
	 * CalculatedCartItems constructor.
	 *
	 * @param CalculatedCartItem[] $items
	 */
	private function __construct(array $items)
	{
		foreach ($items as $item) {
			$this->add_calculated_cart_item($item);
		}
	}

	private function add_calculated_cart_item(CalculatedCartItem $calculated_cart_item): void
	{
		$this->items[] = $calculated_cart_item;
	}

	/**
	 * @param CalculatedCartItem[] $items
	 *
	 * @return static
	 */
	public static function from_array(array $items): self
	{
		return new self($items);
	}

	public function get_by_item_id($id): ?CalculatedCartItem
	{
		foreach ($this->items as $item) {
			if ($item->item_key() == $id) {
				return $item;
			}
		}

		return null;
	}

	public function get_by_product_id($id): ?CalculatedCartItem
	{
		foreach ($this->items as $item) {
			if ($item->product_id() == $id) {
				return $item;
			}
		}

		return null;
	}

	/**
	 * @return CalculatedCartItem[]
	 */
	public function as_array(): array
	{
		return $this->items;
	}
}
