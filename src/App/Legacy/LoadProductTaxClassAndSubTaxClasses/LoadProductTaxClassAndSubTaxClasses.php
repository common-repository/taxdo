<?php


namespace TaxDo\WooCommerce\App\Legacy\LoadProductTaxClassAndSubTaxClasses;


use TaxDo\WooCommerce\Infra\Repository\ProductRepository;
use TaxDo\WooCommerce\Infra\Repository\CustomerRepository;
use TaxDo\WooCommerce\Infra\Repository\CartItemRepository;
use TaxDo\WooCommerce\Domain\SubTaxClass\Legacy\Service\SubTaxClassRepository;
use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClass\OutputPort as ProductTaxClassOutputPort;

class LoadProductTaxClassAndSubTaxClasses
{
	public function __construct(
		private ProductRepository         $product_repository,
		private ProductTaxClassOutputPort $product_tax_class_output_port,
		private SubTaxClassRepository     $sub_tax_class_repository,
		private SubTaxClassesOutputPort   $sub_tax_classes_output_port,
		private CustomerRepository        $customer_repository,
		private CartItemRepository        $cart_item_repository
	)
	{
	}

	public function execute(int $product_id): void
	{
		$address = $this->customer_repository->get_address_for_calculating_tax();
		if (!$address->is_tax_class_able()) {
			return;
		}

		$tax_class = $this->product_repository->find_tax_class($product_id);
		if (is_null($tax_class)) {
			return;
		}
		$this->product_tax_class_output_port->present($tax_class->nameByCountry($address->country_code()));

		if (!$this->state_has_been_provided()) {
			return;
		}

		$state = $this->customer_repository->get_address_for_calculating_tax()->state();
		$sub_tax_classes = $this->sub_tax_class_repository->find_of_tax_class_in_state($tax_class->idByCountry
		($address->country_code()), $state);
		if ($sub_tax_classes->is_empty()) {
			return;
		}
		$item_sub_tax_classes = $this->cart_item_repository->get_items_sub_tax_classes();
		$selected_sub_tax_class = $item_sub_tax_classes->for_product($product_id);
		$this->sub_tax_classes_output_port->present($sub_tax_classes, $selected_sub_tax_class, $product_id);
	}

	private function state_has_been_provided(): bool
	{
		$address = $this->customer_repository->get_address_dto();

		return $address->has_state();
	}
}
