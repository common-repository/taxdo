<?php


namespace TaxDo\WooCommerce\Infra\UseCase\Legacy\ShowCertificateInputs;


use TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs\UploadCertificateInputOutputPort;

final class UploadCertificateInputPresenter implements UploadCertificateInputOutputPort
{
	public function present(): void
	{
		wc_get_template('form-certificate-upload.php', [], TAX_DO_TEMPLATE_PATH, TAX_DO_TEMPLATE_PATH);
	}
}
