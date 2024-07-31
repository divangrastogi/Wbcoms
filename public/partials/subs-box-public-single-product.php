<?php
/**
 * Custom Product Add to Cart Button
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
    return;
}
$frequency_options = get_post_meta( $post->ID, '_frequency_options', true );
if ( ! empty( $frequency_options ) ) {
    $options = explode( ',', $frequency_options );
    $frequency_option = $options[0];
}else{
    $frequency_option = "No Option Available";
}
$frequency_price_options = get_post_meta( $post->ID, '_frequency_price_options', true );
if ( ! empty( $frequency_price_options ) ) {
	$price_options = explode( ',', $frequency_price_options );
    $frequency_price_option = $price_options[0];
}else{
    $frequency_price_option = 00;
}
echo '<form class="cart" method="post" enctype="multipart/form-data">';
    do_action( 'woocommerce_before_add_to_cart_button' );
    echo '<input type="hidden" name="frequency_option_val" value="'.$frequency_option.'">';
    echo '<input type="hidden" name="frequency_price" value="'.$frequency_price_option.'">';
    echo '<input type="hidden" name="_number_of_items" value="1">';
    echo '<button type="submit" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '" class="single_add_to_cart_button button alt">' . esc_html( $product->single_add_to_cart_text() ) . '</button>';

    do_action( 'woocommerce_after_add_to_cart_button' );
echo '</form>';
?>
