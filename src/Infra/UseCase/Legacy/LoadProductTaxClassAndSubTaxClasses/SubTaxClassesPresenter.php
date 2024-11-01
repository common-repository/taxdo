<?php


namespace TaxDo\WooCommerce\Infra\UseCase\Legacy\LoadProductTaxClassAndSubTaxClasses;


use TaxDo\WooCommerce\Domain\SubTaxClass\ItemSubTaxClass;
use TaxDo\WooCommerce\App\Legacy\Forms\SubTaxClassesInput;
use TaxDo\WooCommerce\Domain\SubTaxClass\Value\SubTaxClasses;
use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClassAndSubTaxClasses\SubTaxClassesOutputPort;


final class SubTaxClassesPresenter implements SubTaxClassesOutputPort
{
	public function present(SubTaxClasses $sub_tax_classes, ?ItemSubTaxClass $selected_sub_tax_class, int $product_id): void
	{
		$options = [-1 => 'Default'];
		foreach ($sub_tax_classes->as_array() as $subTaxClass) {
			$options[$subTaxClass->id()] = $subTaxClass->name();
		}

		$default = -1;
		if ($selected_sub_tax_class) {
			$default = $selected_sub_tax_class->customer_selected() ?? -1;
		}

		$key = SubTaxClassesInput::id_for_product($product_id);
		echo woocommerce_form_field($key, [
			'type' => 'select',
			'class' => array('airport_pickup form-row-wide sub_tax', 'taxdo_sub_tax_class_select'),
			'label' => __('sub tax class', 'taxdo'),
			'required' => true,
			'options' => $options,
			'default' => $default,
		]);
	}
}
