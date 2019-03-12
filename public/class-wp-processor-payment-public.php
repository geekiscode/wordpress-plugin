<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://processorpayment.com/lendroid/
 * @since      1.0.0
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/public
 * @author     mak <abansal2107@gmail.com >
 */
class Wp_Processor_Payment_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public $werr;

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
		 * defined in Wp_Processor_Payment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Processor_Payment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-processor-payment-public.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Processor_Payment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Processor_Payment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-processor-payment-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-parsley', plugin_dir_url( __FILE__ ) . 'js/parsley.min.js', array(), $this->version, 'all', true );

	}

	public function func_processor_payment_checkout_page($atts) {

		global $wpdb;
		global $current_user;
		global $post; 	

		// Params extraction
        $atts = shortcode_atts (
			array (
				'class' => 'ppcp',
				'title' => '12/1 - 1/31 Limited Time Offer. Purchase LST Tokens at USD $0.032 Fill in the form below and select the amount of LST you wish to purchase. You will be guided to the Bitcoin payment page.'
			), 
			$atts
		);		
		if(isset($_POST['btn_payment'])) {			
			echo '<div class="ppsc-error"><p>' . $this->werr . '</p></div>';			
		}

		$rtn_html = '<form class="'.$atts['class'].' intro-form" action="" id="get-info" method="POST" novalidate="novalidate" data-parsley-validate enctype="multipart/form-data">

            <input type="hidden" Namee="language" value="en">

            <h4>'.$atts['title'].'</h4>

            <div class="form-row-cont">
              	<div class="half-con">
                	<input name="pp_fname" id="fname" class="pp-fields" placeholder="First Name (Romaji input only)" type="text" value="'.((isset($_POST['pp_fname'])) ? $_POST['pp_fname'] : '').'" required>                
              	</div>
              	<div class="half-con">
                	<input name="pp_lname" id="lname" class="pp-fields" placeholder="Last Name (Romaji input only)" type="text" value="'.((isset($_POST['pp_lname'])) ? $_POST['pp_lname'] : '').'" required>
              	</div>
            </div>
          
            <div class="form-row-cont">
              	<div class="half-con">
                	<input name="pp_email" id="pp_email" class="pp-fields" 
                	placeholder="Email address" required data-parsley-type="email" 
                	value="'.((isset($_POST['pp_email'])) ? $_POST['pp_email'] : '').'">               
              	</div>
              	<div class="half-con">
                	<input name="confirmEmail" class="pp-fields" id="confirmEmail" placeholder="Confirm Email address" data-parsley-type="email" required data-parsley-equalto="#pp_email">               
              	</div>
            </div>
            
            <div class="form-row-cont">
            	<div class="half-con">
                	<input name="pp_mobile" id="mobile" class="pp-fields" placeholder="Mobile Phone" type="text" value="'.((isset($_POST['pp_mobile'])) ? $_POST['pp_mobile'] : '').'" required>
              	</div>
              	<div class="half-con">
                	<input name="pp_amount" id="amount" class="pp-fields" placeholder="Amount" type="text" required placeholder="0" min="0" max="20000" step="100" 
    					data-parsley-validation-threshold="1" data-parsley-trigger="keyup" 
    					data-parsley-type="number" 
    					value="'.((isset($_POST['pp_amount'])) ? $_POST['pp_amount'] : '').'">
              	</div>
            </div>
            <div class="form-row-cont">
              	<div class="full-con">
                	<input type="checkbox" name="pp_terms" id="terms" class="terms-condition" required> 
                	<span>I agree to the terms and conditions.</span>               
              	</div>
            </div>
      
            <div class="form-row-cont">
              	<div class="half-con">
                	<button type="submit" name="btn_payment" class="btn btn-success btn-lg">Submit Information</button>
              	</div>
            </div>

       	</form>';
		
		//$atts = shortcode_atts( array (
		//'title' => 'Xundart',
		//'user'  => 'xundart_cms_guest',
		//), array_filter($atts), 'xe-event-calendar' );
		
		return $rtn_html;
	}

	public function get_form_post_data() {

		global $wpdb;

		if(isset($_POST['btn_payment'])) {
			
			$paymentboxkey = '';
			$coinName = '';
			if(get_option( 'processor_payment_active_coin' )) {
				if(!empty(get_option( 'processor_payment_active_coin' ))) {
					$paymentboxkey = get_option( get_option( 'processor_payment_active_coin' ) );

					if(get_option( 'processor_payment_active_coin' )=='bitcoin_paymentboxkey_live') {
						$coinName = 'bitcoin';
					} else if(get_option( 'processor_payment_active_coin' )=='bitcoin_paymentboxkey_test') {
						$coinName = 'bitcoin';
					} else if(get_option( 'processor_payment_active_coin' )=='zozocoin_paymentboxkey_live') {
						$coinName = 'zozocoin';
					} else if(get_option( 'processor_payment_active_coin' )=='zozocoin_paymentboxkey_test') {
						$coinName = 'zozocoin';
					}
				}
			}
			
			$data = array (
			    "name" 		=> $_POST['pp_fname'].' '.$_POST['pp_lname'], 
			    "email" 	=> $_POST['pp_email'],
			    "mobile" 	=> $_POST['pp_mobile'],
			    "amount" 	=> $_POST['pp_amount'],
			    "language" 	=> get_option( 'processor_payment_language' ),
			    "terms" 	=> 'YES',
			    "coinName" 	=> $coinName,
			    "paymentboxKey" => $paymentboxkey,
			    "secretKey" 	=> get_option( 'customer_secretkey' ),
			    "redirectUrl" 	=> get_option( 'processor_payment_redirectUrl' ),
			    "mailTemplate" 	=> get_option( 'processor_payment_mailtemplate' ),
			    "description" 	=> 'Description Text'
			);
			 			
			$json_data = json_encode($data);

			$ch = curl_init( 'http://processorpayment.com:9000/api/paymentapi/payment/' );
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER,
			   	array (
			    	'Content-Type: application/json',
			      	'Content-Length: ' . strlen($json_data)
			   	)
			);

			$response = curl_exec($ch);
			
			if ($response === false) {
			   echo 'Curl error: ' . curl_error($ch);
			} else {
			   	$jsonResponse = json_decode($response, true);			  	
			   	if(array_key_exists( 'data', $jsonResponse) && array_key_exists( 'payment_url', $jsonResponse['data'] ) ) {

			   		$tbl_pp = $wpdb->prefix.'processor_payment';
			    	$paymentURL = $jsonResponse['data']['payment_url'];

			   		$result = $wpdb->insert(
						$tbl_pp, 
						array (
							'pp_name'       => $_POST['pp_fname'].' '.$_POST['pp_lname'], 
							'pp_email'      => $_POST['pp_email'],
							'pp_mobile'     => $_POST['pp_mobile'],
							'pp_amount'     => number_format($_POST['pp_amount'], 2),
							'pp_language'   => get_option( 'processor_payment_language' ),
							'pp_coin'       => $coinName,
							'pp_paymenturl' => $paymentURL,
							'pp_mode'       => get_option( 'processor_payment_active_coin' ),
							'pp_indate'     => date("Y-m-d H:i:s")
						),
						array (
							'%s',
							'%s',
							'%s',
							'%d',
							'%s',
							'%s',
							'%s',
							'%s',
							'%s' 
						) 
					);
					
					//$wpdb->show_errors();
					//$wpdb->print_error();

					if($result) {
						$to = $_POST['pp_email'];
						$subject = 'Notification: Processor Payment';
						$body = get_option( 'processor_payment_mailtemplate' );
						if(!empty($body)) {
							
							$body = ereg_replace('{{name}}', $info['name'], $body);
							$body = ereg_replace('{{paymentlink}}', $info['name'], $body);
							$headers = array('Content-Type: text/html; charset=UTF-8');
							wp_mail( $to, $subject, $body, $headers );
							
						}
					}
			   		
			      	header('Access-Control-Allow-Origin: *');
			      	header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
			      	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
			      	header('Location: ' . $paymentURL);
			      	exit;
			   	} else {
			      	if(array_key_exists('message', $jsonResponse) && array_key_exists('status', $jsonResponse))
			      	{
			        	//echo($jsonResponse['message']." Please Check the Credentials");
			        	$this->werr = __( $jsonResponse['message']." Please Check the Credentials", 
			        		'wp-processor-payment' );
			        	//print_r( $werr->errors );
			         	//exit;

			      	}

			   	}
			}
			curl_close($ch);			
		}	
	}
}