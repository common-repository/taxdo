<?php


namespace TaxDo\WooCommerce\Infra\WordPress;

use Throwable;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Domain\DomainException;
use TaxDo\WooCommerce\Infra\ServiceRegistry\Container;

final class ErrorHandler
{
	public function __construct(
		private Setting $setting
	)
	{
	}

	public function wrap_callable(callable $function): callable
	{
		$debugMode = $this->setting->is_debug_mode();

		return function (...$args) use ($function, $debugMode) {
			try {
				return $function(...$args);
			} catch (DomainException $exception) {
				if ($debugMode) {
					$this->error_log($exception);
				}

				if (function_exists('wc_add_notice')) {
					if (!self::has_notice($exception->getMessage(), 'error')) {
						wc_add_notice($exception->getMessage(), 'error');
						$this->set_last_error($exception->getMessage());
					}
				}

				$this->send_json_error();
			} catch (Throwable $exception) {
				error_log(wp_json_encode($exception));
				$this->send_json_error();
				throw $exception;
			}
		};
	}

	private function error_log(DomainException $exception): void
	{
		$detail = $exception->detail();
		if (isset($detail['request']['headers']['Authorization'])) {
			$detail['request']['headers']['Authorization'] = 'Bearer ***********************';
		}
		error_log(wp_json_encode($detail));
	}

	public static function has_notice($message, $notice_type, int $duration = 2): bool
	{
		$last_error_at = WC()->session->get(sprintf('taxdo_error_%s', $message));
		if ($last_error_at and microtime(true) - $last_error_at < $duration) {
			return true;
		}

		return wc_has_notice($message, $notice_type);
	}

	public static function set_last_error($message): void
	{
		WC()->session->set(sprintf('taxdo_error_%s', $message), microtime(true));
	}

	private function send_json_error(): void
	{
		if (!is_ajax() or !isset($_REQUEST['action'])) {
			return;
		}

		$action = sanitize_text_field($_REQUEST['action']);

		if (in_array($action, Container::get_config('ajax_actions'))) {
			wp_send_json_error(wc_print_notices(true));
		}
	}
}
