<?php


namespace TaxDo\WooCommerce\App\UploadCertificate;


use InvalidArgumentException;
use TaxDo\WooCommerce\Domain\Certificate\Service\UploadCertificateData;

final class UploadCertificateInput
{
	public const BUSINESS_NAME_ID = 'business_name';
	public const FIRST_NAME_ID = 'first_name';
	public const LAST_NAME_ID = 'last_name';
	public const FULL_NAME_ID = 'full_name';
	public const EMAIL_ID = 'email';
	public const PHONE_ID = 'phone';
	public const NOTE_ID = 'note';
	public const STATE_ID = 'state';
	public const COUNTRY_ID = 'country';
	public const CITY_ID = 'city';
	public const POST_CODE_ID = 'postcode';
	public const ADDRESS_1_ID = 'address_1';
	public const ADDRESS_2_ID = 'address_2';
	public const REASON_ID = 'reason';
	public const FILE_ID = 'attachment';

	public static function get_certificate_data(array $postData, array $fileData): UploadCertificateData
	{
		if (!self::has_sent($postData, $fileData)) {
			throw new InvalidArgumentException('invalid data');
		}

		$validated_file = self::validate_uploaded_file($fileData['attachment']);
		if (!$validated_file['success']) {
//			throw new InvalidArgumentException($validated_file['error']);
		}

		$address = sanitize_text_field($postData[self::ADDRESS_1_ID]);
		if (!empty($postData[self::ADDRESS_2_ID])) {
			$address .= ' ' . sanitize_text_field($postData[self::ADDRESS_2_ID]);
		}

		$country_code = sanitize_text_field($postData[self::COUNTRY_ID] ?? "US");
		return new UploadCertificateData(
			sanitize_text_field($postData[self::BUSINESS_NAME_ID]),
			isset($postData[self::FULL_NAME_ID]) ? sanitize_text_field($postData[self::FULL_NAME_ID]) :
				sanitize_text_field($postData[self::FIRST_NAME_ID]) .
				' ' . sanitize_text_field($postData[self::LAST_NAME_ID]),
			sanitize_text_field($postData[self::EMAIL_ID]),
			sanitize_text_field($postData[self::PHONE_ID]),
			sanitize_text_field($postData[self::NOTE_ID]),
			sanitize_text_field($postData[self::REASON_ID]),
			$country_code,
			WC()->countries->get_states($country_code)[sanitize_text_field($postData[self::STATE_ID])],
			sanitize_text_field($postData[self::CITY_ID]),
			sanitize_text_field($postData[self::POST_CODE_ID]),
			$address,
			$fileData[self::FILE_ID]
		);
	}

	public static function has_sent(array $postData, array $fileData): bool
	{
		return array_key_exists(self::BUSINESS_NAME_ID, $postData)
			&& ((array_key_exists(self::FIRST_NAME_ID, $postData)
					&& array_key_exists(self::LAST_NAME_ID, $postData)) || array_key_exists(self::FULL_NAME_ID, $postData))
			&& array_key_exists(self::NOTE_ID, $postData)
			&& array_key_exists(self::EMAIL_ID, $postData)
			&& array_key_exists(self::STATE_ID, $postData)
			&& array_key_exists(self::CITY_ID, $postData)
			&& array_key_exists(self::POST_CODE_ID, $postData)
			&& array_key_exists(self::REASON_ID, $postData)
			&& array_key_exists(self::ADDRESS_1_ID, $postData)
			&& array_key_exists(self::ADDRESS_2_ID, $postData)
			&& array_key_exists(self::FILE_ID, $fileData);
	}

	private static function validate_uploaded_file($file): array
	{
		$max_upload_size = 10;
		if ($file['size'] > $max_upload_size * 1024 * 1024) {
			return [
				'success' => false,
				'error' => 'File size exceeds maximum limit of %s MB', $max_upload_size / (1024 * 1024)
			];
		}

		$file_type = $file['type'];
		$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
		$allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'pdf');
		$result = wp_check_filetype_and_ext($file['tmp_name'], $file_type, $ext);
		if (!$result['ext'] || !in_array($result['ext'], $allowed_types)) {
			return [
				'success' => false,
				'error' => 'Invalid file type. Allowed types: ' . implode(', ', $allowed_types)
			];
		}

		return ['success' => true];
	}
}
