jQuery( document ).ready( function ( $ ) {
	//****** Start Block UI *****
	function blockUi() {
		$.blockUI( {
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6,
			},
		} );
	}

	function unblockUI() {
		$.unblockUI();
	}
	//****** End Block UI *****

	//****** Start Cert *****
	//****** Start Remove Cert *****
	$( document ).on( 'click', '#taxdo_remove_cert_code', function ( e ) {
		e.preventDefault();
		console.log( '#taxdo_remove_cert_code' );
		const data = {
			action: 'taxdo_remove_certificate',
			security: cart_object.nonce,
		};
		$.post( cart_object.ajax_url, data, function ( response ) {
			if ( response.success ) {
				const update_cart = $( "[name='update_cart']" );
				update_cart.prop( 'disabled', false );
				update_cart.removeAttr( 'disabled' );
				update_cart.trigger( 'click' );
			}
		} );
	} );
	//****** End Remove Cert *****
	const certificate_upload_form = $( '#taxdo_upload_certificate_form' );
	$( document ).on( 'click', '#taxdo_apply_cert_code', function ( e ) {
		e.preventDefault();
		const data = {
			action: 'taxdo_apply_certificate',
			security: cart_object.nonce,
			taxdo_cert_code: $( '#taxdo_cert_code' ).val(),
		};
		$.post( cart_object.ajax_url, data, function ( response ) {
			if ( response.success ) {
				const update_cart = $( "[name='update_cart']" );
				update_cart.prop( 'disabled', false );
				update_cart.removeAttr( 'disabled' );
				update_cart.trigger( 'click' );
				certificate_upload_form.toggle();
			}

			const wrapper = $( '.woocommerce-notices-wrapper' );
			wrapper.empty();
			wrapper.append( response.data );
			wrapper.get( 0 ).scrollIntoView();
		} );
	} );

	const upload_certificate_toggle = $( '#taxdo_upload_tax_cert' );
	if ( certificate_upload_form.length ) {
		if ( upload_certificate_toggle.length ) {
			$( document ).on(
				'click',
				'#taxdo_upload_tax_cert',
				function ( e ) {
					e.preventDefault();
					certificate_upload_form.toggle();
				}
			);
		}
		certificate_upload_form.on( 'submit', function ( e ) {
			e.preventDefault();

			const formData = new FormData();

			const files = $( e.target ).find( '#attachment' )[ 0 ].files[ 0 ];
			formData.append( 'attachment', files );
			formData.append( 'action', 'taxdo_upload_tax_cert' );
			formData.append( 'security', cart_object.nonce );

			const fd = $( e.target ).serializeArray();
			for ( let i = 0; i < fd.length; i++ ) {
				formData.append( fd[ i ].name, fd[ i ].value );
			}
			blockUi();
			$.ajax( {
				url: '/wp-json/taxdo/v1/certificate-request',
				data: formData,
				processData: false,
				contentType: false,
				type: 'POST',
				success: function ( response ) {
					if ( response.reference ) {
						certificate_upload_form.toggle();
					}
					const wrapper = $( '.woocommerce-notices-wrapper' );
					wrapper.empty();
					const r = `<div class="wc-block-components-notice-banner is-success" role="alert"><div class="wc-block-components-notice-banner__content">Your Sales Tax Certificate  is received (Ref ${ response.reference }). Our team is reviewing it, and you'll get a confirmation email with an exemption code, if the certificate meets all the requirements.</div></div>`;
					wrapper.append( r );
					wrapper.get( 0 ).scrollIntoView();
				},
				always: function () {
					unblockUI();
				},
				error: function ( error ) {
					const wrapper = $( '.woocommerce-notices-wrapper' );
					wrapper.empty();
					const e = `<div class="wc-block-components-notice-banner is-error" role="alert"><div class="wc-block-components-notice-banner__content">${ error.responseJSON.message }</div></div>`;
					wrapper.append( e );
					wrapper.get( 0 ).scrollIntoView();
				},
				complete: function () {
					unblockUI();
				},
			} );
		} );
	}

	$( '#country' ).select2( { width: '100%' } );
	$( '#state' ).select2( { width: '100%' } );
	//****** End Cert *****

	//****** Start Checkout page update sub tax class *****
	function handle_change_sub_tax_class() {
		$( document.body ).trigger( 'update_checkout' );
	}

	$( '[id^=sub_tax_taxDo_]' ).on( 'change', handle_change_sub_tax_class );
	$( document.body ).on( 'updated_checkout', function () {
		$( '[id^=sub_tax_taxDo_]' ).on( 'change', handle_change_sub_tax_class );
		$( 'select[id^=sub_tax_taxDo_]' ).each( function () {
			$( this ).select2( {
				width: '100%',
				minimumResultsForSearch: 'Infinity',
			} );
		} );
	} );
	//****** End Checkout page update sub tax class *****
} );
