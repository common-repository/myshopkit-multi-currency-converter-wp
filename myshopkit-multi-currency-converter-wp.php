<?php
/**
 * Plugin Name: WooCommerce Currency Converter | MyShopKit
 * Plugin URI: https://woocommerce.myshopkit.app
 * Author: wiloke
 * Author URI: https://woocommerce.myshopkit.app/product/woocommerce-currency-converter-myshopkit/
 * Version: 1.0.4
 * Tested up to: 5.8
 * WC requires at least: 4.0
 * WC tested up to: 5.5
 * Requires PHP: 7.4
 * Text Domain: myshopkit-multi-currency-converter-wp
 * Description: Provide localized shopping experience, increase global sales
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * https://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

add_action( 'admin_notices', function () {

	if ( ! class_exists( 'WooCommerce' ) ) {
		?>
        <div id="mskmc-converter-warning" class="notice notice-error">
			<?php esc_html_e( 'Please install and activate WooCommerce to use Multi Currency for WooCommerce plugin.',
				'myshopkit-multi-currency-converter-wp' ); ?>
        </div>
		<?php
	}
} );

use MSKMCWP\Dashboard\Controllers\AuthController;

define( 'MSKMC_PREFIX', 'mskmc_' );
define( 'MSKMC_VERSION', uniqid() );
define( 'MSKMC_HOOK_PREFIX', 'mskmc/' );
define( 'MSKMC_REST_VERSION', 'v1' );
define( 'MSKMC_REST_NAMESPACE', 'mskmc' );
define( 'MSKMC_NAMESPACE', 'myshopkit-multi-currency-converter-wp' );
define( 'MSKMC_DS', '/' );
define( 'MSKMC_REST', MSKMC_REST_NAMESPACE . MSKMC_DS . MSKMC_REST_VERSION );

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Dashboard/Dashboard.php';
require_once plugin_dir_path( __FILE__ ) . 'src/Store/Store.php';
require_once plugin_dir_path( __FILE__ ) . 'src/PostScript/PostScript.php';
require_once plugin_dir_path( __FILE__ ) . 'src/RateExchange/RateExchange.php';

register_activation_hook( __FILE__, function () {
	AuthController::generateAuth();
} );

register_deactivation_hook( __FILE__, function () {
	AuthController::autoDeleteAuth();
} );

add_action( 'plugins_loaded', 'mskMultiCurrencyAfterPluginsLoaded' );
function mskMultiCurrencyAfterPluginsLoaded() {
	load_plugin_textdomain( 'myshopkit-multi-currency-converter', false,
		basename( dirname( __DIR__ ) ) . '/languages' );
}
