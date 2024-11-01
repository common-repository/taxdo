<?php

namespace TaxDo\WooCommerce\Infra\Listener;

use TaxDo\WooCommerce\App\AssignProductTaxClass;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\Hooks;
use TaxDo\WooCommerce\App\LoadProductTaxClassesList;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookType;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookData;
use TaxDo\WooCommerce\Infra\WordPress\Hooks\HookListener;
use TaxDo\WooCommerce\Views\TaxClassesSelectInputPresenter;

class ProductListener implements HookListener
{

	public function __construct(
		private AssignProductTaxClass          $assign_product_tax_class,
		private LoadProductTaxClassesList      $load_product_tax_class_list,
		private TaxClassesSelectInputPresenter $tax_classes_select_input_presenter,
	)
	{
	}

	public function hooks(): Hooks
	{
		return Hooks::from_array([
			new HookData(
				HookType::action_always(),
				'woocommerce_product_options_general_product_data',
				[$this, 'present_tax_classes_form_on_product_edit']
			),
			new HookData(
				HookType::action_always(),
				'woocommerce_process_product_meta',
				[$this, 'assign_product_tax_class']
			)
		]);
	}

	public function present_tax_classes_form_on_product_edit()
	{
		$product = wc_get_product();
		if (!$product) return;

		list($tax_classes, $product_tax_class) = $this->load_product_tax_class_list->execute($product->get_id());
		$this->tax_classes_select_input_presenter->present($tax_classes, $product_tax_class);
	}

	public function assign_product_tax_class(int $postId): void
	{
		$tax_class_id = isset($_POST[TaxClassesSelectInputPresenter::ID]) ? (int)sanitize_text_field($_POST[TaxClassesSelectInputPresenter::ID]) : 0;
		if (!$tax_class_id) {
			return;
		}

		$this->assign_product_tax_class->execute($postId, $tax_class_id);
	}
}
