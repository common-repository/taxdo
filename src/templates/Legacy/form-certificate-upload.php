<?php
defined('ABSPATH') || exit;

use TaxDo\WooCommerce\Domain\Certificate\Service\UploadCertificateForm;
$btnClass = esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '');

?>
<div  id="taxdo_upload_certificate_form" hidden>
	<div class="taxdo_certificate_wrapper">
		<h3 class="heading">Have you received your exemption code?</h3>
		<div class="taxdo_cert">
			<label for="taxdo_cert_code" class="screen-reader-text">Exemption Code:</label>
			<input type="text" name="taxdo_cert_code" class="input-text" id="taxdo_cert_code" value=""
				   placeholder="Exemption Code"/>
			<button type="button"
					id="taxdo_apply_cert_code"
					class="button<?php echo esc_attr($btnClass); ?>"
					name="taxdo_apply_cert_code"
					value="Apply certificate">Apply code
			</button>
		</div>

		<h4 class="heading">OR</h4>

		<form class="taxdo_upload_certificate_form">
			<h3 class="heading">Upload Your Sales Tax Certificate</h3>
			<p class="title">This feature is exclusively offered to buyers with a business presence in the United States.</p>

			<div class="taxdo_upload_certificate_field-wrapper">
				<div class="taxdo_business_info-fields__field-wrapper">
					<h5>Business Info</h5>
					<?php
					$fields = UploadCertificateForm::get_upload_cert_business_info_fields();
					foreach ($fields as $key => $field) {
						UploadCertificateForm::render_form_filed($key, $field);
					}
					?>
				</div>
				<div class="taxdo_cert_info-fields__field-wrapper">
					<h5>Certificate Info</h5>
					<?php
					$fields = UploadCertificateForm::get_upload_cert_cert_info_fields();
					foreach ($fields as $key => $field) {
						UploadCertificateForm::render_form_filed($key, $field);
					}
					?>
				</div>
			</div>
			<div class="taxdo_upload_certificate_form__submit-wrapper">
				<button type="submit" class="<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' .
					wc_wp_theme_get_element_class_name('button') : '') ?>">
					Submit
				</button>
			</div>
		</form>

	</div>
</div>

