<?php
// don't load directly
//if ( ! defined( 'ABSPATH' ) ) {
 //   die( 'You shouldnt be here' );
//}
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class cls_xundart_participants extends WP_List_Table {

        function __construct() {

            global $status, $page;
                    
            //Set parent defaults
            parent::__construct( array (
                'singular'  => 'Processor Payment',  //singular name of the listed records
                'plural'    => 'Processor Payment', //plural name of the listed records
                'ajax'      => false        //does this table support ajax?
            ) );
        }   

        /**
         * Method for name column
         *
         * @param array $item an array of DB data
         *
         * @return string
         */
        function column_pp_name( $item ) {
            //http://localhost/glintex/wp-admin/admin.php?page=processor_payments_date&action=delete&customer=1&_wpnonce=500be82c96
            $delete_nonce = wp_create_nonce( 'delete_pp_record' );

            $title = $item['pp_name'];
            $title .= sprintf( '<input type="hidden" name="_bppnonce" value="%s" />', $delete_nonce );

            $actions = [
                'delete' => sprintf( '<a href="?page=%s&action=%s&pp_id=%s&_ppnonce=%s" %s >Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['pp_id'] ), $delete_nonce, 
                'onclick = "if (! confirm(\'Are you sure you want to delete this data?\')) { return false; }"')
            ];  

            return $title . $this->row_actions( $actions );
        }    

        function column_default($item, $column_name) {
            global $wpdb;
          
            switch($column_name) {
               	case 'pp_paymenturl':
               		return '<a href="'.$item[$column_name].'" target="_blank">'.$item[$column_name].'</a>';
                case 'pp_indate':
                    return date( "F j, Y, g:i a", strtotime($item[$column_name]) );
                default:                    
                    return $item[$column_name];
                    //Show the whole array for troubleshooting purposes
            }
        }

       

        /**************************************************************************
         * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
         * is given special treatment when columns are processed. It ALWAYS needs to
         * have it's own method.
         * 
         * @see WP_List_Table::::single_row_columns()
         * @param array $item A singular item (one full row's worth of data)
         * @return string Text to be placed inside the column <td> (movie title only)
         **************************************************************************/

        /**
         * Render the bulk edit checkbox
         *
         * @param array $item
         *
         * @return string
         */
        function column_cb( $item ) {
            return sprintf (
                '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['pp_id']
            );
        }

        /**************************************************************************
         * REQUIRED! This method dictates the table's columns and titles. This should
         * return an array where the key is the column slug (and class) and the value 
         * is the column's title text. If you need a checkbox for bulk actions, refer
         * to the $columns array below.
         * 
         * The 'cb' column is treated differently than the rest. If including a checkbox
         * column in your table you must create a column_cb() method. If you don't need
         * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
         * 
         * @see WP_List_Table::::single_row_columns()
         * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
         **************************************************************************/
        
        function get_columns() {
            $columns = array (
                'cb'       		=> '<input type="checkbox" />',
                'pp_name' 		=> 'Name',
                'pp_email' 		=> 'Email',
                'pp_coin'       => 'Coin Name',
                'pp_amount'     => 'Amount($)',
                'pp_paymenturl' => "Payment Url",
                'pp_indate' 	=> "Date"
            );
            return $columns;
        }

        /** ************************************************************************
         * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
         * you will need to register it here. This should return an array where the 
         * key is the column that needs to be sortable, and the value is db column to 
         * sort by. Often, the key and value will be the same, but this is not always
         * the case (as the value is a column name from the database, not the list table).
         * 
         * This method merely defines which columns should be sortable and makes them
         * clickable - it does not handle the actual sorting. You still need to detect
         * the ORDERBY and ORDER querystring variables within prepare_items() and sort
         * your data accordingly (usually by modifying your query).
         * 
         * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
         **************************************************************************/
        
        function get_sortable_columns() {
            $sortable_columns = array(
                //'xe_post_id'        => array('xe_post_id', false),
                //'xe_participant_ins_date' => array('xe_participant_ins_date', false)            
            );
            return $sortable_columns;
        }

        /** ************************************************************************
         * Optional. If you need to include bulk actions in your list table, this is
         * the place to define them. Bulk actions are an associative array in the format
         * 'slug'=>'Visible Title'
         * 
         * If this method returns an empty value, no bulk action will be rendered. If
         * you specify any bulk actions, the bulk actions box will be rendered with
         * the table automatically on display().
         * 
         * Also note that list tables are not automatically wrapped in <form> elements,
         * so you will need to create those manually in order for bulk actions to function.
         * 
         * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
         **************************************************************************/
        function get_bulk_actions() {
            $actions = array (
                'delete'    => 'Delete'
            );
            return $actions;
        }

        /** ************************************************************************
         * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
         * For this example package, we will handle it in the class to keep things
         * clean and organized.
         * 
         * @see $this->prepare_items()
         **************************************************************************/
        
        function process_bulk_action() {
            
            //Detect when a bulk action is being triggered...
            
            global $wpdb;
            global $wp;
            $tbl_pp = $wpdb->prefix.'processor_payment';

            if( 'delete'===$this->current_action() ) {

                if(isset($_REQUEST['_ppnonce'])) {

                    $nonce = esc_attr( $_REQUEST['_ppnonce'] );

                    if ( ! wp_verify_nonce( $nonce, 'delete_pp_record' ) ) {
                        die( __('Due to security issues operation not allowed.', 'wp-processor-payment').' <a href="'.add_query_arg( array( 'page' => 'processor_payments_data'),admin_url( 'admin.php' )   ).'">'.__('Click Here.', 'wp-processor-payment').'</a>' );
                    } else {

                        $wpdb->delete( $tbl_pp, array( 'pp_id' => $_REQUEST['pp_id'] ), array( '%d' ) );
                        
                        wp_safe_redirect( add_query_arg( array( 'page' => 'processor_payments_data', 
                            'delete' => 'true', 'meg' => 'parocessor payment record is deleted' ), 
                        admin_url( 'admin.php' ) ) );

                        exit();
                    }
                }

                if(isset($_POST['_bppnonce'])) {
                    
                    $nonce = esc_attr( $_POST['_bppnonce'] );

                    if ( ! wp_verify_nonce( $nonce, 'delete_pp_record' ) ) {
                        die( __('Due to security issues operation not allowed.', 'wp-processor-payment').' <a href="'.add_query_arg( array( 'page' => 'processor_payments_data'),admin_url( 'admin.php' )   ).'">'.__('Click Here.', 'wp-processor-payment').'</a>' );
                    } else {
                        if(isset($_POST['bulk-delete'])) 
                        {
                            $delete_ids = esc_sql( $_POST['bulk-delete'] );

                            // loop over the array of record IDs and delete them
                            foreach ( $delete_ids as $id ) {

                                $wpdb->delete ( 
                                    $tbl_pp, 
                                    array( 'pp_id' => $id ), 
                                    array( '%d' ) 
                                );

                            }
                        }
                        wp_safe_redirect( add_query_arg( array( 'page' => 'processor_payments_data', 
                            'delete' => 'true', 'meg' => 'parocessor payment records are deleted' ), 
                        admin_url( 'admin.php' ) ) );
                        exit;                       
                    }
                }
            }
        }

        public static function record_count() {
            global $wpdb;
            $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}processor_payment";
            return $wpdb->get_var( $sql );
        }

        public static function get_customers( $per_page = 5, $page_number = 1 ) 
        {
            global $wpdb;
            $sql = "SELECT * FROM {$wpdb->prefix}processor_payment";
            if ( ! empty( $_REQUEST['orderby'] ) ) {
                $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
                $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
            }
            $sql .= " LIMIT $per_page";
            $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
            $result = $wpdb->get_results( $sql, 'ARRAY_A' );
            return $result;
        }

        function prepare_items() {
            global $wpdb; //This is used only if making any database queries
            /**
             * First, lets decide how many records per page to show
             */
            $per_page = $this->get_items_per_page( 'customers_per_page', 5 );        
            $columns  = $this->get_columns();
            $hidden   = array();

            $sortable = $this->get_sortable_columns();       
            $this->_column_headers = array($columns, $hidden, $sortable);
            
            $this->process_bulk_action();
            $current_page = $this->get_pagenum();
            $total_items  = self::record_count();
            
            $this->set_pagination_args( array (
                'total_items' => $total_items,
                //WE have to calculate the total number of items
                'per_page' => $per_page,
                //WE have to determine how many items to show on a page
                'total_pages' => ceil($total_items/$per_page)
                //WE have to calculate the total number of pages
            ) );

            $this->items = self::get_customers( $per_page, $current_page );
        }
    }
    
    $ListTable = new cls_xundart_participants();    
    $ListTable->prepare_items();
    echo '<div class="wrap">';
    echo '<div id="icon-users" class="icon32"></div>';
    echo '<h2>Processor Payment Data</h2>'; 
    echo '<form method="post">'; 
    $ListTable->display();
    echo '</form>';
    echo '</div>';