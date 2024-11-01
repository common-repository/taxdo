<?php


namespace TaxDo\WooCommerce\Infra\WordPress\Hooks;

final class HookData
{
	private HookType $type;
	private string $name;
	/**
	 * @var callable
	 */
	private $callback_func;
	private int $priority;
	private int $accepted_args;

	public function __construct(
		HookType $type,
		string   $name,
		callable $callback_func,
		int      $priority = 10,
		int      $accepted_args = 1
	)
	{
		$this->type = $type;
		$this->name = $name;
		$this->callback_func = $callback_func;
		$this->priority = $priority;
		$this->accepted_args = $accepted_args;
	}

	public function type(): HookType
	{
		return $this->type;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function get_callback_func(): callable
	{
		return $this->callback_func;
	}

	public function priority(): int
	{
		return $this->priority;
	}

	public function accepted_args(): int
	{
		return $this->accepted_args;
	}

	public function should_be_register(bool $plugin_is_active): bool
	{
		if ($plugin_is_active) {
			return true;
		}

		if ($this->type->should_always_register()) {
			return true;
		}

		return false;
	}

	public function is_action(): bool
	{
		return $this->type->is_action();
	}

	public function is_filter(): bool
	{
		return $this->type->is_filter();
	}
}
