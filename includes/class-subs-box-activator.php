<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree
 * @since      1.0.0
 *
 * @package    Subs_Box
 * @subpackage Subs_Box/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Subs_Box
 * @subpackage Subs_Box/includes
 * @author     Divang Rastogi <divangrastogi@gmail.com>
 */
class Subs_Box_Activator {

	/**
	 *
	 * Activate plugin if woocommerce is active else show warning
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( esc_html__( 'This plugin requires WooCommerce to be active. Please install and activate WooCommerce.', 'subs-box' ), 'Plugin dependency check', array( 'back_link' => true ) );
		}
	}

}
