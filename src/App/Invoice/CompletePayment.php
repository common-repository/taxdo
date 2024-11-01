<?php


namespace TaxDo\WooCommerce\App\Invoice;


use WC_Order;
use DateTimeZone;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Infra\TaxDo\Client;
use TaxDo\WooCommerce\Domain\Invoice\Invoice;
use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;

final class CompletePayment
{

	public function __construct(
		private Client  $client,
		private Setting $setting
	)
	{
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function execute(int $order_id, string $transaction_id = ''): void
	{
		$order = wc_get_order($order_id);

		if ($order->get_meta('_taxdo_invoice_paid')) {
			return;
		}

		$invoice_id = $order->get_meta('_taxdo_invoice_id');
		if ($invoice_id) {
			$invoice_number = $order->get_meta('_taxdo_invoice_number');
			$invoice = new Invoice($invoice_id, $invoice_number, $order_id);
			$this->mark_payment_as_completed($invoice, $order, $transaction_id, $order->get_payment_method());
			$order->add_meta_data('_taxdo_invoice_paid', true);
			$order->add_order_note("TaxDo invoice marked as paid. ($invoice_number)");
			$order->save();
		}
	}

	/**
	 * @throws CommunicationFailed
	 */
	private function mark_payment_as_completed(Invoice $invoice, WC_Order $order, $transaction_id = ''): void
	{
		$transaction_id = !empty($transaction_id) && strlen($transaction_id) < 255
			? sprintf("/%s", $transaction_id) :
			'';
		$this->client->add_payment([
			'transaction_id' => sprintf("%s%s", $order->get_id(), $transaction_id),
			'payment_method' => $order->get_payment_method(),
			'payment_at' => $order->get_date_paid()->setTimezone(new DateTimeZone('UTC'))->format('Y-m-d\TH:i:s.u\Z'),
			'payer_name' => sprintf('%s %s', $order->get_billing_first_name(), $order->get_billing_last_name()),
			'payer_email' => $order->get_billing_email(),
			'invoice_id' => $invoice->id(),
			'send_email' => $this->setting->should_send_invoice_emails(),
		]);
	}
}
