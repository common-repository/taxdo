<?php

namespace TaxDo\WooCommerce;

use ReflectionException;
use TaxDo\WooCommerce\Infra\ServiceRegistry\Container;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookRegistry;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListeners;


class TaxdoBootstrap
{
	/**
	 * @throws ReflectionException
	 */
	public function boot(): void
	{
		$this->load_config();
		$this->register_listeners();
	}

	private function load_config(): void
	{
		$configData = require sprintf('%s%s', TAX_DO_PATH, 'config/config.php');
		Container::load_config($configData);
	}

	/**
	 * @throws ReflectionException
	 */
	private function register_listeners(): void
	{
		$listeners = Container::get_config('hook_listeners');

		$hookListeners = [];
		foreach ($listeners as $listenerName) {
			$hookListeners[] = Container::get_service($listenerName);
		}

		Container::get_service(HookRegistry::class)->register(HookListeners::from_array($hookListeners));
	}
}
