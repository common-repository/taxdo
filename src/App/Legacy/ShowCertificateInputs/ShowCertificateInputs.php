<?php


namespace TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs;

use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;

final class ShowCertificateInputs
{

	public function __construct(
		private Setting                          $setting,
		private UploadCertificateInputOutputPort $upload_certificate_input_output_port,
		private CertificateCodeInputOutputPort   $certificate_code_input_output_port,
		private CertificateRepository            $certificate_repository
	)
	{
	}

	public function certificate_code(): void
	{
		if ($this->setting->show_certificate_code_input()) {
			$this->certificate_code_input_output_port->present($this->setting->show_upload_certificate_input());
		}
	}

	public function upload_certificate(): void
	{
		if ($this->setting->show_upload_certificate_input()) {
			$this->upload_certificate_input_output_port->present();
		}
	}

	public function show_applied_certificate_code(): void
	{
		$exemption = $this->certificate_repository->find();
		if ($exemption) {
			wc_get_template(
				'applied-certificate-code.php',
				['exemption' => $exemption->code(), 'state' => $exemption->address()->state()],
				TAX_DO_TEMPLATE_PATH,
				TAX_DO_TEMPLATE_PATH);
		}
	}
}
