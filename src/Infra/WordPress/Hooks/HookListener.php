<?php


namespace TaxDo\WooCommerce\Infra\WordPress\Hooks;

interface HookListener
{
	public function hooks(): Hooks;
}
