<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree
 * @since             1.0.0
 * @package           Subs_Box
 *
 * @wordpress-plugin
 * Plugin Name:       Subscription Box
 * Plugin URI:        https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree
 * Description:       Subscription Box plugin for WB Coms
 * Version:           1.0.0
 * Author:            Divang Rastogi
 * Author URI:        https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       subs-box
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SUBS_BOX_VERSION', '1.0.0' );


if( ! defined('SUBS_ADMIN_PARTIALS')){

	define('SUBS_ADMIN_PARTIALS', plugin_dir_path( __FILE__ ).'admin/partials/' );

}

if( ! defined('SUBS_PUBLIC_PARTIALS')){

	define('SUBS_PUBLIC_PARTIALS', plugin_dir_path( __FILE__ ).'public/partials/' );

}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-subs-box-activator.php
 */
function activate_subs_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-subs-box-activator.php';
	Subs_Box_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-subs-box-deactivator.php
 */
function deactivate_subs_box() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-subs-box-deactivator.php';
	Subs_Box_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_subs_box' );
register_deactivation_hook( __FILE__, 'deactivate_subs_box' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-subs-box.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_subs_box() {

	$plugin = new Subs_Box();
	$plugin->run();

}
run_subs_box();
