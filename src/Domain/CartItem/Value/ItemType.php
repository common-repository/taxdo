<?php

namespace TaxDo\WooCommerce\Domain\CartItem\Value;

use InvalidArgumentException;

final class ItemType
{
	private const LINE = 'line';
	private const SHIPPING = 'shipping';

	private const VALID_TYPES = [self::LINE, self::SHIPPING];

	private string $type;

	private function __construct(string $type)
	{
		if (!in_array($type, self::VALID_TYPES)) {
			throw new InvalidArgumentException('invalid item type.');
		}
		$this->type = $type;
	}

	public static function line(): ItemType
	{
		return new self(self::LINE);
	}

	public static function shipping(): ItemType
	{
		return new self(self::SHIPPING);
	}

	public function as_string(): string
	{
		return $this->type;
	}
}
