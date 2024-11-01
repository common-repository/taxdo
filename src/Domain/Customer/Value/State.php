<?php


namespace TaxDo\WooCommerce\Domain\Customer\Value;


final class State
{
	const NO_STATE_NAME = 'no_state';
	private ?string $name;
	private ?string $code;

	public function __construct(string $name = null, string $code = null)
	{
		$this->name = $name;
		$this->code = $code;
	}

	public function name(): string
	{
		return $this->name ?? self::NO_STATE_NAME;
	}

	public function code(): string
	{
		return $this->code ?? $this->name();
	}

	public function is_selected(): bool
	{
		return $this->name != self::NO_STATE_NAME;
	}
}
