<?php


namespace TaxDo\WooCommerce\Domain\TaxClass;


final class TaxClass
{
	public const NONE_ID = 0;
	public string $name;
	public int $us_id;
	public string $us_name;
	public int $ca_id;
	public string $ca_name;
	public int $id;

	public function __construct(int $id, string $name, int $us_id, string $us_name, int $ca_id, string $ca_name)
	{
		$this->id = $id;
		$this->name = $name;
		$this->us_id = $us_id;
		$this->us_name = $us_name;
		$this->ca_id = $ca_id;
		$this->ca_name = $ca_name;
	}

	public static function is_valid_id(int $id): bool
	{
		return $id != self::NONE_ID and $id > 0;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function is_equal_to(self $other): bool
	{
		return $this->id === $other->id();
	}

	public function id(): int
	{
		return $this->id;
	}

	public function idByCountry(string $countryCode)
	{
		if ('US' == $countryCode) {
			return $this->us_id;
		}
		if ('CA' == $countryCode) {
			return $this->us_id;
		}
	}

	public function nameByCountry(string $countryCode)
	{
		if ('US' == $countryCode) {
			return $this->us_name;
		}
		if ('CA' == $countryCode) {
			return $this->ca_name;
		}
	}

	public function as_array(): array
	{
		return [
			'id' => $this->id,
			'us_id' => $this->us_id,
			'ca_id' => $this->ca_id,
			'name' => $this->name,
			'us_name' => $this->us_name,
			'ca_name' => $this->ca_name,
		];
	}
}
