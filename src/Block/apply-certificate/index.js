/**
 * External dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
// import { box, Icon } from '@wordpress/icons';
/**
 * Internal dependencies
 */
import { Edit } from './edit';
import metadata from './block.json';

registerBlockType( metadata, {
	// icon: {
	// 	src: <Icon icon={ box } />,
	// },
	edit: Edit,
	// save: Save,
} );
