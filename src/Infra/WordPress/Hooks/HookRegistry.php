<?php


namespace TaxDo\WooCommerce\Infra\WordPress\Hooks;

use InvalidArgumentException;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Infra\WordPress\ErrorHandler;

final class HookRegistry
{
	/**
	 * @var ErrorHandler
	 */
	private ErrorHandler $errorHandler;
	private Setting $setting;

	public function __construct(ErrorHandler $error_handler, Setting $setting)
	{
		$this->errorHandler = $error_handler;
		$this->setting = $setting;
	}

	public function register(HookListeners $listeners): void
	{
		$plugin_is_active = $this->setting->should_calculate_tax_using_taxdo();
		foreach ($listeners->as_array() as $listener) {
			foreach ($listener->hooks()->as_array() as $hook) {
				if (!$hook->should_be_register($plugin_is_active)) {
					continue;
				}

				$callable_func = $this->errorHandler->wrap_callable($hook->get_callback_func());

				switch (true) {
					case $hook->is_action():
						add_action($hook->name(), $callable_func, $hook->priority(), $hook->accepted_args());
						break;
					case $hook->is_filter():
						add_filter($hook->name(), $callable_func, $hook->priority(), $hook->accepted_args());
						break;
					default:
						throw new InvalidArgumentException('invalid hook type.');
				}
			}
		}
	}
}
