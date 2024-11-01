<?php


namespace TaxDo\WooCommerce\App\Invoice;


use WC_Order;
use Exception;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Domain\Invoice\Invoice;
use TaxDo\WooCommerce\Infra\Repository\CartItemRepository;
use TaxDo\WooCommerce\Infra\Repository\CustomerRepository;
use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;


final class CreateInvoice
{

	public function __construct(
		private CertificateRepository $certificate_repository,
		private CartItemRepository    $cart_item_repository,
		private CustomerRepository    $customer_repository,
		private Client                $client
	)
	{
	}

	/**
	 * @throws Exception
	 */
	public function execute(WC_Order $order): void
	{
		$invoice = $this->issue_an_invoice($order->get_id());
		$invoice_number = $invoice->invoice_number();

		$order->update_meta_data('_taxdo_invoice_id', $invoice->id());
		$order->update_meta_data('_taxdo_invoice_number', $invoice_number);
		$order->add_order_note("TaxDo invoice created. ($invoice_number)");
		$order->save();
		$this->certificate_repository->delete();
	}

	/**
	 * @throws Exception
	 */
	private function issue_an_invoice(int $order_id): Invoice
	{
		$cart_items = $this->cart_item_repository->get_for_creating_invoice($order_id);
		$source = Setting::source();
		$certificate = $this->certificate_repository->find();
		$address = $this->customer_repository->get_address_for_creating_invoice();
		$client = ['name' => $address->customer_name()];


		$data = $this->client->create_invoice($certificate?->code(), $address->as_array(), $client,
			$cart_items->as_pure_array(), $source);


		return new Invoice($data['data']['id'], $data['data']['invoice_number'], $order_id);
	}
}
