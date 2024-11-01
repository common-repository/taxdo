<?php


namespace TaxDo\WooCommerce\App\PreProcess;


use Exception;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Domain\Customer\Value\Address;
use TaxDo\WooCommerce\Domain\Certificate\Certificate;
use TaxDo\WooCommerce\Domain\CartItem\Value\CartItems;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;
use TaxDo\WooCommerce\Infra\Repository\CartItemRepository;
use TaxDo\WooCommerce\Infra\Repository\CustomerRepository;
use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;


final class PreProcess
{
	public const PRE_PROCESS_CACHE_PREFIX_KEY = 'taxdo_pre_process';

	public function __construct(
		private CertificateRepository $certificate_repository,
		private CartItemRepository    $cart_item_repository,
		private CustomerRepository    $customer_repository,
		private Client                $client,
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function execute(): ?CalculatedCartItems
	{
		if (!$this->has_required_data_filled()) {
			return null;
		}

		$cartItems = $this->cart_item_repository->get_for_calculating_taxes();
		$address = $this->customer_repository->get_address_for_calculating_tax();
		$certificate = $this->certificate_repository->find();

		$calculated_cart_items = $this->calculate($address, $cartItems, $certificate);
		$this->cart_item_repository->update_sub_tax_class_with_preprocess($calculated_cart_items);

		return $calculated_cart_items;
	}

	public function has_required_data_filled(): bool
	{
		if (!(is_cart() or is_checkout() or is_checkout_pay_page())) {
			return false;
		}

		$address = $this->customer_repository->get_address_dto();

		if (!$address->has_country()) {
			return false;
		}

		if ("US" === $address->get_country_code() and !$address->has_zip_code()) {
			return false;
		}

		if ($address->should_have_state() and !$address->has_state()) {
			return false;
		}

		return true;
	}

	/**
	 * @throws CommunicationFailed
	 */
	private function calculate(Address $address, CartItems $cart_items, ?Certificate $certificate):
	CalculatedCartItems
	{
		$client = ['name' => get_bloginfo('name')];
		$source = Setting::source();

		$hash = md5(
			wp_json_encode($address->as_array())
			. wp_json_encode($client)
			. wp_json_encode($cart_items->as_pure_array())
			. $source
			. $certificate?->code()
		);

		if (!$result = get_transient(sprintf('%s%s', self::PRE_PROCESS_CACHE_PREFIX_KEY, $hash))) {
			$result = $this->client->pre_process($certificate?->code(), $address->as_array(), $client,
				$cart_items->as_pure_array(), $source);

			set_transient(sprintf('%s%s', self::PRE_PROCESS_CACHE_PREFIX_KEY, $hash), $result, MINUTE_IN_SECONDS * 5);
		}

		$calculated_cart_items = [];
		foreach ($result['data']['items'] as $item) {
			$cartItem = $cart_items->get_item($item['description']);
			$calculated_cart_items[] = new CalculatedCartItem(
				$item['description'],
				$item['product_id'],
				$item['tax_amount'],
				$item['effective_tax_rate'],
				$item['sub_tax_class_id'],
				$cartItem->should_its_tax_be_calculated_by_tax_do());
		}

		return CalculatedCartItems::from_array($calculated_cart_items);
	}
}
