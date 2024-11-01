<?php

namespace TaxDo\WooCommerce\Infra\UseCase\Legacy\LoadProductTaxClass;

use TaxDo\WooCommerce\App\Legacy\LoadProductTaxClass\OutputPort;

class CartItemTaxClassPresenter implements OutputPort
{
	public function present(string $tax_class_name): void
	{
		wc_get_template('cart-item-tax-class.php', ['tax_class_name' => $tax_class_name], TAX_DO_TEMPLATE_PATH,
			TAX_DO_TEMPLATE_PATH);
	}
}
