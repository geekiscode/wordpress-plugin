<?php

/**
 * Fired during plugin activation
 *
 * @link       http://processorpayment.com/lendroid/
 * @since      1.0.0
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/includes
 * @author     mak <abansal2107@gmail.com >
 */
class Wp_Processor_Payment_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		$sql = array();

		$charset_collate = $wpdb->get_charset_collate();
		$tbl_pp = $wpdb->prefix.'processor_payment';	

	    $query = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $tbl_pp ) );
	    if ( $wpdb->get_var( $query ) != $tbl_pp ) {	    	

			$sql[0] = "CREATE TABLE IF NOT EXISTS $tbl_pp (
				pp_id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
			  	pp_name varchar(100) NOT NULL,
			  	pp_email varchar(100) NOT NULL,
			  	pp_amount float NOT NULL,
			  	pp_mobile varchar(100) NOT NULL,
			  	pp_language varchar(10) NOT NULL,
			  	pp_coin varchar(10) NOT NULL,
			  	pp_paymenturl varchar(100) NOT NULL,
			  	pp_mode varchar(10) NOT NULL,
			  	pp_indate datetime NOT NULL,
			  	PRIMARY KEY (pp_id)
			) $charset_collate;";
	    }

   		if ( !empty($sql) ) {
	    	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql[0] );			
		}
	}

}
