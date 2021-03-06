<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Gens_RAF
 * @subpackage Gens_RAF/admin
 * @author     Your Name <email@example.com>
 */
class RAF_List_Table extends WP_List_Table {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $gens_raf       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct() {
		parent::__construct( array(
			'singular'=> 'referral',
			'plural' => 'referrals',
			'ajax'   => false
		) );
    }

    function extra_tablenav( $which ) {
	   if ( $which == "top" ){
	      //The code that goes before the table is here
//	      echo"Hello, I'm before the table";
	   }
	   if ( $which == "bottom" ){
	      //The code that goes after the table is there
//	      echo"Hi, I'm after the table";
	   }
	}

	function get_columns() {
	   return $columns= array(
	   		'status' => __('Status','gens-raf'),
			'ID'=>__('RAF Order'),
			'referrer'=>__('Referred by:','gens-raf'),
			'date'=>__('Date','gens-raf'),
			'total' => __('Total','gens-raf')
	   );
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function get_customers() {
		$args = array(
		    'meta_query'  => array(
		    	array(
		    		'key' => '_raf_id'
		    	)
		    ),
		    'post_type'   => wc_get_order_types(),
		    'post_status' => array_keys( wc_get_order_statuses() ),
		);

		$orders = new WP_Query($args);

	    return $orders->get_posts();
	}

	public function no_items() {
	    _e( 'No Referrals found yet.', 'gens-raf' );
	}

	function column_default($item, $column_name){
		$order = new WC_Order($item->ID);

		switch ( $column_name ) {
			case 'status':
				return $order->get_status();
				break;
			case 'ID':
				$user = $order->get_user();
				$return = "<a href='".get_edit_post_link( $item->ID )."'>#".$item->ID."</a> by ";
				$return .= "<a href='".get_edit_user_link( $user->ID )."'>".$user->user_login."</a><br/>";
				$return .= "<a href='mailto:".$user->user_email."'>".$user->user_email."</a>";
				return $return;
				break; 
			case 'referrer':
				$referralID = get_post_meta( $order->id, '_raf_id', true );
				if (!empty($referralID)) {
					$args = array('meta_key' => "gens_referral_id", 'meta_value' => $referralID );
					$user = get_users($args);
					return "<a href='".get_edit_user_link( $user[0]->ID )."'>".$user[0]->user_email."</a>";					
				}
				break; 
			case 'date':
				return $order->order_date;
			case 'total':
				return $order->get_formatted_order_total();
			default:
				print_r($column_name);
		}

	}

	function prepare_items() {

		$posts = $this->get_customers();

		$per_page = 10;
		$current_page = $this->get_pagenum();
		$total_items = count($posts);

	    $columns = $this->get_columns();
	    $hidden = array();
	    $sortable = $this->get_sortable_columns();
	    $this->_column_headers = array($columns, $hidden, $sortable);
	    $this->process_bulk_action();
		
		$posts = array_slice($posts,(($current_page-1)*$per_page),$per_page);

	    $this->set_pagination_args( array (
	        'total_items' => $total_items,
	        'per_page'    => $per_page,
	        'total_pages' => ceil( $total_items / $per_page )
	    ) );

	    $this->items = $posts;
	}

}
