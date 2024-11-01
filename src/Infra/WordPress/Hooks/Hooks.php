<?php


namespace TaxDo\WooCommerce\Infra\WordPress\Hooks;

final class Hooks
{
	/**
	 * @var HookData[]
	 */
	private array $hooks = [];

	private function __construct(array $hooks)
	{
		foreach ($hooks as $hook) {
			$this->add_hook($hook);
		}
	}

	private function add_hook(HookData $hook): void
	{
		$this->hooks[] = $hook;
	}

	/**
	 * @param HookData[] $hooks
	 *
	 * @return static
	 */
	public static function from_array(array $hooks): self
	{
		return new self($hooks);
	}

	/**
	 * @return HookData[]
	 */
	public function as_array(): array
	{
		return $this->hooks;
	}
}
