/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
/**
 * Internal dependencies
 */
import './style.scss';
import { registerFilters } from './filters';

const render = () => {
	return null;
};

registerPlugin( 'taxdo-woocommerce', {
	render,
	scope: 'woocommerce-checkout',
} );

registerFilters();
