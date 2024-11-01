import { registerCheckoutFilters } from '@woocommerce/blocks-checkout';

export const registerFilters = ( pointsLabelPlural, discountRegex ) => {
	registerCheckoutFilters( 'taxdo_show_tax_class', {
		itemName: ( defaultValue, extensions, args ) => {
			return `${ defaultValue }<span hidden class="taxdo-cart-item" id=${ args.cartItem.key }></span>`;
		},
	} );
};
