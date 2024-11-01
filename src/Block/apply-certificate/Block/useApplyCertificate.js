/**
 * External dependencies
 */
import { useState } from '@wordpress/element';
import { extensionCartUpdate } from '@woocommerce/blocks-checkout';

export const userApplyCertificate = ( { onSuccess = () => {} } ) => {
	const [ error, setError ] = useState();
	const [ isLoading, setIsLoading ] = useState( false );

	const handleRequest = ( request ) => {
		setIsLoading( true );
		extensionCartUpdate( request )
			.then( ( result ) => {
				if ( result ) {
					onSuccess();
				}
			} )
			.catch( ( e ) => setError( e.message ) )
			.finally( () => setIsLoading( false ) );
	};

	const submitCertificateCode = ( certificateValue ) => {
		handleRequest( {
			namespace: 'taxdo',
			data: {
				action: 'apply-certificate',
				certificate: certificateValue,
			},
		} );
	};
	const removeCertificate = () => {
		handleRequest( {
			namespace: 'taxdo',
			data: {
				action: 'remove-certificate',
			},
		} );
	};

	return { submitCertificateCode, removeCertificate, isLoading, error };
};
