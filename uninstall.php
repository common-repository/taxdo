<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$options = [
	'woocommerce_taxdo_settings',
	'taxdo_woocommerce_tax_classes',
];

foreach ( $options as $option ) {
	delete_option( $option );
}

delete_post_meta_by_key('_tax_do_class_id');
delete_transient('taxdo_current_tax_classes');
