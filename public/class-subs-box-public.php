<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://https://tastewp.com/create/NMS/7.4/6.6.1/tastewp-default/twentytwentythree
 * @since      1.0.0
 *
 * @package    Subs_Box
 * @subpackage Subs_Box/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Subs_Box
 * @subpackage Subs_Box/public
 * @author     Divang Rastogi <divangrastogi@gmail.com>
 */
class Subs_Box_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/subs-box-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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



		if ( is_product() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/subs-box-public.js', array( 'jquery' ), $this->version, false );
			// Localize script to pass PHP values to JS
			global $post;
			$product = wc_get_product( $post->ID );
			$hide_price = ($product->get_type()=="sub_box")?true:false;
 			$frequency_options = get_post_meta( $post->ID, '_frequency_options', true );
			$frequency_price_options = get_post_meta( $post->ID, '_frequency_price_options', true );
			if ( ! empty( $frequency_options ) &&  ! empty( $frequency_price_options )) {
				$options = explode( ',', $frequency_options );
				foreach ( $options as $key=>$option ) {
					$options[$key] = trim( $option );
				}
				$price_options = explode( ',', $frequency_price_options );
				foreach ( $price_options as $price_key=>$price_option ) {
					$price_options[$price_key] = trim( $price_option );
				}
				$prices=array_combine($options,$price_options);
				wp_localize_script( $this->plugin_name, 'frequencyPrices', $prices );
				wp_localize_script( $this->plugin_name, 'other_data', array(
					'hide_price' => $hide_price,
					'currency_symbol' => get_woocommerce_currency_symbol()
				) );
			}
		}
	}


	/**
	 * function for displying the subscription options and its price along with the item to user
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_display_custom_fields_on_product_page() {
		global $post;
		$product = wc_get_product( $post->ID );
		if($product->get_type()=="sub_box"){
			// Display subscription frequency options
			$frequency_options = get_post_meta( $post->ID, '_frequency_options', true );
			if ( ! empty( $frequency_options ) ) {
				$options = explode( ',', $frequency_options );
				echo '<div class="frequency-options">';
				echo '<label>' . __( 'Choose Subscription Frequency: ', 'subs-box' ) . '</label>';
				echo '<select name="frequency_option">';
				foreach ( $options as $option ) {
					$option = trim( $option );
					echo '<option value="' . esc_attr( $option ) . '">' . esc_html( $option ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			// Display subscription price options
			$frequency_price_options = get_post_meta( $post->ID, '_frequency_price_options', true );
			if ( ! empty( $frequency_price_options ) ) {
				$price_options = explode( ',', $frequency_price_options );

				echo '<div class="frequency-price-options">';
				echo '<label>' . __( 'Price: ', 'subs-box' ) . '</label>';
				echo '<input type="hidden" name="frequency_price">';
				echo '<b><span class="subs-box-price-field">'.$price_options[0].'</span></b>';
				echo '</div>';
			}

			// Display number of items
			$number_of_items = get_post_meta( $post->ID, '_number_of_items', true );
			if ( ! empty( $number_of_items ) ) {
				echo '<div class="number-of-items">';
				echo '<label>' . __( 'Number of Items in Box: ', 'subs-box' ) . '</label>';
				echo '<span>' . esc_html( $number_of_items ) . '</span>';
				echo '<div class="quantity"><input type="number" id="item_quantity" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="'. esc_attr_x( 'Qty', 'Product quantity input tooltip', 'subs-box' ) .'" size="4" inputmode="numeric" /></div>';
				echo '</div>';
			}
		}
	}


	/**
	 * Add the Selected Subscription Frequency and Price to the Cart Item Data
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_add_frequency_option_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
		if ( isset( $_POST['frequency_option_val'] ) ) {
			$cart_item_data['frequency_option'] = sanitize_text_field( $_POST['frequency_option_val'] );
			$cart_item_data['frequency_price'] = sanitize_text_field( $_POST['frequency_price'] );
			$cart_item_data['_number_of_items'] = sanitize_text_field( $_POST['_number_of_items'] );
			$cart_item_data['quantity'] = sanitize_text_field( $_POST['_number_of_items'] );
			$cart_item_data['unique_key'] = md5( microtime().rand() ); // To avoid merging items
		}
		return $cart_item_data;
	}


	/**
	 * Adjust the Cart Item Price
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_set_custom_quantity_in_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		if ( isset( $cart_item_data['quantity'] ) ) {
			WC()->cart->set_quantity( $cart_item_key, $cart_item_data['quantity'], false );
		}
	}

	/**
	 * Adjust the Cart Item Price
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_set_custom_cart_item_price( $cart_object ) {
		foreach ( $cart_object->get_cart() as $cart_item_key => $cart_item ) {
			if ( isset( $cart_item['frequency_price'] ) ) {
				$cart_item['data']->set_price( $cart_item['frequency_price'] );
			}
			if ( isset( $cart_item['_number_of_items'] ) ) {
				$cart_item['data']->set_stock_quantity( $cart_item['_number_of_items'] );
			}
		}
	}



	/**
	 * Display the Subscription Frequency Option and Price in the Cart and Checkout
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_display_frequency_option_cart_item( $item_data, $cart_item ) {
		if ( isset( $cart_item['frequency_option'] ) ) {
			$item_data[] = array(
				'key'     => __( 'Frequency', 'subs-box' ),
				'value'   => wc_clean( $cart_item['frequency_option'] ),
				'display' => '',
			);
		}
		if ( isset( $cart_item['frequency_price'] ) ) {
			$item_data[] = array(
				'key'     => __( 'Frequency Price', 'subs-box' ),
				'value'   => wc_clean( $cart_item['frequency_price'] ),
				'display' => '',
			);
		}
		return $item_data;
	}

	/**
	 * Save the Subscription Frequency Option and Price to the Order Meta
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_save_frequency_option_order_item_meta( $item_id, $values, $cart_item_key ) {
		if ( isset( $values['frequency_option'] ) ) {
			wc_add_order_item_meta( $item_id, 'Frequency', $values['frequency_option'] );
		}
		if ( isset( $values['frequency_price'] ) ) {
			wc_add_order_item_meta( $item_id, 'Price', $values['frequency_price'] );
		}
	}

	/**
	 * Display the Subscription Frequency Option and Price in the Order Details
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_display_frequency_option_order_item_meta( $item_id, $item, $order, $plain_text ) {
		$frequency = wc_get_order_item_meta( $item_id, 'Frequency', true );
		if ( $frequency ) {
			echo '<p><strong>' . __( 'Frequency', 'subs-box' ) . ':</strong> ' . esc_html( $frequency ) . '</p>';
		}
		$frequency_price = wc_get_order_item_meta( $item_id, 'Price', true );
		if ( $frequency_price ) {
			echo '<p><strong>' . __( 'Price', 'subs-box' ) . ':</strong> ' . esc_html( $frequency_price ) . '</p>';
		}
	}

	/**
	 * Display the Add to cart button for custom product type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_add_to_cart() {
		// wc_get_template( 'single-product/add-to-cart/simple.php' );
		include SUBS_PUBLIC_PARTIALS."subs-box-public-single-product.php";
	}

	/**
	 * Reduce the product quantity on order completed status
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_reduce_custom_product_quantity( $order_id ) {
		// Get the order
		$order = wc_get_order( $order_id );

		// Loop through order items
		foreach ( $order->get_items() as $item_id => $item ) {
			// Get the product ID
			$product_id = $item->get_product_id();


			// Check if this is a custom product type
			$product = wc_get_product( $product_id );
			if($product->get_type()=="sub_box"){
				// Get the current quantity from the custom field
				$current_quantity = get_post_meta( $product_id, '_number_of_items', true );

				// Get the quantity purchased
				$purchased_quantity = $item->get_quantity();

				// Calculate the new quantity
				$new_quantity = $current_quantity - $purchased_quantity;
				// Update the quantity in the database
				update_post_meta( $product_id, '_number_of_items', max( 0, $new_quantity ) ); // Ensure it doesn't go below zero
			}
		}
	}

	/**
	 * Show the notice for low product quantity for purchase on single product page
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $subs_box_plugin_name
	 */
	function subs_box_validate_custom_product_quantity( $passed, $product_id, $quantity ) {
		$product = wc_get_product( $product_id );
			if($product->get_type()=="sub_box"){
			// Get the available quantity from the custom field
			$available_quantity = get_post_meta( $product_id, '_number_of_items', true );

			// Check if the requested quantity exceeds the available quantity
			if ( $quantity > $available_quantity ) {
				wc_add_notice( __( 'You cannot purchase more than the available quantity for this product.', 'subs-box' ), 'error' );
				return false;
			}
		}

		return $passed;
	}


}
