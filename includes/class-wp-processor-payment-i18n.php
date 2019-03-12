<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://processorpayment.com/lendroid/
 * @since      1.0.0
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/includes
 * @author     mak <abansal2107@gmail.com >
 */
class Wp_Processor_Payment_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-processor-payment',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
