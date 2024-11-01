<?php

namespace TaxDo\WooCommerce\App;

use WC_Integration;
use WC_Admin_Settings;

final class TaxdoIntegrationSettings extends WC_Integration
{
	private string $api_key;
	private string $taxdo_id;
	private string $sandbox;
	private string $active_taxdo;
	private string $store_name;
	private string $email_invoice_taxdo;
	private string $certificate_code;
	private string $certificate_taxdo_upload;
	private string $debug_mode;

	/**
	 * Init and hook in the integration.
	 */
	public function __construct()
	{
		$this->id = 'taxdo';
		$this->method_title = 'TaxDo';
		$this->method_description = __('Accurate sales tax calculation, handling of tax exemption certificates, sales tax registration, and timely filing, all taken care of for you.', 'taxdo');

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->api_key = $this->get_option('api_key');
		$this->taxdo_id = $this->get_option('taxdo_id');
		$this->sandbox = $this->get_option('sandbox');
		$this->active_taxdo = $this->get_option('active_taxdo');
		$this->store_name = $this->get_option('store_name');
		$this->debug_mode = $this->get_option('debug_mode');
		$this->email_invoice_taxdo = $this->get_option('email_invoice_taxdo');
		$this->certificate_code = $this->get_option('certificate_code');
		$this->certificate_taxdo_upload = $this->get_option('certificate_taxdo_upload');

		// Actions.
		if (!empty($_POST['woocommerce_taxdo_api_key']) && !empty($_POST['woocommerce_taxdo_taxdo_id'])) {
			add_action('woocommerce_update_options_integration_' . $this->id, [$this, 'process_admin_options']);

		} else {
			if (
				array_key_exists('section', $_GET) && 'integration-taxdo' === $_GET['section']
				&& array_key_exists('save', $_POST) && 'Save changes' === $_POST['save']
			) {
				WC_Admin_Settings::add_error(esc_html__('required TaxDo ID and API Key.', 'taxdo'));
			}
		}

	}

	/**
	 * Initialize integration settings form fields.
	 */
	public function init_form_fields()
	{
		$this->form_fields = [
			'taxdo_id' => [
				'title' => __('TaxDo ID', 'taxdo'),
				'description' => sprintf("<a href='https://app.taxdo.co' target='_blank'>%s</a>", __('Where to get your API URL?', 'taxdo')),
				'type' => 'text',
				'default' => array_key_exists('woocommerce_taxdo_taxdo_id', $_POST) ? sanitize_text_field($_POST['woocommerce_taxdo_taxdo_id']) : '',
				'custom_attributes' => [
					'required' => true,
				],
			],
			'api_key' => [
				'title' => __('API Key', 'taxdo'),
				'type' => 'password',
				'default' => array_key_exists('woocommerce_taxdo_api_key', $_POST) ? sanitize_text_field($_POST['woocommerce_taxdo_api_key']) : '',
				'description' => sprintf("<a href='https://app.taxdo.co' target='_blank'>%s</a>", __('Where to get your API URL?', 'taxdo')),
				'custom_attributes' => [
					'required' => true,
				],
			],
			'sandbox' => [
				'title' => __('Testing Mode', 'taxdo'),
				'type' => 'checkbox',
				'value' => array_key_exists('woocommerce_taxdo_sandbox', $_POST) ? sanitize_text_field($_POST['woocommerce_taxdo_sandbox']) : '',
				'label' => __('Switch to Sandbox for Testing', 'taxdo'),
				'description' => __('Caution: Changing environments may affect product tax classes. Please review and adjust them as needed.', 'taxdo'),
				'default' => $this->is_post_parameter_active('woocommerce_taxdo_sandbox'),
			],
			'active_taxdo' => [
				'title' => __('TaxDo calculates Sales & Use tax automatically', 'taxdo'),
				'type' => 'checkbox',
				'value' => array_key_exists('woocommerce_taxdo_taxdo_id', $_POST) ? sanitize_text_field($_POST['woocommerce_taxdo_taxdo_id']) : '',
				'label' => __('Enable TaxDo', 'taxdo'),
				'default' => $this->is_post_parameter_active('woocommerce_taxdo_active_taxdo'),
			],
			'store_name' => [
				'title' => __(' Store Name', 'taxdo'),
				'type' => 'text',
				'label_class' => 'address',
				'default' => get_option('blogname'),
			],
			'email_invoice_taxdo' => [
				'title' => __('Autosend receipt', 'taxdo'),
				'type' => 'checkbox',
				'label' => __('Enable', 'taxdo'),
				'description' => __('If checked, TaxDo will automatically send a receipt to your clients after every purchase including products and sales tax details. ', 'taxdo'),
				'default' => $this->is_post_parameter_active('woocommerce_taxdo_email_invoice_taxdo'),
			],
			'certificate_code' => [
				'title' => __('Sales tax certificate processing', 'taxdo'),
				'type' => 'checkbox',
				'label' => __('Enable', 'taxdo'),
				'default' => $this->is_post_parameter_active('woocommerce_taxdo_certificate_code'),
				'description' => __('If checked, your client can add the certification code, which received from you or TaxDo certification team to enjoy tax exemption.', 'taxdo')
			],
			'certificate_taxdo_upload' => [
				'title' => __('Upload Sales Tax Certificate', 'taxdo'),
				'type' => 'checkbox',
				'label' => __('Upload The Certificate', 'taxdo'),
				'default' => $this->is_post_parameter_active('woocommerce_taxdo_certificate_taxdo_upload'),
				'description' => __('If checked, your client would be able to upload a sales tax certificate and share it with you or TaxDo team', 'taxdo') . '<br>' . __('(if you purchase this service) for verification purpose to enjoy sales tax exemption.', 'taxdo')
			],
			'debug_mode' => [
				'title' => __('Debug Mode', 'taxdo'),
				'type' => 'checkbox',
				'label' => __('Enable Debug Logging', 'taxdo'),
				'default' => $this->is_post_parameter_active('woocommerce_taxdo_debug_mode'),
				'description' => __('All errors and app server communication details will be logged.', 'taxdo')
			],
			[
				'title' => __('Force universal pricing', 'taxdo'),
				'type' => 'hidden',
				'label' => __('Upload The Certificate', 'taxdo'),
				'description' => $this->get_force_description(),
			],
		];

	}

	private function is_post_parameter_active(string $key): string
	{
		if (!array_key_exists($key, $_POST)) {
			return '';
		}

		return '1' === sanitize_text_field($_POST[$key]) ? 'yes' : '';
	}

	public function get_force_description()
	{

		if (get_option('woocommerce_prices_include_tax') === 'yes') {
			$price_include_tax = sprintf('%s%s%s%s',
				__('Yes, I will enter prices inclusive of tax', 'taxdo'),
				'<br><span class="text_color">',
				__("TaxDo won't calculate sales taxes for this selection", 'taxdo')
				, '</span><br>'
			);
		} else {
			$price_include_tax = sprintf('%s%s', __('No, I will enter prices exclusive of tax', 'taxdo'), '<br>');
		}

		$price_include_tax_checkout = get_option('woocommerce_tax_display_cart') == 'incl' ?
			__('Including tax', 'taxdo') :
			__('Excluding tax', 'taxdo');

		$price_include_tax_shop = get_option('woocommerce_tax_display_shop') == 'incl' ?
			__('Including tax', 'taxdo') :
			__('Excluding tax', 'taxdo');

		$tax_based_on = get_option('woocommerce_tax_based_on');

		$description = sprintf('<label class="taxdo_description">%s</label><br>', __('Force universal pricing', 'taxdo'));

		$description .= sprintf('%s <a href=%s target="_blank">%s</a> %s',
			__('In order for this option be available you must set the following options on the', 'taxdo'),
			admin_url('admin.php?page=wc-settings&tab=tax'),
			__('Tax Options', 'taxdo'),
			__('page:', 'taxdo')
		);

		$description .= sprintf('<br><br>%s <b>%s</b><br>', __('1. Address to be used for tax calculation:', 'taxdo'), $tax_based_on);

		$description .= sprintf('%s <b>%s</b>', __('2. Prices entered with tax:', 'taxdo'), $price_include_tax);

		$description .= sprintf('%s <b>%s</b> <br>',
			__('3. Display prices in the shop:', 'taxdo'),
			$price_include_tax_shop
		);

		$description .= sprintf('%s <b>%s</b><br>',
			__('4. display prices during cart and checkout:', 'taxdo'),
			$price_include_tax_checkout
		);

		return $description;
	}
}
