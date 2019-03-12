<?php
// don't load directly
//if ( ! defined( 'ABSPATH' ) ) {
//    die( 'You shouldnt be here' );
//}

/**
* Function when plugin is activated
*
* @param void
*
* @return void
*/
/*
Element Description: VC Info Box
*/
 
// Element Class 
class vcppBox extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_ppbox_mapping' ) );
        add_shortcode( 'vc_pp_form', array( $this, 'vc_ppbox_html' ) );
    }
     
    // Element Mapping
    public function vc_ppbox_mapping() {
         
        // Stop all if VC is not enabled
        if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
        }
         
        // Map the block with vc_map()
        vc_map ( 
            array (
                'name'        => __('Processor Payment Form', 'wp-processor-payment' ),
                'base'        => 'vc_pp_form',
                'description' => __('VC Processor Payment Form', 'wp-processor-payment' ), 
                'category'    => __('Payment', 'wp-processor-payment' ),   
                'icon'        => get_template_directory_uri().'/assets/img/vc-icon.png',
                'show_settings_on_create' => false,
                'params'      => array (                         
                    array (
                        'type'        => 'textfield',
                        'holder'      => 'h3',
                        'class'       => 'title-class',
                        'heading'     => __( 'Title', 'wp-processor-payment' ),
                        'param_name'  => 'title',
                        'value'       => __( 'Default value', 'wp-processor-payment' ),
                        'description' => __( 'Processor Payment Title', 'wp-processor-payment' ),
                        'admin_label' => false,
                        'weight'      => 0,
                        'group'       => 'Custom Group',
                    ),
                    array (
                        'type'        => 'textfield',
                        'holder'      => '',
                        'class'       => '',
                        'heading'     => __( 'Custom Class', 'wp-processor-payment' ),
                        'param_name'  => 'class',
                        'value'       => __( '', 'wp-processor-payment' ),
                        //'description' => __( 'Container Custom Class', 'wp-processor-payment' ),
                        'admin_label' => true,
                        'weight'      => 0,
                        'group'       => 'Custom Group',
                    ), 
                    /*array (
                        'type' => 'css_editor',
                        'heading' => __( 'Css', 'my-text-domain' ),
                        'param_name' => 'css',
                        'group' => __( 'Design options', 'my-text-domain' ),
                    ),*/
                )
            )
        );                                
        
    }
     
     
    // Element HTML
    public function vc_ppbox_html( $atts ) {
         
        // Params extraction
        extract(
            shortcode_atts (
                array (
                    'title' => '',
                    'class' => ''                  
                ), 
                $atts
            )
        );
        
        // Fill $html var with data
        $html = '<div class="vc-infobox-wrap">';
        if(!empty($title)) {
            $html .= '<div class="vc-infobox-text">'.do_shortcode('[processor_payment_checkout_page title="'.$title.'" class="'.$class.'"]').'</div>';
        } else {
            $html .= '<div class="vc-infobox-text">'.do_shortcode('[processor_payment_checkout_page class="'.$class.'"]').'</div>';
        }
        $html .= '</div>';      
         
        return $html;
         
    }
     
} // End Element Class
 
 
// Element Class Init
new vcppBox();    