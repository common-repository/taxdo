<?php

defined('ABSPATH') || exit;

do_action('taxdo_before_cart_certificate');

$btnClass = esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : '');
?>
<tr>
	<td colspan="6" class="actions">

		<?php if ($showUpload): ?>
			<button
				type="submit"
				id="taxdo_upload_tax_cert"
				class="button<?php echo esc_attr($btnClass); ?>"
				name="taxdo_upload_cert"
				value="Upload Tax Certificate">Submit Sales Tax Certificate
			</button>
		<?php endif; ?>
	</td>
</tr>

<?php do_action('taxdo_after_cart_certificate'); ?>
