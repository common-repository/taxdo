<?php

namespace TaxDo\WooCommerce\Infra\Repository;

use http\Exception\RuntimeException;
use TaxDo\WooCommerce\Domain\Customer\AddressDTO;
use TaxDo\WooCommerce\Domain\Customer\Value\State;
use TaxDo\WooCommerce\Domain\Customer\Value\Address;

class CustomerRepository
{
	private const CUSTOMER_SHIPPING = 'shipping';
	private const CUSTOMER_BILLING = 'billing';
	private const SHOP_BASE = 'base';

	public function get_address_for_creating_invoice(): Address
	{
		return $this->get_address_for_calculating_tax();
	}

	public function get_address_for_calculating_tax(): Address
	{
		return Address::from_dto(
			$this->get_address_dto()
		);
	}

	public function get_address_dto(): AddressDTO
	{
		$customer = WC()->customer;
		$baseOn = get_option('woocommerce_tax_based_on');

		return match ($baseOn) {
			self::CUSTOMER_SHIPPING => new AddressDTO(
				$customer->get_shipping_first_name(),
				$customer->get_shipping_last_name(),
				$customer->get_shipping_country(),
				$customer->get_shipping_state(),
				$customer->get_shipping_city(),
				$customer->get_shipping_postcode(),
				$customer->get_shipping_address()
			),
			self::CUSTOMER_BILLING => new AddressDTO(
				$customer->get_billing_first_name(),
				$customer->get_billing_last_name(),
				$customer->get_billing_country(),
				$customer->get_billing_state(),
				$customer->get_billing_city(),
				$customer->get_billing_postcode(),
				$customer->get_billing_address()
			),
			self::SHOP_BASE => new AddressDTO(
				$customer->get_shipping_first_name(),
				$customer->get_shipping_last_name(),
				WC()->countries->get_base_country(),
				WC()->countries->get_base_state(),
				WC()->countries->get_base_city(),
				WC()->countries->get_base_postcode(),
				get_option('woocommerce_store_address', null),
			),
			default => throw new RuntimeException('invalid base address'),
		};
	}
}
