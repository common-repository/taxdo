import { TotalsWrapper } from '@woocommerce/blocks-checkout';
import {
	RemovableChip,
	ValidationInputError,
} from '@woocommerce/blocks-components';
import { userApplyCertificate } from './useApplyCertificate';

export const ValidCertificate = ( { certificateCode, state, onRemove } ) => {
	const { removeCertificate, isLoading, error } = userApplyCertificate( {
		onSuccess: () => {
			onRemove();
		},
	} );

	const apply = ( e ) => {
		e.preventDefault();
		removeCertificate();
	};

	return (
		<TotalsWrapper className="taxdo-block-components-apply-certificate-block">
			<div className="taxdo-block-components-totals-certificate">
				<span className="taxdo-block-components-totals-certificate__valid-code">
					The Certificate is valid for { state }
				</span>
				<RemovableChip
					element="li"
					onRemove={ apply }
					screenReaderText={ certificateCode }
					text={ certificateCode }
				/>
				<ValidationInputError errorMessage={ error } />
			</div>
		</TotalsWrapper>
	);
};
