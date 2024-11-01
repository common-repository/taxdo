<?php


namespace TaxDo\WooCommerce\Domain;

use Exception;

class DomainException extends Exception
{
	private array $detail;

	public function __construct(string $message, array $detail = [])
	{
		parent::__construct($message, 0, null);
		$this->detail = $detail;
	}

	public function detail(): array
	{
		return $this->detail;
	}
}
