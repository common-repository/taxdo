<?php

namespace TaxDo\WooCommerce\App;


use TaxDo\WooCommerce\Infra\Repository\CertificateRepository;

final class RemoveCertificate
{
	public function __construct(
		private CertificateRepository $certificate_repository,
	)
	{
	}

	public function execute(): void
	{
		$this->certificate_repository->delete();
	}
}
