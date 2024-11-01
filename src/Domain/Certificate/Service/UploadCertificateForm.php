<?php

namespace TaxDo\WooCommerce\Domain\Certificate\Service;

use JetBrains\PhpStorm\ArrayShape;

class UploadCertificateForm
{

	public static function get_upload_cert_business_info_fields()
	{
		$fields = array(
			'business_name' => array(
				'label' => 'Business name',
				'required' => true,
				'type' => 'text',
				'class' => array('form-row-wide'),
				'autocomplete' => 'organization',
				'custom_attributes' => ['required' => true],
				'priority' => 10,
			),
			'full_name' => array(
				'label' => 'Full name',
				'required' => true,
				'type' => 'text',
				'class' => array('form-row-wide'),
				'autocomplete' => 'full-name',
				'custom_attributes' => ['required' => true],
				'priority' => 20,
			),
			'email' => array(
				'label' => 'Email address',
				'required' => true,
				'type' => 'email',
				'class' => array('form-row-wide'),
				'autocomplete' => 'email username',
				'custom_attributes' => ['required' => true],
				'priority' => 30,
			),
			'phone' => array(
				'label' => 'Phone number',
				'required' => false,
				'type' => 'tel',
				'class' => array('form-row-wide'),
				'autocomplete' => 'phone',
				'priority' => 40,
			),
			'note' => array(
				'label' => 'Note',
				'required' => false,
				'type' => 'textarea',
				'class' => array('form-row-wide'),
				'priority' => 50,
			)
		);

		return apply_filters('taxdo_get_upload_cert_business_info_fields', $fields);
	}

	public static function get_upload_cert_cert_info_fields()
	{
		$fields = array(
			'reason' => array(
				'label' => 'Reason',
				'required' => false,
				'type' => 'text',
				'placeholder' => 'The reason your are asking for this exemption',
				'class' => array('form-row-wide'),
				'autocomplete' => 'reason',
				'priority' => 10,
			),
			'country' => array(
				'label' => 'Country',
				'required' => true,
				'type' => 'country',
				'class' => array('form-row-wide', 'address-field'),
				'autocomplete' => 'country',
				'custom_attributes' => array('disabled' => true, 'readonly' => true),
				'default' => 'US',
				'priority' => 20,
			),
			'address_1' => array(
				'label' => 'Address',
				'placeholder' => 'House number and street name',
				'required' => true,
				'type' => 'text',
				'class' => array('form-row-wide', 'address-field'),
				'autocomplete' => 'address-line1',
				'custom_attributes' => ['required' => true],
				'priority' => 30,
			),
			'address_2' => array(
				'placeholder' => 'Apartment, suite, unit, etc. (optional)',
				'required' => false,
				'type' => 'text',
				'class' => array('form-row-wide', 'address-field'),
				'autocomplete' => 'address-line2',
				'priority' => 40,
			),
			'city' => array(
				'label' => 'City',
				'required' => true,
				'type' => 'text',
				'class' => array('form-row-wide', 'address-field'),
				'autocomplete' => 'city',
				'custom_attributes' => ['required' => true],
				'priority' => 50,
			),
			'state' => array(
				'label' => 'State',
				'required' => true,
				'type' => 'state',
				'class' => array('form-row-wide', 'address-field'),
				'autocomplete' => 'state',
				'validate' => array('state'),
				'country_field' => "country",
				'country' => 'US',
				'custom_attributes' => ['required' => true],
				'priority' => 60,
			),
			'postcode' => array(
				'label' => 'ZIP Code',
				'required' => true,
				'type' => 'text',
				'class' => array('form-row-wide', 'address-field'),
				'autocomplete' => 'postcode',
				'custom_attributes' => ['required' => true],
				'priority' => 70,
			),
			'attachment' => array(
				'label' => 'Attachment',
				'required' => true,
				'type' => 'text',
				'class' => array('form-row-wide'),
				'return' => true,
				'custom_attributes' => ['required' => true],
				'priority' => 80,
			)
		);

		return apply_filters('taxdo_get_upload_cert_business_info_fields', $fields);
	}

	public static function render_form_filed(string $key, array $field)
	{
		$field = woocommerce_form_field($key, $field, 'country' === $key ? 'US' : self::find_in_post_parameters($key
		));

		if ($field) {
			$field = str_replace('type="text"', 'type="file"', $field);
			echo wp_kses($field, self::allowed_html_tags());
		}
	}

	public static function find_in_post_parameters(string $key)
	{
		return array_key_exists($key, $_POST) ? sanitize_text_field($_POST[$key]) : null;
	}

	public static function allowed_html_tags(): array
	{
		return [
			'p' => [
				'class' => true,
				'id' => true,
				'data-priority' => true,
			],
			'label' => [
				'for' => true,
				'class' => true,
			],
			'abbr' => [
				'class' => true,
				'title' => true,
			],
			'input' => [
				'type' => true,
				'class' => true,
				'name' => true,
				'id' => true,
				'placeholder' => true,
				'value' => true,
				'required' => true
			],
			'span' => [
				'class' => true,
			],
		];
	}
}
