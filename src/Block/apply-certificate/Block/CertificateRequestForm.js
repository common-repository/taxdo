/**
 * External dependencies
 */
import {
	Button,
	Panel,
	Textarea,
	TextInput,
	ValidationInputError,
} from '@woocommerce/blocks-components';
import { useInstanceId } from '@wordpress/compose';
import { useState } from '@wordpress/element';
import {
	FormFileUpload,
	TextHighlight,
	Flex,
	FlexBlock,
	FlexItem,
} from '@wordpress/components';

const validateAddress = ( {
	address_1,
	city,
	country,
	email,
	first_name,
	last_name,
	postcode,
	state,
} ) => {
	return (
		address_1.length &&
		city.length &&
		country.length &&
		email.length &&
		first_name.length &&
		last_name.length &&
		postcode.length &&
		state.length
	);
};

const initValue = {
	businessName: '',
	reason: '',
	note: '',
	attachment: null,
};

export const CertificateRequestForm = ( { address, cancel } ) => {
	const [ data, setData ] = useState( initValue );
	const [ referenceNo, setReferenceNo ] = useState( false );
	const [ error, setError ] = useState( false );
	const [ isLoading, setIsLoading ] = useState( false );

	const instanceId = useInstanceId( CertificateRequestForm );

	const isValidAddress = validateAddress( address );

	const submit = ( e ) => {
		e.preventDefault();

		const { businessName, reason, note, attachment } = data;

		const formData = new FormData();

		formData.append( 'business_name', businessName );
		formData.append( 'reason', reason );
		formData.append( 'note', note );
		formData.append( 'address_1', address.address_1 );
		formData.append( 'address_2', address.address_2 );
		formData.append( 'city', address.city );
		formData.append( 'country', address.country );
		formData.append( 'email', address.email );
		formData.append( 'first_name', address.first_name );
		formData.append( 'last_name', address.last_name );
		formData.append( 'postcode', address.postcode );
		formData.append( 'state', address.state );
		formData.append( 'phone', address.phone );
		formData.append( 'attachment', attachment[ 0 ] );

		setIsLoading( true );
		fetch( '/wp-json/taxdo/v1/certificate-request', {
			method: 'POST',
			body: formData,
		} )
			.then( ( response ) => {
				if ( response.ok ) {
					return response.json();
				} else {
					response.json().then( ( e ) => {
						setError( e.message );
					} );
				}
			} )
			.then( ( data ) => {
				setReferenceNo( data.reference );
			} )
			.catch( ( error ) => {
				setError( 'Error on submit certificate.' );
			} )
			.finally( () => setIsLoading( false ) );
	};
	if ( referenceNo ) {
		return (
			<Panel
				title="Submit Sales Tax Certificate"
				titleTag="div"
				initialOpen
				className="wc-block-components-totals-wrapper"
			>
				<TextHighlight
					highlight={ referenceNo }
					text={ `Your sales tax exemption certificate is received (ref. ${ referenceNo }). Our team is reviewing it, and you'll get a confirmation email with a code for checkout.` }
				/>
			</Panel>
		);
	}
	return (
		<Panel
			title="Submit Sales Tax Certificate"
			titleTag="div"
			initialOpen
			className="wc-block-components-totals-wrapper"
		>
			<form>
				<TextInput
					id={ `taxdo-block-components-apply-certificate-business-${ instanceId }` }
					className="taxdo-block-components-apply-certificate__input"
					label="Business name (optional)"
					onChange={ ( value ) =>
						setData( { ...data, businessName: value } )
					}
					value={ data.businessName }
				/>
				<TextInput
					id={ `taxdo-block-components-apply-certificate-reason-${ instanceId }` }
					className="taxdo-block-components-apply-certificate__input"
					label="Reason (optional)"
					onChange={ ( value ) =>
						setData( { ...data, reason: value } )
					}
					value={ data.reason }
				/>
				<div
					style={ {
						paddingBottom: '.50em',
					} }
				></div>
				<Textarea
					id={ `taxdo-block-components-apply-certificate-note-${ instanceId }` }
					className=""
					onTextChange={ ( value ) =>
						setData( { ...data, note: value } )
					}
					placeholder="Note (optional)"
					value={ data.note }
				/>
				<FormFileUpload
					accept="image/*,application/pdf"
					onChange={ ( event ) =>
						setData( {
							...data,
							attachment: event.currentTarget.files,
						} )
					}
					render={ ( { openFileDialog } ) => {
						return (
							<>
								<Button
									type="button"
									variant="outlined"
									onClick={ openFileDialog }
								>
									Select attachment
								</Button>

								{ data.attachment && (
									<span> { data.attachment[ 0 ].name }</span>
								) }
							</>
						);
					} }
				/>
				<Flex direction="row-reverse">
					<FlexItem>
						<Button
							onClick={ submit }
							disabled={
								! isValidAddress ||
								! data.attachment ||
								isLoading
							}
							showSpinner={ isLoading }
							className="taxdo-block-components-apply-certificate__button"
							type="submit"
						>
							Submit
						</Button>
					</FlexItem>
					<FlexItem>
						<Button
							onClick={ cancel }
							className="taxdo-block-components-apply-certificate__button"
							type="button"
							variant="outlined"
							disabled={ isLoading }
						>
							Cancel
						</Button>
					</FlexItem>
					<FlexBlock />

				</Flex>
				{ ! isValidAddress && (
					<ValidationInputError errorMessage="Please enter your address and email." />
				) }
				{ ! data.attachment && (
					<ValidationInputError errorMessage="Please select certificate file." />
				) }
				{ error && <ValidationInputError errorMessage={ error } /> }
			</form>
		</Panel>
	);
};
