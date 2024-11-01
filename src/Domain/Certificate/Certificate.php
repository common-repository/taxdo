<?php


namespace TaxDo\WooCommerce\Domain\Certificate;


use TaxDo\WooCommerce\Domain\Customer\Value\Address;

final class Certificate
{
	private string $code;
	private Address $address;

	public function __construct(string $code, Address $address)
	{
		$this->code = $code;
		$this->address = $address;
	}

	public function code(): string
	{
		return $this->code;
	}

	public function address(): Address
	{
		return $this->address;
	}
}
