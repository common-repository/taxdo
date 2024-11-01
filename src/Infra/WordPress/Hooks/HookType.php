<?php

namespace TaxDo\WooCommerce\Infra\WordPress\Hooks;

use InvalidArgumentException;

final class HookType
{
	private const ACTION = 'ACTION';
	private const ACTION_CONDITIONAL = 'ACTION_CONDITIONAL';
	private const FILTER = 'FILTER';
	private const FILTER_CONDITIONAL = 'FILTER_CONDITIONAL';
	private const VALID_TYPES = [self::ACTION, self::FILTER, self::ACTION_CONDITIONAL, self::FILTER_CONDITIONAL];

	private string $type;

	private function __construct(string $type)
	{
		if (!in_array($type, self::VALID_TYPES)) {
			throw new InvalidArgumentException('invalid hook type.');
		}
		$this->type = $type;
	}

	public static function action_conditional(): self
	{
		return new self(self::ACTION_CONDITIONAL);
	}

	public static function action_always(): self
	{
		return new self(self::ACTION);
	}

	public static function filter_conditional(): self
	{
		return new self(self::FILTER_CONDITIONAL);
	}

	public static function filter_always(): self
	{
		return new self(self::FILTER);
	}

	public function is_action(): bool
	{
		return self::ACTION === $this->type || self::ACTION_CONDITIONAL === $this->type;
	}

	public function is_filter(): bool
	{
		return self::FILTER === $this->type || self::FILTER_CONDITIONAL === $this->type;
	}

	public function should_always_register(): bool
	{
		return self::FILTER === $this->type || self::ACTION === $this->type;
	}
}
