<?php


namespace TaxDo\WooCommerce\Domain\Customer\Value;


use TaxDo\WooCommerce\Domain\Customer\AddressDTO;

final class Address
{
	public function __construct(
		private string $customer_name,
		private string $country_code,
		private string $state,
		private string $city,
		private string $zip_code,
		private string $address
	)
	{}

	public static function from_dto(AddressDTO $DTO): self
	{
		return new self(
			sprintf('%s %s', $DTO->get_first_name(), $DTO->get_last_name()),
			$DTO->get_country_code(),
			$DTO->get_state(),
			$DTO->get_city(),
			$DTO->get_zip_code(),
			$DTO->get_address(),
		);
	}

	public function is_tax_class_able(): bool
	{
		return $this->is_country_usa() || $this->is_country_ca();
	}

	public function is_country_usa(): bool
	{
		return 'US' === $this->country_code;
	}

	private function is_country_ca(): bool
	{
		return 'CA' === $this->country_code;
	}

	public function customer_name(): string
	{
		return $this->customer_name;
	}

	public function country_code(): string
	{
		return $this->country_code;
	}


	public function state(): string
	{
		return $this->state;
	}

	public function city(): string
	{
		return $this->city;
	}

	public function zipCode(): string
	{
		return $this->zip_code;
	}

	public function address(): string
	{
		return $this->address;
	}

	public function as_array(): array
	{
		$data = [
			'country_code' => $this->country_code,
			'state' => $this->state,
			'city' => $this->city,
			'zip_code' => $this->zip_code,
			'address' => $this->address,
		];

		$filtered = [];
		foreach ($data as $key => $value) {
			if (!empty($value) and strlen(trim($value))) {
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}
}
