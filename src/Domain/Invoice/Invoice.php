<?php


namespace TaxDo\WooCommerce\Domain\Invoice;


final class Invoice
{
	private string $id;
	private string $invoice_number;
	private int $order_id;

	public function __construct(string $id, string $invoice_number, int $order_id)
	{
		$this->id = $id;
		$this->invoice_number = $invoice_number;
		$this->order_id = $order_id;
	}

	public function id(): string
	{
		return $this->id;
	}

	public function invoice_number(): string
	{
		return $this->invoice_number;
	}

	public function order_id(): int
	{
		return $this->order_id;
	}
}
