<?php

if( ! class_exists( 'WP_List_Table' ) ) {

    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

}


class Commision_Table extends WP_List_Table
{

	public function __construct()
	{
        parent::__construct(

            array(
                'singular' => 'singular_form',
                'plural'   => 'plural_form',
                'ajax'     => false
            )
        );
        add_action( 'admin_menu', array($this,'register_mp_menu_page') );
    }
	function delete_mp_seller(){

	global $wpdb;

	if(isset($_GET['action']) && $_GET['action']=='delete' && isset($_GET['user'])&& isset($_GET['_wpnonce']))
		{
			if((wp_create_nonce('confirm_yes_mp_nonceuser_'.$_GET['user'])==$_GET['_wpnonce']) )
			{
				$user=$_GET['user'];

				$post_id=$wpdb->get_results("select ID from {$wpdb->prefix}posts where post_author='".$user."'");
				foreach($post_id as $id)
				{
					if($wpdb->get_results("delete * from {$wpdb->prefix}postmeta where post_id='".$id->ID."'"))
					{
						wp_delete_post($id->ID);
					}
				}
				$wpdb->get_results("delete * from {$wpdb->prefix}usermeta where user_id='".$user."'");
				wp_delete_user($user);
				echo 'user_deleted';
				echo '<a href="?page=sellers">Go Back To seller List</a>';
				exit;
			}
			if(wp_create_nonce('del_mp_nonceuser_'.$_GET['user'])==$_GET['_wpnonce'])
			{
				$nonce_del=wp_create_nonce('confirm_yes_mp_nonceuser_'.$_GET['user']);
				echo __("Are you sure you want to delete this user this will delete all product and post of this user click yes to delete <a class='submitdelete' href='?page=sellers&action=delete&user=".$_GET['user']."&_wpnonce=".$nonce_del."'>Yes</a> click No to go back<a href='?page=sellers'>No</a>", 'marketplace');
				exit;
			}
		}
	}


	function getdata(){

	global $wpdb;
	$thickbox_pymtd=array();
	$table_name1 = $wpdb->prefix . "usermeta";

	$table_name2 = $wpdb->prefix . "mpsellerinfo";

	if(isset($_POST['s']))

	{

	$m_seller=$_POST['s'];

	$query = "Select Distinct A.user_id,A.seller_value from $table_name2 as A join {$wpdb->prefix}users user on user.ID=A.user_id where (user.user_login LIKE '".$m_seller."%' or user.user_login LIKE '%".$m_seller."' or user.user_login LIKE '%".$m_seller."%')";

	}

	else

	{

	$query = "Select Distinct A.user_id,A.seller_value from $table_name2 as A join {$wpdb->prefix}users user on user.ID=A.user_id";

	}



	$user_ids = $wpdb->get_results($query, ARRAY_A);

	foreach($user_ids as $id){

	$user[]=$id['user_id'];

	$total_sales[] 	=Commision_Table::mp_get_total_order_amount($id['user_id']);

	$user_result[] = get_user_meta($id['user_id']);

	$user_data[]=get_userdata($id['user_id']);

	$user_edit_link[]=get_edit_user_link($id['user_id']);

	$user_products[] = $wpdb->get_results("SELECT COUNT( ID ) AS product FROM wp_posts WHERE post_author='".$id['user_id']."' and post_type='product'");

	}

	$i=0;

	if(!empty($user_result))

	{
		$payment_method='';
		// for Stripe connect or paypal adaptive payment

		$paid_amount='Already Paid';

		foreach( $user_result as $result ) {
			$new_var='modal-window-id_'.$user[$i];
			$commision_display=$wpdb->get_results("select * from {$wpdb->prefix}mpcommision where seller_id='".$user[$i]."'");
			$overall_amount_rem=$commision_display[0]->seller_total_ammount;
			$comm_admin=$wpdb->get_results("select * from {$wpdb->prefix}mpcommision where seller_id='".$user[$i]."'");
			$seller_selected_paymthd=$comm_admin[0]->seller_payment_method;

			if(isset($seller_selected_paymthd)){
				$seller_selected_paymthd=unserialize($seller_selected_paymthd);
				if(isset($seller_selected_paymthd['paypal'])) {
					$mp_paypal=get_option('woocommerce_paypal_settings');

						add_thickbox();
						if( $mp_paypal['enabled']=='yes')
						{
							switch($mp_paypal['testmode'])
							{
							 case 'yes':
		        				$payment_method='<div class="pay" id="'.$user[$i].'"><a title="Pay PayPal" href="javascript:void(0);" id="open_thickbox">'.$seller_selected_paymthd['paypal'].'</a></div>';
		        				break;
		    					case 'no':
		        				$payment_method='<div class="pay" id="'.$user[$i].'"><a title="Pay PayPal" href="javascript::void(0);"  id="open_thickbox">'.$seller_selected_paymthd['paypal'].'</a></div>';
		        				break;
		        				default:
		        				$payment_method='<div class="pay" id="'.$user[$i].'"><a title="Pay PayPal" href="javascript::void(0);" id="open_thickbox">'.$seller_selected_paymthd['paypal'].'</a></div>';
		        			}
	        			}
	        			else
	        			{
	        				switch($mp_paypal['testmode'])
	        				{
						 	case 'yes':
	        				$payment_method='<div class="pay" id="'.$user[$i].'"><a title="Pay PayPal" href="javascript:void(0);" id="open_thickbox">'.$seller_selected_paymthd['paypal'].'</a></div>';
	        				break;
	    	   				default:
	        				$payment_method='<div class="pay" id="'.$user[$i].'"><a title="Pay PayPal" href="javascript::void(0); id="open_thickbox">'.$seller_selected_paymthd['paypal'].'</a></div>';
	        				}
	        			}
				}
				if(isset($seller_selected_paymthd['standard'])){

					if(!empty($seller_selected_paymthd['standard']) ) {


						$thickbox_pymtd=$seller_selected_paymthd['standard'];

						if($overall_amount_rem > 0){

							$seller_main=$user[$i];

							add_thickbox();

							$payment_method="<div class='pay' id='".$user[$i]."'><a href='#TB_inline?width=600&height=350&inlineId=modal-window-id_$user[$i]' id='open_thickbox' class='thickbox' title='Transfer Amount To Seller Account'>Pay</a></div>";	?>
							    <div id='<?php echo $new_var; ?>' style='display:none;'>

									<div class='payment-modelbox'>

										<input type='hidden' class='seller_main' value='<?php echo $user[$i];?>' />

										<?php  wp_create_nonce( 'form-check-nonce' ); ?>

										<label><?php echo __('Payment Method', 'marketplace'). ' :-'; ?> </label>

										<p><?php echo $thickbox_pymtd; ?></p>

										<input type='hidden' class='thickbox_pymtd' value='<?php echo $thickbox_pymtd; ?>' />

										<label><?php echo __('Amount Remain', 'marketplace'). ' :-'; ?> </label>

										<p><?php echo $commision_display[0]->seller_total_ammount;;?></p>

										<input type='hidden' class='thickbox_amt_rem' value='<?php echo $commision_display[0]->seller_total_ammount+$commision_display[0]->paid_amount; ?>' />

										<label><?php echo __('Amount Paid', 'marketplace'). ' :-'; ?> </label>

										<p><?php  echo $commision_display[0]->paid_amount; ?></p>

										<input type='hidden' class='thickbox_paid_amt' value='<?php echo $commision_display[0]->paid_amount; ?>' />
										<p>
											<input type='checkbox' name='notify_seller' class='notify_seller'><strong> <?php echo __('Notify seller about remaining payment received', 'marketplace'); ?></strong>
										</p>
										<button class='button button-primary pay-rem-amt'>Pay Remaining Amout</button>

									</div>

								</div>
							<?php
						}
						else{
							$payment_method="<div class='pay' id='".$user[$i]."'>".$paid_amount."</div>";
						}

					}
					else{
						$payment_method="<div class='pay' id='".$user[$i]."'>".$paid_amount."</div>";
					}
				}
				if(isset($seller_selected_paymthd['stripe'])){

					if(!empty($seller_selected_paymthd['stripe']) ) {

						$thickbox_pymtd=$seller_selected_paymthd['stripe'];

						  if($seller_selected_paymthd['stripe']=='Credit Card (Stripe Connect)'){

								if($overall_amount_rem > 0){

									$commision_display[0]->seller_total_ammount;

									add_thickbox();

									$payment_method="<div class='pay' id='".$user[$i]."'><a href='#TB_inline?width=600&height=350&inlineId=modal-window-id_$user[$i]' id='open_thickbox' class='thickbox' title='Transfer Amount To Seller Account' id='open_thickbox'>Pay</a></div>"; ?>

									<div id='<?php echo $new_var; ?>' style='display:none;'>

										<div class='payment-modelbox'>

											<input type='hidden' class='seller_main' value='<?php echo $user[$i];?>'>

											<?php  wp_create_nonce( 'form-check-nonce' ); ?>

											<label><?php echo __('Payment Method', 'marketplace') . ' :-'; ?> </label>

											<p><?php echo $thickbox_pymtd; ?></p>

											<input type='hidden' class='thickbox_pymtd' value='<?php echo $thickbox_pymtd; ?>' name=''>

											<label><?php echo __('Amount Remain', 'marketplace') . ' :-'; ?> </label>

											<p><?php echo $commision_display[0]->seller_total_ammount;;?></p>

											<input type='hidden' class='thickbox_amt_rem' value='<?php echo $commision_display[0]->seller_total_ammount+$commision_display[0]->paid_amount; ?>'>

											<label><?php echo __('Amount Paid', 'marketplace'). ' :-'; ?> </label>

											<p><?php  echo $commision_display[0]->paid_amount; ?></p>

											<input type='hidden' class='thickbox_paid_amt' value='<?php echo $commision_display[0]->paid_amount; ?>'>
											<p>
												<input type='checkbox' name='notify_seller' class='notify_seller'> <strong> <?php echo __('Notify seller about remaining payment received', 'marketplace'); ?></strong>
											</p>
											<button class='button button-primary pay-rem-amt'><?php echo __('Pay Remaining Amount', 'marketplace'); ?></button>

										</div>

									</div>

							<?php
								}
								else
									$payment_method="<div class='pay' id='".$user[$i]."'>".$paid_amount."</div>";
							}
					}
				}

			}
			$cur_symbol=get_woocommerce_currency_symbol(get_option('woocommerce_currency'));


			$fresult[$i]['seller_id']=$user[$i];

			$fresult[$i]['seller_name']='<a href="'.get_home_url().'/wp-admin/admin.php?page=setcommision&sid='.$user[$i].'">'.$user_result[$i]['first_name'][0].' '.$user_result[$i]['last_name'][0].'</a>';

			$fresult[$i]['seller_email'] =$user_data[$i]->data->user_email;

			$fresult[$i]['commision'] =$commision_display[0]->commision_on_seller.' %';

			$fresult[$i]['total_sales']= $commision_display[0]->last_com_on_total.' '.$cur_symbol;

			$fresult[$i]['comm_amount']= $commision_display[0]->admin_amount.' '.$cur_symbol;

			$fresult[$i]['recive_amount']=$commision_display[0]->paid_amount.' '.$cur_symbol;

			$fresult[$i]['ammount_remain']=$commision_display[0]->seller_total_ammount.' '.$cur_symbol;

			$fresult[$i]['last_pay_am']=$commision_display[0]->last_paid_ammount.' '.$cur_symbol;

			$fresult[$i]['pay_action']= $payment_method;
			?>

	<?php
			$i++;
		}

	}
	 ?>



	<?php


	if (empty($fresult)) {
		$fresult='';
	}

	return $fresult;

}



function column_default( $item, $column_name ) {

    switch( $column_name ) {

	case 'seller_name':

    case 'seller_email':

	case 'commision':

	case 'total_sales':

	case 'comm_amount':

	case 'recive_amount':

	case 'ammount_remain':

	case 'last_pay_am':

	case 'pay_action':



     return $item[ $column_name ];

    default:

      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes

  }



}



function get_columns(){

  $columns = array(

   'cb'        => '<input type="checkbox" />',

	'seller_name' => 'Seller Name',

	'seller_email' => 'Seller Email',

	'commision'=>'Commision %',

	'total_sales'=>'Total Sales',

	'comm_amount'=>'Commision Amount',

	'recive_amount'=>'Paid Amount',

	'ammount_remain'=>'Amount Remain',

	'last_pay_am'=>'Last Pay Amount',

	'pay_action'=>'Action'

  );

  return $columns;

}



function prepare_items() {



  $columns = $this->get_columns();

  $hidden = array();

  $found_data=array();

  $sortable = $this->get_sortable_columns();

  $this->_column_headers = array($columns, $hidden, $sortable);

  $this->_column_headers = $this->get_column_info();

  $seller_data=$this->getdata();

  if(!empty($seller_data))

  {

  usort( $seller_data, array($this, 'usort_reorder' ) );

  }

   $per_page = get_option('posts_per_page');

  $current_page = $this->get_pagenum();

  $total_items = count($seller_data);

  if(!empty($seller_data))

  {

  $found_data = array_slice($seller_data,(($current_page-1)*$per_page),$per_page);

  }

  $this->set_pagination_args( array(

    'total_items' => $total_items,

    'per_page'    => $per_page

  ) );

  /*$this->items = $this->found_data;*/

  $this->items = $found_data;

  //$this->items = $this->getdata();

 }

  public function process_bulk_action()

  {
  			global $wpdb;
         // security check!

		   if ( isset( $_GET['_wpnonce'] ) && ! empty( $_GET['_wpnonce'] ) ) {

		   $_POST['_wpnonce'];

		   $nonce  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );

           $action = 'bulk-' . $this->_args['plural'];

            if ( ! wp_verify_nonce( $nonce, $action ) )

                wp_die( 'Nope! Security check failed!' );

				}



		$action = $this->current_action();

        switch ( $action ) {



            case 'delete':



              $user_id=$_GET['user'];

			  foreach($user_id as $u_id)

			  {

				if($u_id==1){continue;}

				else{

				$post_id=$wpdb->get_results("select ID from {$wpdb->prefix}posts where post_author='".$u_id."'");

				foreach($post_id as $id)

				{

					if($wpdb->get_results("delete * from {$wpdb->prefix}postmeta where post_id='".$id->ID."'"))

					{

						wp_delete_post($id->ID);

					}

				}

					$wpdb->get_results("delete * from {$wpdb->prefix}usermeta where user_id='".$u_id."'");

				wp_delete_user($u_id);

				}

			  }

			  echo 'user_deleted';

				echo "<a href='?page=sellers'>Go Back To seller List</a>";

                break;



				default:

                break;

        }



      }

	function column_cb($item) {
		if(isset($item['seller_id']))

        return sprintf('<input type="checkbox" id="user_%s"name="user[]" value="%s" />',$item['seller_id'], $item['seller_id']);

        else

        return sprintf('<input type="checkbox" id=""name="user[]" value="" />');

    }

	//action start here



	function column_nickname($item)
	{

	$nonce = wp_create_nonce('remove-users');

	//wp_create_nonce( $action );

	 $actions = array(

            'edit'      => sprintf('<a href="'.get_edit_user_link($item['id']).'">Edit</a>'),

            'delete'    => sprintf('<a class="submitdelete" href="?page=sellers&action=delete&user=%s&_wpnonce=%s">Delete</a>',$item['id'],wp_create_nonce('del_mp_nonceuser_'.$item['id']))

        );



	  return sprintf('%1$s %2$s', $item['nickname'], $this->row_actions($actions) );

	}



//shorting on title click



		function get_sortable_columns() {

		  $sortable_columns = array(

		    'nickname'  => array('nickname',false),

		    'name' => array('name',false),

		    'user_email'   => array('user_email',false)

		  );

		  return $sortable_columns;

		}

		function usort_reorder( $a, $b ) {



		  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';

		  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		  $result='';
		  if(isset($a[$orderby]))
		  $result = strcmp( $a[$orderby], $b[$orderby] );

		  return ( $order === 'asc' ) ? $result : -$result;

		}

		function test_table_set_option($status, $option, $value) {

		  return $value;

		}





		function register_mp_menu_page(){

		    add_menu_page( 'Mp menu', 'Seller List', '', 'seller list page', 'my_custom_menu_page', plugins_url( 'marketplace/assets/images/tick.png' ), 6 );

		}



		function update_deleted_user()

		{

		global $wpdb;

		$wpdb->get_results("Delete from {$wpdb->prefix}mpsellerinfo where user_id not in(select ID from {$wpdb->prefix}users)");

		}



		function add_options()

		{

		global $ListTable;

		  $option = 'per_page';

		  $args = array(

		         'label' => 'Seller',

		         'default' => 10,

		         'option' => 'seller_per_page'

		         );

		  add_screen_option( $option, $args );

		  $ListTable = new Seller_List_Table;

		}

		//seller search

		function mp_search_seller()

		{?>

		<form action="<?php $this->prepare_items();?>"method="post">

		  <input type="hidden" name="page" value="my_list_test" />

		  <?php $this->search_box('search', 'mp_search_seller'); ?>

		</form>

		<?php

		}

		//seller search end

		function mp_get_total_order_amount($sel_id)
		{

				global $wpdb;

				$postid=Commision_Table::getOrderId($sel_id);
				if(!empty($postid))
				{
				$sql="select sum(meta_value) AS 'total_order_amount' from {$wpdb->prefix}woocommerce_order_itemmeta where meta_key='_line_total' and order_item_id in(".$postid.")";
				$total_value=$wpdb->get_var($sql);

				}
				else
				{
					$total_value=0;
				}

				return $total_value;

		}



		public function getOrderId($sel_id)

		{

			global $wpdb;

			 $sql="SELECT woi.order_item_id,woi.meta_value

					FROM {$wpdb->prefix}woocommerce_order_itemmeta woi

					JOIN {$wpdb->prefix}woocommerce_order_items woitems ON woitems.order_item_id = woi.order_item_id

					JOIN {$wpdb->prefix}posts post ON woi.meta_value = post.ID

					WHERE woi.meta_key ='_product_id'

					AND post.ID = woi.meta_value

					AND post.post_author ='".$sel_id."'

					GROUP BY order_id";

			$result=$wpdb->get_results($sql);

			$ID=array();

			foreach($result as $res)

			{

				$ID[]=$res->order_item_id;

			}

			return implode(',',$ID);

		}

// seller total sales
		//updateing seller commition table

		function seller_commission()
			{
				global $wpdb;
				$com_on_seller=get_option('wkmpcom_minimum_com_onseller');
				$seller_id=$wpdb->get_results("select Distinct user_id from {$wpdb->prefix}mpsellerinfo");
				foreach($seller_id as $id)
					{
						$sel_in_commition=$wpdb->get_results("select Distinct seller_id from {$wpdb->prefix}mpcommision");
						$seller_list=array();
						foreach($sel_in_commition as $sellid)
							{
								$seller_list[]=$sellid->seller_id;
							}
						if(!in_array($id->user_id, $seller_list))
						{
							$wpdb->insert("{$wpdb->prefix}mpcommision",array('id'=>'','seller_id'=>$id->user_id,'commision_on_seller'=>$com_on_seller,'admin_amount'=>0,'seller_total_ammount'=>0,'paid_amount'=>0,'last_paid_ammount'=>0,'last_com_on_total'=>0));
						}
					}
			}


			function update_seller_ammount()
			{
				global $wpdb;
				$com_on_seller=get_option('wkmpcom_minimum_com_onseller');
				$seller_id=$wpdb->get_results("select Distinct user_id from {$wpdb->prefix}mpsellerinfo");
				foreach($seller_id as $id)
				{
					$total_sales=Commision_Table::mp_get_total_order_amount($id->user_id);
					if($total_sales=='')
					{
						$total_sales=0;
					}
					$remain_update=$wpdb->get_results("select * from {$wpdb->prefix}mpcommision where seller_id='".$id->user_id."'");
					$money=$total_sales-$remain_update[0]->last_com_on_total;
					$comm=$remain_update[0]->commision_on_seller ? $remain_update[0]->commision_on_seller :0;
					$admin_com=$money*($comm/100);// admin commission
					$admin_money=$admin_com+$remain_update[0]->admin_amount;
					$seller_money=($money-$admin_com)+$remain_update[0]->seller_total_ammount;

					$wpdb->get_results("update {$wpdb->prefix}mpcommision set admin_amount='".$admin_money."',seller_total_ammount='".$seller_money."',last_com_on_total='".$total_sales."'where seller_id='".$id->user_id."'");
				}

			}

			function update_paypal_payment()
			{
				global $wpdb;
				if(isset($_GET['sid'])&& $_POST['payment_status']=='Completed')
				{
					$id=$_GET['sid'];
					$pay=$_POST['payment_gross'];
					$query = "select * from {$wpdb->prefix}mpcommision where seller_id=$id";
					$seller_data = $wpdb->get_results($query);
					$paid_ammount=$seller_data[0]->paid_amount+$pay;
					$seller_total_ammount=$seller_data[0]->seller_total_ammount-$pay;
					$last_paid_ammount=$pay;
					$seller_money=$seller_data[0]->last_com_on_total-$seller_data[0]->admin_amount;
					$remain_ammount=$seller_money-$paid_ammount;
					$wpdb->get_results("update {$wpdb->prefix}mpcommision set paid_amount='".$paid_ammount."',seller_total_ammount='".$seller_total_ammount."',seller_total_ammount='".$remain_ammount."',last_paid_ammount='".$last_paid_ammount."'where seller_id='".$id."'");
					echo "<div id='wk_payment_success'>";
          echo __("Your payment is successfull", "marketplace");
          echo "</div>";
				}
			}




	}


$CommisionTable = new Commision_Table();

$CommisionTable->update_deleted_user();

$CommisionTable->seller_commission();

$CommisionTable->update_seller_ammount();

$CommisionTable->delete_mp_seller();

$CommisionTable->mp_search_seller();

$CommisionTable->update_paypal_payment();

add_filter('set-screen-option', 'test_table_set_option', 10, 3);




printf( '<div class="wrap" id="seller-list-table"><h2>%s</h2>', __( 'Commision List', 'marketplace' ) );

echo '<form id="seller-list-table-form" method="get">';



$page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );

$paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );

printf( '<input type="hidden" name="page" value="%s" />', $page );

printf( '<input type="hidden" name="paged" value="%d" />', $paged );



$CommisionTable->prepare_items(); // this will prepare the items AND process the bulk actions

$CommisionTable->display();

echo '</form>';
echo '</div>';

?>
<div id="com-pay-ammount" style="display:none;">
</div>
