(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


	jQuery(document).ready(function($) {

 		// Hide the main product price if subscription prices are available
		if (other_data.hide_price) {
			$('.price').hide();
		}
		// Update the price display when the frequency option changes
		$('select[name="frequency_option"]').on('change', function() {
			var selectedFrequency = $(this).val();
			var price = frequencyPrices[selectedFrequency];
			if (price) {
				$('.subs-box-price-field').text(other_data.currency_symbol + parseFloat(price).toFixed(2));
				$('input[name="frequency_price"]').val(price);  // Hidden input to store the price
				$('input[name="frequency_option_val"]').val(selectedFrequency);
			}
		});

		$("#item_quantity").on('change', function() {
			$('input[name="_number_of_items"]').val($("#item_quantity").val());
		});

	});


})( jQuery );
