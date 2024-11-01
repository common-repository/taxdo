<?php


namespace TaxDo\WooCommerce\Infra\WordPress\Hooks;


final class HookListeners
{
	/**
	 * @var HookListener[]
	 */
	private array $listeners = [];

	private function __construct(array $listeners)
	{
		foreach ($listeners as $listener) {
			$this->add_listener($listener);
		}
	}

	private function add_listener(HookListener $listener): void
	{
		$this->listeners[] = $listener;
	}

	public static function from_array(array $listeners): self
	{
		return new self($listeners);
	}

	/**
	 * @return HookListener[]
	 */
	public function as_array(): array
	{
		return $this->listeners;
	}
}
