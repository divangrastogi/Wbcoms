<?php
  /**
	 * Class for the custom product type
	 *
	 * @since    1.0.0
	 */
 class WC_Product_Subsbox extends WC_Product {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
    public function __construct( $sub_box_product ) {
        $this->product_type = 'sub_box';
	      parent::__construct( $sub_box_product );
    }


	/**
	 * For creating the cart url of the product
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
    public function add_to_cart_url() {
      $subs_box_url = $this->is_purchasable() && $this->is_in_stock() ? remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $this->id ) ) : get_permalink( $this->id );
      return apply_filters( 'woocommerce_product_add_to_cart_url', $subs_box_url, $this );
  }

  /**
	 * Make sure the product is purchasable
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
  public function is_purchasable() {
    return true;
  }

  }
