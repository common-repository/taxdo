/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { Disabled, PanelBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.scss';

export const Edit = ( { attributes, setAttributes } ) => {
	const { text } = attributes;
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps } style={ { display: 'block' } }>
			<InspectorControls>
				<PanelBody title={ __( 'Block options', 'taxdo' ) }>
					Apply certificate code:
				</PanelBody>
			</InspectorControls>
			<div>
				<Disabled>Have a Sales Tax Exemption Certificate? </Disabled>
			</div>
		</div>
	);
};

// export const Save = ( { attributes } ) => {
// 	const { text } = attributes;
// 	return (
// 		<div { ...useBlockProps.save() }>
// 			<RichText.Content value={ text } />
// 		</div>
// 	);
// };
