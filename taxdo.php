<?php
/**
 * Plugin Name:   TaxDo
 * Plugin URI:    https://taxdo.co
 * Description:   A unique solution for managing sales tax calculation & tax exemption certificates.
 * Version:       2.3.10
 * License:       GPL-2.0+
 * License URL:   http://www.gnu.org/licenses/gpl-2.0.txt
 * Author:        TaxDo
 * Author URL:    https://taxdo.co
 * Tested up to:  6.4
 * Requires PHP:  8.0
 * WC requires at least: 8.3.0
 * WC tested up to: 8.6
 */

defined( 'ABSPATH' ) || exit( 'NO ACCESS!' );

use TaxDo\WooCommerce\TaxdoBootstrap;
use TaxDo\WooCommerce\Domain\Setting;
use TaxDo\WooCommerce\Infra\WordPress\ErrorHandler;

define( 'TAX_DO_PATH', plugin_dir_path( __FILE__ ) );
define( 'TAX_DO_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'src/templates/Legacy/' );
define( 'TAX_DO_URL', plugin_dir_url( __FILE__ ) );

// Define SHIPPING_WORKSHOP_VERSION.
$plugin_data = get_file_data( __FILE__, array( 'version' => 'version' ) );
define( 'TAX_DO_VERSION', $plugin_data['version'] );

require_once sprintf( '%s%s', TAX_DO_PATH, 'vendor/autoload.php' );

if ( ! function_exists( 'tax_do_woo_init' ) ) {
	/**
	 * Init taxdo plugin
	 */
	function tax_do_woo_init(): void {
		$bootstrapper = new TaxdoBootstrap();
		$bootstrapper->boot();
	}
}
add_action( 'plugins_loaded', ( new ErrorHandler(new Setting()) )->wrap_callable( 'tax_do_woo_init' ) );
