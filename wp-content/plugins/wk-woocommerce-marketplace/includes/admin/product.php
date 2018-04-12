<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Product_List_Table extends WP_List_Table
{


	public function __construct() {

        parent::__construct(
            array(
                'singular' => 'singular_form',
                'plural'   => 'plural_form',
                'ajax'     => false
            )
        );
    }


	function trash_product()
	{
		if(isset($_GET['action']) && $_GET['action']=='trash'&& isset($_GET['_wpnonce']))
		{
			if(wp_create_nonce('trash_'.$_GET['post'])==$_GET['_wpnonce'])
			{
				$product_trashed = array('ID'=>$_GET['post'],'post_status' => 'trash');
				if(wp_update_post( $product_trashed ))
				{
					echo __('Product Trashed', 'marketplace');
				}
			}
		}
	}

	function extra_tablenav( $which ) {
    global $wpdb;
    $nonce = wp_create_nonce();

    if ( $which == "top" ){

    	$cr_id=get_current_user_id();

        ?>
        <div class="alignleft actions bulkactions">

            <select name="check-pro" class="ewc-filter-cat">

                <option value=""><?php echo __('Filter by Product', 'marketplace'); ?></option>

                <option value="assign"><?php echo __('Assigned', 'marketplace'); ?></option>

                <option value="<?php echo $cr_id; ?>"><?php echo __('UnAssigned', 'marketplace'); ?></option>

            </select>

        </div>
        <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>">
        <?php

        submit_button( __( 'Change', 'marketplace' ), 'button', 'changeit', false);
    }

    if ( $which == "bottom" ){
        //The code that goes after the table is there

    }
}


function getdata()
{

	//$_GET['post_status'];
	global $wpdb;
	if( isset($_GET['changeit'])){

        $val=$_GET['check-pro'];
        if($val==1)
        	$query = "SELECT post.ID as post_id from {$wpdb->prefix}posts as post where post.post_type='product' and (post.post_status='publish' or post.post_status='draft') and post.post_author=1";
        else
        	$query = "SELECT post.ID as post_id from {$wpdb->prefix}posts as post where post.post_type='product' and (post.post_status='publish' or post.post_status='draft') and post.post_author!=1";
    }

	else if(isset($_POST['s']))
	{
		$p_search=$_POST['s'];


		$query = "SELECT post.ID as post_id from {$wpdb->prefix}posts as post where post.post_type='product' and (post.post_status='publish' or post.post_status='draft') and (post.post_title like '".$p_search."%' or post.post_title like '%".$p_search."' or post.post_title like '%".$p_search."%')";
	}
	else{


		$query = "SELECT post.ID as post_id from {$wpdb->prefix}posts as post where post.post_type='product' and (post.post_status='publish' or post.post_status='draft')";

	}

	$post_ids = $wpdb->get_results($query, ARRAY_A);

	if(!empty($post_ids ))
	{


		foreach($post_ids as $id)
		{

			$post_result[] = get_post($id['post_id'],ARRAY_A);

			$post_data[]=get_post_meta($id['post_id']);

			$post_cat=get_the_category($id['post_id']);

			$category=get_categories($post_cat);

			$user_edit_link[]=get_edit_user_link($id['post_id']);

			$user_products[] = $wpdb->get_results("SELECT COUNT( ID ) AS product FROM wp_posts WHERE post_author='".$id['post_id']."'");

		}


		$i=0;

		$currency=get_woocommerce_currency_symbol();

		foreach( $post_result as $result )
		{
			$product_tags = get_the_terms( $result['ID'], 'product_tag' );

			$product_cats = get_the_terms( $result['ID'], 'product_cat' );

			$product_object = wc_get_product( $result['ID'] );

			$product_type='';

			$p_cat=array();

			if(!empty($product_cats))
			{
				foreach($product_cats as $cat)
				{
					$p_cat[]=$cat->name;
				}
				$category=implode(',',$p_cat);
			}

			else {
				$category='';
			}
			$p_tags=array();

			if(!empty($product_tags))
			{
				foreach($product_tags as $tag)
				{
					$p_tags[]=$tag->name;
				}
				$tags=implode(',',$p_tags);
			}

			else{
				$tags='';

			}

			if(isset($post_data[$i]['_downloadable'][0]) && $post_data[$i]['_downloadable'][0]=='yes')
			$producttype='Downloadable';

			if(isset($post_data[$i]['_virtual'][0]) && $post_data[$i]['_virtual'][0]=='yes')
				$producttype='Virtual';

			if($result['post_author']==1) {
				if(isset($post_data[$i]['_downloadable'][0]) && $post_data[$i]['_downloadable'][0] =='no' && $post_data[$i]['_virtual'][0] == 'no')
					update_post_meta($id['post_id'],'_simple','yes');
					$producttype='Simple';
			}
			else if(isset($post_data[$i]['_simple'][0]) && $post_data[$i]['_simple'][0]=='yes')
				$producttype='Simple';

			else
			$producttype='';

			$thumnail_image = explode(',',get_post_thumbnail_id($result['ID']));

			$product_thum = $wpdb->get_var("select meta_value from {$wpdb->prefix}postmeta where post_id='".$thumnail_image[0]."' and meta_key='_wp_attached_file'");

			$product_thum = get_post_meta($thumnail_image[0],'_wp_attached_file',true);

			$post_date = explode(' ',$result['post_date']);

			if($product_thum == '')
				$fresult[$i]['Image']='<img class="attachment-shop_thumbnail wp-post-image" width="50" height="50" alt="" src="'.WK_MARKETPLACE.'assets/images/placeholder.png'.'">';

			else
				$fresult[$i]['Image'] = '<img class="attachment-shop_thumbnail wp-post-image" width="50" height="50" alt="" src="'.content_url().'/uploads/'.$product_thum.'">';

			$fresult[$i]['id'] = $result['ID'];

			$fresult[$i]['Title'] = $result['post_name'];

			$fresult[$i]['Name'] ='<a href="post.php?post='.$result['ID'].'&action=edit">'. $result['post_title'].'</a>';

			$fresult[$i]['SKU'] = (isset($post_data[$i]['_sku'][0]) ? $post_data[$i]['_sku'][0] : 0);

			$fresult[$i]['Stock']= isset($post_data[$i]['_stock_status'][0])? '<mark class="instock">'.$post_data[$i]['_stock_status'][0].'</mark>':'draft';

			if ( $product_object->is_type( 'simple' ) ){
				$product_type='simple';

				$fresult[$i]['Price']='<span class="amount">'. wc_price($product_object->get_price()) .'</span>';
			}
			else if( $product_object->is_type( 'variable' ) ){
				$product_type='variable';

				$fresult[$i]['Price']='<span class="price"><span class="amount">'.wc_price($product_object->get_variation_prices()['price'] ? min($product_object->get_variation_prices()['price']) : 0 ).'</span>&ndash;<span class="amount">'.wc_price($product_object->get_variation_prices()['price'] ? max($product_object->get_variation_prices()['price']) : 0 ) .'</span></span>';

			}
			else if( $product_object->is_type( 'external' ) ){
				$product_type='external';

				$fresult[$i]['Price']='<span class="amount">'.wc_price($product_object->get_price()).'</span>';

			}
			else if( $product_object->is_type( 'grouped' ) ){
				$product_type='grouped';

				$fresult[$i]['Price']='<span class="amount">-</span>';
			}

			$fresult[$i]['Categories']=$category;

			$fresult[$i]['Tags']=$tags;

			$fresult[$i]['featured']=isset($post_data[$i]['_featured'][0])?$post_data[$i]['_featured'][0]:'no';

			$fresult[$i]['Type']=$product_type;/*$producttype;*/

			$fresult[$i]['Date']=$post_date[0]."<br>".$result['post_status'];
			if($result['post_author']==1)
				$fresult[$i]['Seller']='Admin';
			else
				$fresult[$i]['Seller'] = get_user_meta($result['post_author'],'first_name',true) ." ". get_user_meta($result['post_author'],'last_name',true);

			$i++;

		}

		return $fresult;
	}
}

function column_default( $item, $column_name )
{
  switch( $column_name ) {
  	case 'Image':
  	case 'Name':
    case 'SKU':
    case 'Stock':
  	case 'Price':
  	case 'Categories':
  	case 'Tags':
  	case 'featured':
  	case 'Type':
  	case 'Date':
  	case 'Seller':
     return $item[ $column_name ];
    default:
      return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
  }
}

function get_columns(){
  $columns = array(
   	'cb'    	=> '<input type="checkbox" />',
    'Image' 	=> '<span class="wc-image tips">Image</span>',
	'Name' 		=> 'Product',
    'SKU' 		=> 'SKU',
	'Stock'		=>'Stock',
	'Price'		=>'Price',
	'Categories'=>'Categories',
	'Tags'		=>'Tags',
	'featured'	=>'featured',
	'Type'		=>'Type',
	'Date'		=>'Date',
	'Seller'	=>'Seller',
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
	$this->process_bulk_action();
    $data_return=$this->getdata();
    // $get_array=$this->getdata();
  if(!empty($data_return))
  {
  	usort( $data_return, array($this, 'usort_reorder' ) );
  }
  $per_page = get_option('posts_per_page');
  $current_page = $this->get_pagenum();
/*  echo "=============================================";
  print_r($per_page);
  echo "=============================================";*/
  $total_items = count($data_return);
  if(!empty($data_return))
  {
  	$found_data = array_slice($data_return,(($current_page-1)*$per_page),$per_page);
  }
  $this->set_pagination_args( array(
    'total_items' => $total_items,
    'per_page'    => $per_page
  ) );
  $this->items = $found_data;
  // $this->items = $this->getdata();
 }
  public function process_bulk_action()
  {
        // security check!
		if ( isset( $_GET['_wpnonce'] ) && ! empty( $_GET['_wpnonce'] ) ) {
			// $_POST['_wpnonce'];
			$nonce  = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
	        $action = 'bulk-' . $this->_args['plural'];
	        if ( ! wp_verify_nonce( $nonce ) )
                wp_die( 'Nope! Security check failed!' );
		}

		echo $action = $this->current_action();
        switch ( $action ) {
            case 'trash':
			$product_id=$_GET['product'];
			foreach($product_id as $id)
			{
			$product_trashed = array('ID'=>$id,'post_status' => 'trash');
			wp_update_post( $product_trashed );
			}
			echo __('Product Trashed', 'marketplace');
            break;

				default:
                break;
        }

      }

function get_bulk_actions() {
  	$actions = array(
    	'trash'    => 'Trash'
  	);
   return $actions;
}

function column_cb($item)
{
    return sprintf('<input type="checkbox" name="product[]" value="%s" />', $item['id']);
}
	//action start here

function column_Name($item) {
  	$actions = array(
            'ID'      => sprintf('ID:%s',$item['id']),
            'Edit'    => sprintf('<a href="post.php?post=%s&action=edit">Edit</a>',$item['id']),
            'View'    => sprintf('<a href="'.get_site_url().'?product=%s">View</a>',$item['Title']),
            'Trash'    => sprintf('<a class="submitdelete" title="move this to the trash" href="?page=products&post=%s&action=trash&_wpnonce=%s">Trash</a>',$item['id'],wp_create_nonce('trash_'.$item['id']))
        );

  return sprintf('%1$s %2$s', $item['Name'], $this->row_actions($actions) );
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
//searching in product table
function mp_search_product()
{?>
<form action="<?php $this->prepare_items();?>"method="post">
  <input type="hidden" name="page" value="my_list_test" />
  <?php $this->search_box('search', 'mp_search_product'); ?>
</form>
<?php
}
//searching end
/*function test_table_set_option($status, $option, $value) {
  return $value;
}*/


/*function register_mp_menu_page(){
    add_menu_page( 'Mp menu', 'Seller List', '', 'seller list page', 'my_custom_menu_page', plugins_url( 'marketplace/assets/images/tick.png' ), 6 );
}*/
function load_js()
{
add_action( 'admin_enqueue_scripts', array($this,'add_Quick_edit_js'));
}
function add_Quick_edit_js()
		{
		echo "132";
		wp_register_script('marketplace', plugins_url( 'woocommerce/assets/js/admin/quick-edit.min.js' ) , array( 'jquery', 'inline-edit-post' ), '', true );
		}
}
$ProductListTable = new Product_List_Table();
$ProductListTable->trash_product();
$ProductListTable->mp_search_product();
//add_filter('set-screen-option', 'test_table_set_option', 10, 3);
//add_action( 'admin_menu', array($this,'register_mp_menu_page') );
$ProductListTable-> load_js();
/*echo "<br>===========================================<br>";
echo "<pre>";
print_r($ProductListTable->getdata());
echo "</pre>";
echo "<br>===========================================<br>";*/
printf( '<div class="wrap" id="product-list-table"><h2>%s</h2>', __( 'Product List', 'marketplace' ) );
echo '<form id="product-list-table-form" method="get">';
$page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
$paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
printf( '<input type="hidden" name="page" value="%s" />', $page );
printf( '<input type="hidden" name="paged" value="%d" />', $paged );

$ProductListTable->prepare_items(); // this will prepare the items AND process the bulk actions
$ProductListTable->display();

echo '</form>';

echo '</div>';
