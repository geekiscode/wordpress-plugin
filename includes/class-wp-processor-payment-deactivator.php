<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://processorpayment.com/lendroid/
 * @since      1.0.0
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/includes
 * @author     mak <abansal2107@gmail.com >
 */
class Wp_Processor_Payment_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// if uninstall.php is not called by WordPress, die
	
		global $wpdb;
	    $tbl_pp = $wpdb->prefix . 'processor_payment';
	    $sql = "TRUNCATE TABLE $tbl_pp";
	    $wpdb->query($sql);

	    delete_option( 'customer_secretkey' );

		delete_option( 'bitcoin_secretkey' );
		delete_option( 'zozocoin_secretkey' );

		delete_option( 'zozocoin_paymentboxkey_live' );
		delete_option( 'bitcoin_paymentboxkey_live' );

		delete_option( 'zozocoin_paymentboxkey_test' );
		delete_option( 'bitcoin_paymentboxkey_test' );

		delete_option( 'processor_payment_redirectUrl' );

		delete_option( 'processor_payment_active_coin' );

		delete_option( 'processor_payment_mailtemplate' );
		delete_option( 'processor_payment_mode' );		

		delete_option( 'processor_payment_checkout_page' );		
		delete_option( 'processor_payment_language' );	

		//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		//dbDelta( $sql );		
	}
}