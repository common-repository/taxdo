<?php


namespace TaxDo\WooCommerce\Domain\CartItem\Value;


class CartItemIds
{
	/**
	 * @var array<string>
	 */
	private array $ids = [];

	/**
	 * CartItemIds constructor.
	 *
	 * @param array<string> $ids
	 */
	private function __construct(array $ids)
	{
		foreach ($ids as $id) {
			$this->add_id($id);
		}
	}


	private function add_id(string $id): void
	{
		$this->ids[] = $id;
	}

	/**
	 * @param array<string, mixed> $cart_items
	 *
	 * @return $this
	 */
	public static function from_array_keys(array $cart_items): self
	{
		return new self(array_keys($cart_items));
	}

	public function contains(string $id): bool
	{
		return in_array($id, $this->ids, false);
	}
}
