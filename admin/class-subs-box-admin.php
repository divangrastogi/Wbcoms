<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree
 * @since      1.0.0
 *
 * @package    Subs_Box
 * @subpackage Subs_Box/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Subs_Box
 * @subpackage Subs_Box/admin
 * @author     Divang Rastogi <divangrastogi@gmail.com>
 */
class Subs_Box_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Subs_Box_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Subs_Box_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/subs-box-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Subs_Box_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Subs_Box_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/subs-box-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * function for creation the custom product class file i.e subs_box_subscription
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_create_custom_product_type()
	{
		include_once(SUBS_ADMIN_PARTIALS . 'subs-box-product-class.php');
	}

	/**
	 * function for adding the custom product class i.e subs_box_subscription
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_add_custom_product_class($subs_box_classname, $subs_box_product_type)
	{
			if ($subs_box_product_type == 'sub_box') {
				$subs_box_classname = 'WC_Product_Subsbox';
			}
		return $subs_box_classname;
	}


	/**
	 * function for adding the custom product type in the product type list 
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_add_subscription_type($types)
	{
		$types['sub_box'] = __('Subscription Box', 'subs-box');
		return $types;
	}


	/**
	 * function for showing the subscription field html
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_subscription_tab_product_tab_content()
	{
		global $post;
		?><div id='sub_box_subscription_options' class='options_group hidden show_if_sub_box'>
				<div class='options_group'>
					<?php
					woocommerce_wp_textarea_input(
						array(
							'id'          => '_frequency_options',
							'label'       => __( 'Frequency Options', 'subs-box' ),
							'placeholder' => 'Enter options separated by commas (e.g. Monthly, Quarterly, Annually)',
							'description' => __( 'Enter the frequency options for this product, separated by commas.', 'subs-box' )
						)
					);
					?>
				</div>
				<div class='options_group'>
					<?php
					woocommerce_wp_textarea_input(
						array(
							'id'          => '_frequency_price_options',
							'label'       => __( 'Frequency Price Options', 'subs-box' ),
							'placeholder' => 'Enter Price options separated by commas along with the repective frequency(e.g. 24, 34, 54)',
							'description' => __( 'Enter the frequency price options for this product along with the repective frequency, separated by commas.', 'subs-box' )
						)
					);
					?>
				</div>
				<div class='options_group'>
					<?php
					  woocommerce_wp_text_input(
						array(
							'id'          => '_number_of_items',
							'label'       => __( 'Number of Items in Box', 'subs-box' ),
							'placeholder' => 'Enter number of items',
							'desc_tip'    => 'true',
							'description' => __( 'Specify the number of items in the box.', 'subs-box' )
						)
					);
					?>
				</div>
			</div>
		<?php
	}

	/**
	 * function for saving subscription the settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_save_subscription_settings($subs_box_post_id)
	{
		if (isset($_POST['_frequency_options']) && !empty($_POST['_frequency_options'])) {
			update_post_meta($subs_box_post_id, '_frequency_options', sanitize_text_field($_POST['_frequency_options']));
		}
		if (isset($_POST['_frequency_price_options']) && !empty($_POST['_frequency_price_options'])) {
			update_post_meta($subs_box_post_id, '_frequency_price_options', sanitize_text_field($_POST['_frequency_price_options']));
			$price_options = explode( ',', sanitize_text_field($_POST['_frequency_price_options']) );
			$custom_price = isset( $_POST['_frequency_price_options'] ) ? $price_options[0] : '';
			$regular_price = update_post_meta($subs_box_post_id, '_regular_price', sanitize_text_field($custom_price));
			$price = update_post_meta($subs_box_post_id, '_price', sanitize_text_field($custom_price));
		}
		if (isset($_POST['_number_of_items']) && !empty($_POST['_number_of_items'])) {
				update_post_meta($subs_box_post_id, '_number_of_items', sanitize_text_field($_POST['_number_of_items']));
		}
	}

}
