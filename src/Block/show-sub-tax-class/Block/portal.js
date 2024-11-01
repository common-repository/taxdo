import { createPortal } from '@wordpress/element';

const SubTaxClass = ( { children, root } ) => {
	return createPortal( children, root );
};

export default SubTaxClass;
