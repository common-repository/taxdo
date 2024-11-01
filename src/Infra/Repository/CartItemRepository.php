<?php

namespace TaxDo\WooCommerce\Infra\Repository;

use Exception;
use TaxDo\WooCommerce\Domain\CartItem\CartItem;
use TaxDo\WooCommerce\Domain\Customer\Value\Address;
use TaxDo\WooCommerce\Domain\CartItem\Value\ItemType;
use TaxDo\WooCommerce\Domain\CartItem\Value\CartItems;
use TaxDo\WooCommerce\Domain\SubTaxClass\ItemSubTaxClass;
use TaxDo\WooCommerce\App\PreProcess\CalculatedCartItems;
use TaxDo\WooCommerce\Domain\SubTaxClass\Value\ItemSubTaxClasses;

class CartItemRepository
{
	public function __construct(
		private ProductRepository  $product_repository,
		private CustomerRepository $customer_repository
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function get_for_calculating_taxes(): CartItems
	{
		$cart_items = [];
		$item_sub_tax_classes = $this->get_items_sub_tax_classes();
		foreach (WC()->cart->get_cart() as $id => $data) {
			$tax_class_id = $this->product_repository->find_tax_class_id($data['product_id']);
			$tax_amount = (!$this->tax_address()->is_tax_class_able() || $tax_class_id > 0) ? -1 : $data['line_tax'] ?? 0;
			$sub_tax_class = $item_sub_tax_classes->for_product($data['product_id']);
			$cart_items[] = $this->generate_cart_items($id, $data, $tax_amount, $sub_tax_class?->for_preprocess(), $id);
		}

		return CartItems::from_array($cart_items);
	}

	public function get_items_sub_tax_classes(): ItemSubTaxClasses
	{
		$items_sub_array = WC()->session->get('taxdo_product_sub_tax_classes', []);
		$item_sub = [];
		foreach ($items_sub_array as $sub) {
			$item_sub[] = ItemSubTaxClass::from_array($sub);
		}

		return ItemSubTaxClasses::from_array($item_sub);
	}

	private function tax_address(): Address
	{
		return $this->customer_repository->get_address_for_calculating_tax();
	}

	/**
	 * @param $id
	 * @param $data
	 * @param $tax_amount
	 * @param int|null $sub_tax_class_id
	 *
	 * @return CartItem
	 * @throws Exception
	 */
	private function generate_cart_items($id, $data, $tax_amount, ?int $sub_tax_class_id, string $description = ''):
	CartItem
	{
		$tax_class = $this->product_repository->find_tax_class($data['product_id']);
		$discount = isset($data['line_total']) ? (($data['data']->get_price() * (int)$data['quantity']) - (float)$data['line_total']) : 0;
		$product = wc_get_product($data['data']->get_id());

		return new CartItem(
			$id,
			$data['product_id'],
			$product->get_title(),
			ItemType::line(),
			$tax_class?->id(),
			$sub_tax_class_id == 0 ? null : $sub_tax_class_id,
			$data['data']->get_price(),
			$data['quantity'],
			$tax_amount,
			max($discount, 0),
			'AMOUNT',
			$description
		);
	}

	/**
	 * @throws Exception
	 */
	public function get_for_creating_invoice(int $order_id): CartItems
	{

		$cart_items = [];
		$item_sub_tax_classes = $this->get_items_sub_tax_classes();

		foreach (WC()->cart->get_cart() as $id => $data) {
			$tax_class_id = $this->product_repository->find_tax_class_id($data['product_id']);
			$tax_amount = (!$this->tax_address()->is_tax_class_able() || $tax_class_id > 0) ? -1 : $data['line_tax'] ?? 0;
			$sub_tax_class = $item_sub_tax_classes->for_product($data['product_id']);
			$cart_items[] = $this->generate_cart_items($id, $data, $tax_amount, $sub_tax_class?->for_preprocess());
		}

		$extra_items = $this->get_extra_items($order_id);

		return CartItems::from_array($cart_items)->merge($extra_items);
	}

	private function get_extra_items(int $order_id): CartItems
	{
		$order = wc_get_order($order_id);

		$cart_items = [];
		if ($order->get_shipping_total() > 0) {
			$cart_items[] = new CartItem(
				sprintf("%s-shipping", $order_id),
				0,
				"shipping",
				ItemType::shipping(),
				null,
				null,
				$order->get_shipping_total(),
				1,
				$order->get_shipping_tax(),
				0,
				'AMOUNT',
				''
			);
		}

		return CartItems::from_array($cart_items);
	}

	public function update_sub_tax_class_with_preprocess(CalculatedCartItems $calculated_cart_items): void
	{
		$before_preprocess = $this->get_items_sub_tax_classes();
		$after_preprocess = [];
		foreach ($calculated_cart_items->as_array() as $calculated_cart_item) {
			$item_sub_tax_class = $before_preprocess->for_product($calculated_cart_item->product_id());
			if ($item_sub_tax_class) {
				$after_preprocess[] = new ItemSubTaxClass(
					$item_sub_tax_class->for_preprocess(),
					$calculated_cart_item->product_id(),
					$calculated_cart_item->item_key(),
					$calculated_cart_item->sub_tax_class_id() ?? null);
			}
		}

		$this->persist_item_sub_tax_class_ids(ItemSubTaxClasses::from_array($after_preprocess));
	}

	public function persist_item_sub_tax_class_ids(ItemSubTaxClasses $product_sub_tax_classes): void
	{
		WC()->session->set('taxdo_product_sub_tax_classes', $product_sub_tax_classes->as_pure_array());
	}
}
