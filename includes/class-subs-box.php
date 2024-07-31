<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree
 * @since      1.0.0
 *
 * @package    Subs_Box
 * @subpackage Subs_Box/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Subs_Box
 * @subpackage Subs_Box/includes
 * @author     Divang Rastogi <divangrastogi@gmail.com>
 */
class Subs_Box {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Subs_Box_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SUBS_BOX_VERSION' ) ) {
			$this->version = SUBS_BOX_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'subs-box';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Subs_Box_Loader. Orchestrates the hooks of the plugin.
	 * - Subs_Box_i18n. Defines internationalization functionality.
	 * - Subs_Box_Admin. Defines all hooks for the admin area.
	 * - Subs_Box_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-subs-box-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-subs-box-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-subs-box-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-subs-box-public.php';

		$this->loader = new Subs_Box_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Subs_Box_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Subs_Box_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Subs_Box_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('woocommerce_loaded', $plugin_admin, 'subs_box_create_custom_product_type');
		$this->loader->add_action('woocommerce_product_class', $plugin_admin, 'subs_box_add_custom_product_class', 10, 2);
		$this->loader->add_action('product_type_selector', $plugin_admin, 'subs_box_add_subscription_type');
		$this->loader->add_action('woocommerce_product_options_general_product_data', $plugin_admin, 'subs_box_subscription_tab_product_tab_content');
		$this->loader->add_action('woocommerce_process_product_meta', $plugin_admin, 'subs_box_save_subscription_settings');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Subs_Box_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('woocommerce_single_product_summary', $plugin_public, 'subs_box_display_custom_fields_on_product_page', 25);
		$this->loader->add_filter('woocommerce_add_cart_item_data', $plugin_public, 'subs_box_add_frequency_option_to_cart_item', 10, 3 );
		$this->loader->add_filter('woocommerce_get_item_data', $plugin_public, 'subs_box_display_frequency_option_cart_item', 10, 2  );
		$this->loader->add_action('woocommerce_add_order_item_meta', $plugin_public, 'subs_box_save_frequency_option_order_item_meta',  10, 3);
		$this->loader->add_action('woocommerce_order_item_meta_end', $plugin_public, 'subs_box_display_frequency_option_order_item_meta',  10, 4 );
		$this->loader->add_action('woocommerce_before_calculate_totals', $plugin_public, 'subs_box_set_custom_cart_item_price',  10, 1);
		$this->loader->add_action('woocommerce_sub_box_add_to_cart', $plugin_public, 'subs_box_add_to_cart',  10, 1);
		$this->loader->add_action('woocommerce_add_to_cart', $plugin_public, 'subs_box_set_custom_quantity_in_cart', 10, 6);
		$this->loader->add_action('woocommerce_order_status_completed', $plugin_public, 'subs_box_reduce_custom_product_quantity', 10);
		$this->loader->add_filter('woocommerce_add_to_cart_validation', $plugin_public, 'subs_box_validate_custom_product_quantity', 10, 3  );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Subs_Box_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
