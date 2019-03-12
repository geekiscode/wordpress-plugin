<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://processorpayment.com/lendroid/
 * @since      1.0.0
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Processor_Payment
 * @subpackage Wp_Processor_Payment/admin
 * @author     mak <abansal2107@gmail.com >
 */
class Wp_Processor_Payment_Admin {

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
		 * defined in Wp_Processor_Payment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Processor_Payment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-processor-payment-admin.css', array(), $this->version, 'all' );

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
		 * defined in Wp_Processor_Payment_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Processor_Payment_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-processor-payment-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name.'-tinymce', plugin_dir_url( __FILE__ ) . 'js/tinymce/tinymce.min.js', array( 'jquery' ), $this->version, false );		

	}

	function prfx_add_custom_post_states($states) {
	    global $post;

	    // get saved project page ID
	    $project_page_id = get_option('processor_payment_checkout_page');

	    // add our custom state after the post title only,
	    // if post-type is "page",
	    // "$post->ID" matches the "$project_page_id",
	    // and "$project_page_id" is not "0"
	    if( 'page' == get_post_type($post->ID) && $post->ID == $project_page_id && $project_page_id != '0') {
	        $states[] = __('Processor Payment Checkout Page', 'wp-processor-payment');
	    }

	    return $states;
	}

	public function set_custom_page_init() {
				
		// This page will be under "Settings"
       	//add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
       	add_menu_page (
       		__( 'Processor Payment', 'wp-processor-payment' ),
	        'Processor Payment',
	        'manage_options',
	        'processor-payment-page',
	        array( $this, 'func_processor_payment_page' ),
	        plugins_url( 'wp-processor-payment/admin/images/mern-favicon-circle-fill.png' ),
        	30
        );
        register_setting( 'processor_payment_setting', 'customer_secretkey' );

		//register_setting( 'processor_payment_setting', 'bitcoin_secretkey' );
		//register_setting( 'processor_payment_setting', 'zozocoin_secretkey' );

		register_setting( 'processor_payment_setting', 'zozocoin_paymentboxkey_live' );
		register_setting( 'processor_payment_setting', 'bitcoin_paymentboxkey_live' );

		register_setting( 'processor_payment_setting', 'zozocoin_paymentboxkey_test' );
		register_setting( 'processor_payment_setting', 'bitcoin_paymentboxkey_test' );

		register_setting( 'processor_payment_setting', 'processor_payment_redirectUrl' );

		register_setting( 'processor_payment_setting', 'processor_payment_active_coin' );

		register_setting( 'processor_payment_setting', 'processor_payment_mailtemplate' );
		register_setting( 'processor_payment_setting', 'processor_payment_mode' );		

		register_setting( 'processor_payment_setting', 'processor_payment_checkout_page' );		
		register_setting( 'processor_payment_setting', 'processor_payment_language' );	
		
		try {
			require_once plugin_dir_path(dirname(__FILE__)).'admin/class-wp-processor-payment-widget.php';	
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function vc_before_init_actions(){
		// checking if visual composer is active
		if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
			try {
				require_once plugin_dir_path(dirname(__FILE__)).'admin/class-wp-processor-payment-vc.php';	
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
	   	} 
	}

	// Register the widget
	public function register_pp_widget() {
		register_widget( 'processor_payment_widget' );
	}

	public function add_admin_sub_menu() {
		add_submenu_page( 'processor-payment-page', 'Processor Payments Data', 'Processor Payments Data', 'manage_options', 'processor_payments_data', array($this, 'fun_show_processor_payments_data') );
	}

	public function fun_show_processor_payments_data() {
		try {
			require_once plugin_dir_path(dirname(__FILE__)).'admin/class-wp-processor-payment-datatable.php';
		} catch (Exception $e) {
		    echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}

	public function func_processor_payment_page() {

		echo '<form method="post" action="options.php">';

		settings_fields( 'processor_payment_setting' );
		do_settings_sections( 'processor_payment_setting' );
		$bitcoin = 'checked="checked"';
		$zozocoin = '';
		if(!empty( get_option('processor_payment_active_coin') )) {
			if( get_option('processor_payment_active_coin') == 'bitcoin') {
				$bitcoin = 'checked="checked"';
			} else {
				$zozocoin = 'checked="checked"';
			}
		}
		echo '<div class="wrap processor_payment_setting">
			<script type="text/javascript">
				tinymce.init({
			    	selector: \'#txt_processor_payment_mailtemplate\',
			    	branding: false,
			    	min_width: 500,
			    	width : 500,
			    	theme: \'modern\',
					plugins: \'code print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help\',
					toolbar1: \'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat | preview | code\',
					image_advtab: true,
					templates: [
					    { title: \'Test template 1\', content: \'Test 1\' },					    
					],
					content_css: [
					    \'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i\',
					    \'//www.tinymce.com/css/codepen.min.css\'
					]					
			  	});
			</script>

			<h1> '.__( 'Processor Payment', 'wp-processor-payment' ).'</h1>

			<table class="form-table">

				<tr valign="top">
			 		<th scope="row"><label for="customer_secretkey"><h2 class="title">Customer Key</h2></label></th>
			 		<td>
			 			<input type="text" id="customer_secretkey" name="customer_secretkey" class="regular-text code" value="'.get_option('customer_secretkey').'" >
			 		</td>
			 	</tr>

			 	<tr valign="top">
			 		<th scope="row"><label><h2 class="title">Payment Box Keys</h2></label></th>
			 		<td></td>
			 	</tr>
		        <tr valign="top">
			        <td scope="row">
			        	<label for="processor_payment_active_bitcoin_live">
			        	<input type="radio" id="processor_payment_active_bitcoin_live" 
			 			name="processor_payment_active_coin" value="bitcoin_paymentboxkey_live"
			 			'.((get_option('processor_payment_active_coin') == 'bitcoin_paymentboxkey_live') ? 'checked="checked"' : '').'>
			 			<span>'.__('Bitcoin (Live)', 'wp-processor-payment' ).'</span>
			 			</label>
			 		</td>
			        <td>
				   		<input id="txt_bitcoin_paymentboxkey" name="bitcoin_paymentboxkey_live" type="text"
				   		 class="regular-text code" value="'.get_option('bitcoin_paymentboxkey_live').'" 
				   		 autocomplete="off" />
					</td>
		        </tr>
		       	<tr valign="top">
			        <td scope="row">
			        	<label for="processor_payment_active_bitcoin_test">
			        		<input type="radio" id="processor_payment_active_bitcoin_test" 
			 				name="processor_payment_active_coin" value="bitcoin_paymentboxkey_test"
			 				'.((get_option('processor_payment_active_coin') == 'bitcoin_paymentboxkey_test') ? 'checked="checked"' : '').' >
			 				<span>'.__('Bitcoin (Test)', 'wp-processor-payment' ).'</span>
			 			</label>
			 		</td>
			        <td>
				   		<input id="txt_bitcoin_paymentboxkey" name="bitcoin_paymentboxkey_test" type="text"
				   		 class="regular-text code" value="'.get_option('bitcoin_paymentboxkey_test').'" 
				   		 autocomplete="off" />
					</td>	
		        </tr>
		        <tr valign="top">
			        <td scope="row">
			        	<label for="processor_payment_active_zozocoin_live">
			        		<input type="radio" id="processor_payment_active_zozocoin_live" 
			 				name="processor_payment_active_coin" value="zozocoin_paymentboxkey_live"
			 				'.((get_option('processor_payment_active_coin') == 'zozocoin_paymentboxkey_live') ? 'checked="checked"' : '').' >
			 				<span>'.__('Zozocoin (Live)', 'wp-processor-payment' ).'</span>
			 			</label>
			 		</td>
			        <td>
				   		<input id="txt_bitcoin_paymentboxkey" name="zozocoin_paymentboxkey_live" type="text"
				   		 class="regular-text code" value="'.get_option('zozocoin_paymentboxkey_live').'" 
				   		 autocomplete="off" />
					</td>
		        </tr>
		        <tr valign="top">
			        <td scope="row">
			        	<label for="processor_payment_active_zozocoin_test">
			        		<input type="radio" id="processor_payment_active_zozocoin_test" 
			 				name="processor_payment_active_coin" value="zozocoin_paymentboxkey_test" 
			 				'.((get_option('processor_payment_active_coin') == 'zozocoin_paymentboxkey_test') ? 'checked="checked"' : '').' >
			 				<span>'.__('Zozocoin (Test)', 'wp-processor-payment' ).'</span>
			 			</label>
			 		</td>
			        <td>
				   		<input id="txt_bitcoin_paymentboxkey" name="zozocoin_paymentboxkey_test" type="text"
				   		 class="regular-text code" value="'.get_option('zozocoin_paymentboxkey_test').'" 
				   		 autocomplete="off" />
					</td>
		        </tr>
	        </table>	       

	        <table class="form-table">
		        <tr valign="top">
			        <th scope="row">'.__('Redirect Url Key', 'wp-processor-payment' ).'</th>
			        <td>
				   		<input id="txt_processor_paymen_redirectUrl" name="processor_payment_redirectUrl" 
				   		type="url" class="regular-text code" 
				   		value="'.get_option('processor_payment_redirectUrl').'" 
				   		autocomplete="off" />
					</td>
		        </tr>
		        <tr valign="top">
			        <th scope="row">'.__('Mail Template', 'wp-processor-payment' ).'</th>
			        <td>
				   		<textarea id="txt_processor_payment_mailtemplate" 
				   		name="processor_payment_mailtemplate" 
				   		type="text" 
				   		class="regular-text code" 
				   		style="height: 160px;"
				   		autocomplete="off">'.get_option('processor_payment_mailtemplate').'</textarea>
				   		<br>
				   		<i>{{name}}</i>
				   		<i>{{paymentlink}}</i>
					</td>
		        </tr>
		        <tr valign="top" style="display: none;">
			        <th scope="row">'.__('Payment Mode', 'wp-processor-payment' ).'</th>
			        <td>
				   		<label for="btn_processor_payment_mode_test">
				   			<input type="radio" id="btn_processor_payment_mode_test" 
			 				name="processor_payment_mode" value="test"
			 				'.((get_option('processor_payment_mode') == 'test') ? 'checked="checked"' : '').' /><span>Test</span>
				   		</label>&nbsp;&nbsp;&nbsp;
				   		<label for="btn_processor_payment_mode_live">
				   			<input type="radio" id="btn_processor_payment_mode_live" 
			 				name="processor_payment_mode" value="live" 
			 				'.((get_option('processor_payment_mode') == 'live') ? 'checked="checked"' : '').'
			 				/><span>Live</span>
				   		</label>
					</td>
		        </tr>
		        <tr valign="top">
			        <th scope="row">'.__('language', 'wp-processor-payment' ).'</th>
			        <td>
				   		<select data-placeholder="Choose a Language..." name="processor_payment_language">
						  	
						  	<option '.((get_option('processor_payment_language') == 'AF') ? 'selected="selected"' : '').' value="AF">Afrikanns</option>
						  	<option '.((get_option('processor_payment_language') == 'SQ') ? 'selected="selected"' : '').' value="SQ">Albanian</option>
						  	<option '.((get_option('processor_payment_language') == 'AR') ? 'selected="selected"' : '').' value="AR">Arabic</option>
						  	<option '.((get_option('processor_payment_language') == 'HY') ? 'selected="selected"' : '').' value="HY">Armenian</option>
						  	<option '.((get_option('processor_payment_language') == 'EU') ? 'selected="selected"' : '').' value="EU">Basque</option>
						  	<option '.((get_option('processor_payment_language') == 'BN') ? 'selected="selected"' : '').' value="BN">Bengali</option>
						  	<option '.((get_option('processor_payment_language') == 'BG') ? 'selected="selected"' : '').' value="BG">Bulgarian</option>
						  	<option '.((get_option('processor_payment_language') == 'CA') ? 'selected="selected"' : '').' value="CA">Catalan</option>
						  	<option '.((get_option('processor_payment_language') == 'KM') ? 'selected="selected"' : '').' value="KM">Cambodian</option>
						  	<option '.((get_option('processor_payment_language') == 'ZH') ? 'selected="selected"' : '').' value="ZH">Chinese (Mandarin)</option>
						  	<option '.((get_option('processor_payment_language') == 'HR') ? 'selected="selected"' : '').' value="HR">Croation</option>
						  	<option '.((get_option('processor_payment_language') == 'CS') ? 'selected="selected"' : '').' value="CS">Czech</option>
						  	<option '.((get_option('processor_payment_language') == 'DA') ? 'selected="selected"' : '').' value="DA">Danish</option>
						  	<option '.((get_option('processor_payment_language') == 'NL') ? 'selected="selected"' : '').' value="NL">Dutch</option>
						  	<option '.((get_option('processor_payment_language') == 'EN') ? 'selected="selected"' : '').' value="EN">English</option>
						  	<option '.((get_option('processor_payment_language') == 'ET') ? 'selected="selected"' : '').' value="ET">Estonian</option>
						  	<option '.((get_option('processor_payment_language') == 'FJ') ? 'selected="selected"' : '').' value="FJ">Fiji</option>
						  	<option '.((get_option('processor_payment_language') == 'FI') ? 'selected="selected"' : '').' value="FI">Finnish</option>
						  	<option '.((get_option('processor_payment_language') == 'FR') ? 'selected="selected"' : '').' value="FR">French</option>
						  	<option '.((get_option('processor_payment_language') == 'KA') ? 'selected="selected"' : '').' value="KA">Georgian</option>
						  	<option '.((get_option('processor_payment_language') == 'DE') ? 'selected="selected"' : '').' value="DE">German</option>
						  	<option '.((get_option('processor_payment_language') == 'EL') ? 'selected="selected"' : '').' value="EL">Greek</option>
						  	<option '.((get_option('processor_payment_language') == 'GU') ? 'selected="selected"' : '').' value="GU">Gujarati</option>
						  	<option '.((get_option('processor_payment_language') == 'HE') ? 'selected="selected"' : '').' value="HE">Hebrew</option>
						  	<option '.((get_option('processor_payment_language') == 'HI') ? 'selected="selected"' : '').' value="HI">Hindi</option>
						  	<option '.((get_option('processor_payment_language') == 'HU') ? 'selected="selected"' : '').' value="HU">Hungarian</option>
						  	<option '.((get_option('processor_payment_language') == 'IS') ? 'selected="selected"' : '').' value="IS">Icelandic</option>
						  	<option '.((get_option('processor_payment_language') == 'ID') ? 'selected="selected"' : '').' value="ID">Indonesian</option>
						  	<option '.((get_option('processor_payment_language') == 'GA') ? 'selected="selected"' : '').' value="GA">Irish</option>
						  	<option '.((get_option('processor_payment_language') == 'IT') ? 'selected="selected"' : '').' value="IT">Italian</option>
						  	<option '.((get_option('processor_payment_language') == 'JA') ? 'selected="selected"' : '').' value="JA">Japanese</option>
						  	<option '.((get_option('processor_payment_language') == 'JW') ? 'selected="selected"' : '').' value="JW">Javanese</option>
						  	<option '.((get_option('processor_payment_language') == 'KO') ? 'selected="selected"' : '').' value="KO">Korean</option>
						  	<option '.((get_option('processor_payment_language') == 'LA') ? 'selected="selected"' : '').' value="LA">Latin</option>
						  	<option '.((get_option('processor_payment_language') == 'LV') ? 'selected="selected"' : '').' value="LV">Latvian</option>
						  	<option '.((get_option('processor_payment_language') == 'LT') ? 'selected="selected"' : '').' value="LT">Lithuanian</option>
						  	<option '.((get_option('processor_payment_language') == 'MK') ? 'selected="selected"' : '').' value="MK">Macedonian</option>
						  	<option '.((get_option('processor_payment_language') == 'MS') ? 'selected="selected"' : '').' value="MS">Malay</option>
						  	<option '.((get_option('processor_payment_language') == 'ML') ? 'selected="selected"' : '').' value="ML">Malayalam</option>
						  	<option '.((get_option('processor_payment_language') == 'MT') ? 'selected="selected"' : '').' value="MT">Maltese</option>
						  	<option '.((get_option('processor_payment_language') == 'MI') ? 'selected="selected"' : '').' value="MI">Maori</option>
						  	<option '.((get_option('processor_payment_language') == 'MR') ? 'selected="selected"' : '').' value="MR">Marathi</option>
						  	<option '.((get_option('processor_payment_language') == 'MN') ? 'selected="selected"' : '').' value="MN">Mongolian</option>
						  	<option '.((get_option('processor_payment_language') == 'NE') ? 'selected="selected"' : '').' value="NE">Nepali</option>
						  	<option '.((get_option('processor_payment_language') == 'NO') ? 'selected="selected"' : '').' value="NO">Norwegian</option>
						  	<option '.((get_option('processor_payment_language') == 'FA') ? 'selected="selected"' : '').' value="FA">Persian</option>
						  	<option '.((get_option('processor_payment_language') == 'PL') ? 'selected="selected"' : '').' value="PL">Polish</option>
						  	<option '.((get_option('processor_payment_language') == 'PT') ? 'selected="selected"' : '').' value="PT">Portuguese</option>
						  	<option '.((get_option('processor_payment_language') == 'PA') ? 'selected="selected"' : '').' value="PA">Punjabi</option>
						  	<option '.((get_option('processor_payment_language') == 'QU') ? 'selected="selected"' : '').' value="QU">Quechua</option>
						  	<option '.((get_option('processor_payment_language') == 'RO') ? 'selected="selected"' : '').' value="RO">Romanian</option>
						  	<option '.((get_option('processor_payment_language') == 'RU') ? 'selected="selected"' : '').' value="RU">Russian</option>
						  	<option '.((get_option('processor_payment_language') == 'SM') ? 'selected="selected"' : '').' value="SM">Samoan</option>
						  	<option '.((get_option('processor_payment_language') == 'SR') ? 'selected="selected"' : '').' value="SR">Serbian</option>
						  	<option '.((get_option('processor_payment_language') == 'SK') ? 'selected="selected"' : '').' value="SK">Slovak</option>
						  	<option '.((get_option('processor_payment_language') == 'SL') ? 'selected="selected"' : '').' value="SL">Slovenian</option>
						  	<option '.((get_option('processor_payment_language') == 'ES') ? 'selected="selected"' : '').' value="ES">Spanish</option>
						  	<option '.((get_option('processor_payment_language') == 'SW') ? 'selected="selected"' : '').' value="SW">Swahili</option>
						  	<option '.((get_option('processor_payment_language') == 'SV') ? 'selected="selected"' : '').' value="SV">Swedish </option>
						  	<option '.((get_option('processor_payment_language') == 'TV') ? 'selected="selected"' : '').' value="TA">Tamil</option>
						  	<option '.((get_option('processor_payment_language') == 'TT') ? 'selected="selected"' : '').' value="TT">Tatar</option>
						  	<option '.((get_option('processor_payment_language') == 'TE') ? 'selected="selected"' : '').' value="TE">Telugu</option>
						  	<option '.((get_option('processor_payment_language') == 'TH') ? 'selected="selected"' : '').' value="TH">Thai</option>
						  	<option '.((get_option('processor_payment_language') == 'BO') ? 'selected="selected"' : '').' value="BO">Tibetan</option>
						  	<option '.((get_option('processor_payment_language') == 'TO') ? 'selected="selected"' : '').' value="TO">Tonga</option>
						  	<option '.((get_option('processor_payment_language') == 'TR') ? 'selected="selected"' : '').' value="TR">Turkish</option>
						  	<option '.((get_option('processor_payment_language') == 'UK') ? 'selected="selected"' : '').' value="UK">Ukranian</option>
						  	<option '.((get_option('processor_payment_language') == 'UR') ? 'selected="selected"' : '').' value="UR">Urdu</option>
						  	<option '.((get_option('processor_payment_language') == 'UZ') ? 'selected="selected"' : '').' value="UZ">Uzbek</option>
						  	<option '.((get_option('processor_payment_language') == 'VI') ? 'selected="selected"' : '').' value="VI">Vietnamese</option>
						  	<option '.((get_option('processor_payment_language') == 'CY') ? 'selected="selected"' : '').' value="CY">Welsh</option>
							<option '.((get_option('processor_payment_language') == 'XH') ? 'selected="selected"' : '').' value="XH">Xhosa</option>
						</select>	
					</td>
		        </tr>
		        <tr valign="top">
			        <th scope="row">'.__('Checkout Page', 'wp-processor-payment' ).'</th>
			        <td>
				   		<select name="processor_payment_checkout_page">';
				
						$arg = array (
							'post_type' => 'page',
							'post_status' => 'publish'
						);

					  	$pages = get_pages($arg); 

					  	foreach ( $pages as $page ) {

					  		if( get_option('processor_payment_checkout_page') == $page->ID ) {
						  		$option = '<option value="' . $page->ID  . '" selected="selected">';
								$option .= $page->post_title;
								$option .= '</option>';
							} else {
								$option = '<option value="' . $page->ID  . '">';
								$option .= $page->post_title;
								$option .= '</option>';
							}
							echo $option;
							
					  	}
			
					echo '</select><br>
					<i>'.__('Please select the page for checkout page. Please add the shortcode 
					<pre>[processor_payment_checkout_page]</pre> in page.', 'wp-processor-payment' ).' </i>
					</td>
		        </tr>
	        </table>

			<div class="clear"></div>
			
		</div>';
		echo submit_button();
		echo '</form>';
	}
}