<?php


namespace TaxDo\WooCommerce\Infra\UseCase\Legacy\ShowCertificateInputs;


use TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs\CertificateCodeInputOutputPort;

final class CertificateCodeInputPresenter implements CertificateCodeInputOutputPort
{
	public function present(bool $showUpload): void
	{
		wc_get_template('form-certificate-code.php', ['showUpload' => $showUpload], TAX_DO_TEMPLATE_PATH, TAX_DO_TEMPLATE_PATH);
	}
}
