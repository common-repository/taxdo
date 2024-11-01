<?php


namespace TaxDo\WooCommerce\App\UploadCertificate;


final class UploadCertificateRequest
{
	public function __construct(
		public string $business_name,
		public string $name,
		public string $email,
		public string $phone_number,
		public string $note,
		public string $reason,
		public string $country_code,
		public string $state,
		public string $city,
		public string $zip_code,
		public string $address,
		public array  $file
	)
	{
	}

	public function as_array(): array
	{
		return [
			'business_name' => $this->business_name,
			'name' => $this->name,
			'email' => $this->email,
			'phone_number' => $this->phone_number,
			'note' => $this->note,
			'reason' => $this->reason,
			'country_code' => $this->country_code,
			'country_id' => 226,
			'state' => $this->state,
			'city' => $this->city,
			'zip_code' => $this->zip_code,
			'address' => $this->address,
		];
	}

	public function file_path(): string
	{
		return $this->file['tmp_name'];
	}
}
