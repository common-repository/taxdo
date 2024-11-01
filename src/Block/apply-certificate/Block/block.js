/**
 * External dependencies
 */
import { useState, useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { CertificateRequestForm } from './CertificateRequestForm';
import { ValidCertificate } from './ValidCertificate';
import TotalsCertificate from './TotalsCertificate';

const STATUS = {
	INIT: 1,
	ENTER_CODE: 2,
	VALID_CERTIFICATE: 3,
	CERTIFICATE_REQUEST: 4,
};

export const Block = ( {
	cart,
	validation,
	checkoutExtensionData,
	extensions,
} ) => {
	const { taxdo_certificate } = extensions;

	const [ status, setStatus ] = useState( () => {
		if ( ! taxdo_certificate?.certificate ) {
			return STATUS.INIT;
		}

		return STATUS.VALID_CERTIFICATE;
	} );

	useEffect( () => {
		if ( taxdo_certificate?.certificate ) {
			setStatus( STATUS.VALID_CERTIFICATE );
		}
	}, [ taxdo_certificate?.certificate ] );

	switch ( status ) {
		case STATUS.INIT:
		case STATUS.ENTER_CODE:
			return (
				<TotalsCertificate
					displayCertificateForm={ STATUS.ENTER_CODE === status }
					onRequestCode={ () =>
						setStatus( STATUS.CERTIFICATE_REQUEST )
					}
					onSuccess={ () => setStatus( STATUS.VALID_CERTIFICATE ) }
				/>
			);
		case STATUS.VALID_CERTIFICATE:
			return (
				<ValidCertificate
					onRemove={ () => setStatus( STATUS.ENTER_CODE ) }
					state={ taxdo_certificate?.valid_state }
					certificateCode={ taxdo_certificate?.certificate }
				/>
			);
		case STATUS.CERTIFICATE_REQUEST:
			return (
				<CertificateRequestForm
					cancel={ () => setStatus( STATUS.INIT ) }
					address={ cart.billingAddress }
				/>
			);
		default:
			return null;
	}
};
