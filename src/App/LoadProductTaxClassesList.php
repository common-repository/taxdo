<?php

namespace TaxDo\WooCommerce\App;

use TaxDo\WooCommerce\Infra\TaxDo\CommunicationFailed;
use TaxDo\WooCommerce\Infra\Repository\ProductRepository;
use TaxDo\WooCommerce\Infra\Repository\TaxClassRepository;

class LoadProductTaxClassesList
{
	public function __construct(
		private ProductRepository  $product_repository,
		private TaxClassRepository $taxClassRepository
	)
	{
	}

	/**
	 * @throws CommunicationFailed
	 */
	public function execute(int $product_id): array
	{
		$tax_classes = $this->taxClassRepository->get_active_tax_classes();
		$product_tax_class = $this->product_repository->find_tax_class_id($product_id);


		return [$tax_classes, $product_tax_class];
	}
}
