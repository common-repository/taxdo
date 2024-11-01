<?php


namespace TaxDo\WooCommerce\Infra\Repository\Legacy\SubTaxClass;


class SubTaxClassRepositoryFactory
{
	private TaxDoLoadTaxClasses $sub_tax_class_repository_impl;

	public function __construct(TaxDoLoadTaxClasses $sub_tax_class_repository_impl)
	{
		$this->sub_tax_class_repository_impl = $sub_tax_class_repository_impl;
	}

	public function make(): SubTaxClassRepositoryUsingCache
	{
		return new SubTaxClassRepositoryUsingCache($this->sub_tax_class_repository_impl);
	}
}
