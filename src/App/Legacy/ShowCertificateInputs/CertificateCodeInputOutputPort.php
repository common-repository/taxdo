<?php


namespace TaxDo\WooCommerce\App\Legacy\ShowCertificateInputs;


interface CertificateCodeInputOutputPort
{
	public function present(bool $showUpload): void;
}
