/**
 * External dependencies
 */
import { useEffect, useState } from '@wordpress/element';
import { extensionCartUpdate } from '@woocommerce/blocks-checkout';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Portal from './portal';

function TaxClass( { name } ) {
	return (
		<li className="wc-block-components-product-details__tax-class">
			<span className="wc-block-components-product-details__name">
				Tax Class:
			</span>
			<span className="wc-block-components-product-details__value">
				{ name }
			</span>
		</li>
	);
}

function SubTaxClass( { value, options, onChange } ) {
	return (
		<li className="wc-block-components-product-details__sub_tax-class">
			<SelectControl
				label={ __( 'Sub-Tax Class', 'taxdo' ) }
				value={ value }
				options={ options }
				onChange={ onChange }
				size="compact"
			/>
		</li>
	);
}

export const Block = ( {
	cart,
	validation,
	checkoutExtensionData,
	extensions,
} ) => {
	const { state, country } = cart.billingAddress;
	const [ subTaxClasses, setSubTaxClasses ] = useState();
	const [ cartItems, setCartItems ] = useState();
	const [ itemSubTaxClasses, setItemSubTaxClasses ] = useState( () => {
		return cart.cartItems.map( ( item ) => {
			return {
				id: item.id,
				key: item.key,
				value: -1,
			};
		} );
	} );

	useEffect( () => {
		if ( itemSubTaxClasses.length ) {
			checkoutExtensionData?.setExtensionData(
				'taxdo',
				'itemSubTaxClasses',
				itemSubTaxClasses
			);

			extensionCartUpdate( {
				namespace: 'taxdo',
				data: {
					action: 'change-sub-tax-class',
					item_sub_tax_classes: itemSubTaxClasses,
				},
			} );
		}
	}, [ itemSubTaxClasses ] );

	useEffect( () => {
		fetch( `/wp-json/taxdo/v1/states/${ state }/sub-tax-classes` )
			.then( ( res ) => res.json() )
			.then( ( res ) => {
				setSubTaxClasses( res.data );
			} )
			.catch( ( err ) => {} );
	}, [ state ] );

	useEffect( () => {
		const ref = setInterval( () => {
			if ( cartItems ) {
				clearInterval( ref );
				return;
			}
			const items = document.querySelectorAll( '.taxdo-cart-item' );
			if ( items.length ) {
				const newRoots = [ ...items ].map( ( el ) => {
					return {
						root: el.parentElement.parentElement.querySelector(
							'.wc-block-components-product-metadata'
						),
						key: el.id,
					};
				} );

				setCartItems( newRoots );
				clearInterval( ref );
			}
		}, 100 );

		return () => {
			clearInterval( ref );
		};
	}, [] );

	if ( ! subTaxClasses || ! cartItems ) return null;

	return (
		<>
			{ cartItems.map( ( cartItem ) => {
				let item = cart.cartItems.find(
					( item ) => item.key === cartItem.key
				);
				if ( ! item || ! item.extensions.taxdo_tax_class?.tc_id )
					return;

				let name = '';
				let tacClassId = 0;
				switch ( country ) {
					case 'US':
						name = item.extensions.taxdo_tax_class.tc_us_name;
						tacClassId = item.extensions.taxdo_tax_class.tc_us_id;
						break;
					case 'CA':
						name = item.extensions.taxdo_tax_class.tc_ca_name;
						tacClassId = item.extensions.taxdo_tax_class.tc_ca_id;
						break;
					default:
						return;
				}

				const opt = subTaxClasses?.filter((taxClass) => taxClass.tax_class_id === tacClassId)[0]['sub_tax_classes']?.map(
					( subTaxClass ) => {
						return {
							label: subTaxClass[ 'name' ],
							value: subTaxClass[ 'id' ],
						};
					}
				);
				return (
					<Portal
						root={ cartItem.root }
						key={ cartItem.key + tacClassId }
					>
						<ul className="wc-block-components-product-details">
							<TaxClass name={ name } />
							{ checkoutExtensionData && opt && (
								<SubTaxClass
									value={
										itemSubTaxClasses.find(
											( sub ) => sub.key === item.key
										)?.value ?? -1
									}
									options={ [
										{
											label: 'default',
											value: -1,
										},
										...opt,
									] }
									onChange={ ( value ) =>
										setItemSubTaxClasses( ( prev ) => {
											return [
												...prev.filter(
													( sub ) =>
														sub.key != item.key
												),
												{
													id: item.id,
													key: item.key,
													value: value,
												},
											];
										} )
									}
								/>
							) }
						</ul>
					</Portal>
				);
			} ) }
		</>
	);
};
