/**
 * External dependencies
 */
import { useState } from '@wordpress/element';
import {
	Button,
	ValidatedTextInput,
	ValidationInputError,
} from '@woocommerce/blocks-components';
import { Button as Link } from '@wordpress/components';
import { TotalsWrapper } from '@woocommerce/blocks-checkout';

import classnames from 'classnames';

import { userApplyCertificate } from './useApplyCertificate';

const TotalsCertificate = ( {
	displayCertificateForm = false,
	onSuccess,
	onRequestCode,
} ) => {
	const [ certificateValue, setCertificateValue ] = useState( '' );

	const [ isCertificateFormHidden, setIsCertificateFormHidden ] = useState(
		! displayCertificateForm
	);

	const { submitCertificateCode, isLoading, error } = userApplyCertificate( {
		onSuccess: () => {
			setCertificateValue( '' );
			setIsCertificateFormHidden( true );
			onSuccess();
		},
	} );
	const textInputId = 'taxdo-block-components-totals-certificate__input';
	const formWrapperClass = classnames(
		'taxdo-block-components-totals-certificate__content',
		{
			'screen-reader-text': isCertificateFormHidden,
		}
	);

	const handleCertificateAnchorClick = ( e ) => {
		e.preventDefault();
		setIsCertificateFormHidden( false );
	};

	const apply = ( e ) => {
		e.preventDefault();
		submitCertificateCode( certificateValue );
	};

	return (
		<TotalsWrapper className="taxdo-block-components-apply-certificate-block">
			<div className="taxdo-block-components-totals-certificate">
				{ isCertificateFormHidden ? (
					<a
						role="button"
						href="#taxdo-block-components-totals-certificate__form"
						className="taxdo-block-components-totals-certificate-link"
						aria-label="Have a Sales Tax Exemption Certificate?"
						onClick={ handleCertificateAnchorClick }
					>
						Have a Sales Tax Exemption Certificate?
					</a>
				) : (
					<div className={ formWrapperClass }>
						<form
							className="taxdo-block-components-totals-certificate__form"
							id="taxdo-block-components-totals-certificate__form"
						>
							<ValidatedTextInput
								id={ textInputId }
								errorId="certificate"
								className="taxdo-block-components-totals-certificate__input"
								label="Enter exemption code"
								value={ certificateValue }
								onChange={ ( newCertificateValue ) => {
									setCertificateValue( newCertificateValue );
								} }
								focusOnMount={ true }
								validateOnMount={ false }
								showError={ false }
							/>
							<Button
								className="taxdo-block-components-totals-certificate__button"
								disabled={ isLoading || ! certificateValue }
								showSpinner={ isLoading }
								onClick={ apply }
								type="submit"
							>
								Apply
							</Button>
						</form>
						<ValidationInputError
							propertyName="certificate"
							errorMessage={ error }
						/>
						<Link
							onClick={ ( e ) => {
								e.preventDefault();
								onRequestCode();
							} }
							href="#taxdo-block-components-apply-certificate__form"
							variant="link"
							type="button"
						>
							Don't have an exemption code?
						</Link>
					</div>
				) }
			</div>
		</TotalsWrapper>
	);
};

export default TotalsCertificate;
