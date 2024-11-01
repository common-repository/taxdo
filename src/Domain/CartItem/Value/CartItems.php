<?php


namespace TaxDo\WooCommerce\Domain\CartItem\Value;


use TaxDo\WooCommerce\Domain\CartItem\CartItem;

final class CartItems
{
	/**
	 * @var CartItem[]
	 */
	private array $items = [];

	/**
	 * CartItems constructor.
	 *
	 * @param CartItem[] $items
	 */
	private function __construct(array $items)
	{
		foreach ($items as $item) {
			$this->add_cart_item($item);
		}
	}

	private function add_cart_item(CartItem $cart_item): void
	{
		$this->items[] = $cart_item;
	}

	/**
	 * @param CartItem[] $items
	 *
	 * @return static
	 */
	public static function from_array(array $items): self
	{
		return new self($items);
	}

	/**
	 * @return CartItem[]
	 */
	public function as_pure_array(): array
	{
		return array_map(
			static function (CartItem $item): array {
				return $item->as_array();
			},
			$this->items
		);
	}

	public function get_item(string $id): CartItem
	{
		foreach ($this->items as $item) {
			if ($item->identify_as($id)) {
				return $item;
			}
		}
		// TODO:: Throw an exception.
	}

	public function merge(self $cart_items): self
	{
		foreach ($cart_items->items as $item) {
			$this->add_cart_item($item);
		}

		return $this;
	}
}
