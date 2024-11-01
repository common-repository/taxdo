/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { Disabled, PanelBody, SelectControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.scss';
import { options } from './options';

export const Edit = ( { attributes, setAttributes } ) => {
	const { text } = attributes;
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps } style={ { display: 'block' } }>
			<InspectorControls>
				<PanelBody title={ __( 'Block options', 'taxdo' ) }>
					Show sub tax class options:
				</PanelBody>
			</InspectorControls>
		</div>
	);
};

export const Save = ( { attributes } ) => {
	const { text } = attributes;
	return (
		<div { ...useBlockProps.save() }>
			<RichText.Content value={ text } />
		</div>
	);
};
