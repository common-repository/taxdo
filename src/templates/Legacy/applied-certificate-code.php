<?php
defined('ABSPATH') || exit;
?>

	<tr class="cart-discount exemption-<?php echo esc_attr(sanitize_title($exemption)); ?>">
		<th>Exemption code:</th>
		<td data-title="taxdo-exemption-code"><span>Applied for <?php echo esc_attr($state) ?> (<?php echo esc_attr
				($exemption) ?>)</span>
			<a id="taxdo_remove_cert_code" href="#"><?php echo esc_html__('[Remove]', 'taxdo') ?></a></td>
	</tr>
<?php
