<?php


namespace TaxDo\WooCommerce\Domain\Customer;


class AddressDTO
{
	public function __construct(
		private string  $first_name,
		private string  $last_name,
		private string $country_code,
		private string $state,
		private string $city,
		private string $zip_code,
		private string $address
	)
	{}

	public function get_first_name(): string
	{
		return $this->first_name;
	}

	public function get_last_name(): string
	{
		return $this->last_name;
	}

	public function get_country_code(): string
	{
		return $this->country_code;
	}

	public function has_country(): bool
	{
		return !empty($this->country_code);
	}

	public function get_state(): string
	{
		return $this->state;
	}

	public function has_state(): bool
	{
		return !empty($this->state);
	}

	public function get_city(): string
	{
		return $this->city;
	}

	public function get_zip_code(): string
	{
		return $this->zip_code;
	}

	public function has_zip_code(): bool
	{
		return !empty($this->zip_code);
	}

	public function get_address(): string
	{
		return $this->address;
	}

	public function should_have_state(): bool
	{
		return in_array($this->get_country_code(), ["US", "CA"]);
	}
}
