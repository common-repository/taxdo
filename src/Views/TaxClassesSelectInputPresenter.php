<?php


namespace TaxDo\WooCommerce\Views;

use TaxDo\WooCommerce\Domain\TaxClass\TaxClass;
use TaxDo\WooCommerce\Domain\TaxClass\TaxClasses;

final class TaxClassesSelectInputPresenter
{
	public const ID = '_taxDo_class_id';

	public function present(TaxClasses $tax_classes, ?string $active_tax_class): void
	{
		$tax_class_options = [];
		foreach ($tax_classes->as_array() as $tax_class) {
			$tax_class_options[(string)$tax_class->id()] = $tax_class->name();
		}
		$tax_class_options[TaxClass::NONE_ID] = 'None';

		woocommerce_wp_select(
			[
				'id' => self::ID,
				'label' => 'TaxDo Tax class',
				'style' => 'padding: 5px; border-radius: 5px;',
				'desc_tip' => true,
				'description' => __("To use TaxDo, you activate it.", "taxdo"),
				'value' => $active_tax_class ?? TaxClass::NONE_ID,
				'options' => $tax_class_options,
			]
		);
	}
}
