<?php

if ( !class_exists( 'MP_Notifications' ) )
{
    /**
     *
     */
    class MP_Notifications
    {

        function __construct()
        {
            add_action( 'woocommerce_checkout_order_processed', array( $this, 'mp_custom_process_order' ), 1, 1 );

            add_action( 'transition_post_status', array( $this, 'mp_save_on_product_update'), 10, 3 );

            add_action( 'mp_save_seller_review_notification', array( $this, 'mp_save_seller_review_notification' ), 10, 2 );

            add_action( 'woocommerce_low_stock_notification', array( $this, 'mp_low_stock' ) );

            add_action( 'woocommerce_no_stock_notification', array( $this, 'mp_no_stock' ) );

            add_action( 'woocommerce_order_status_processing', array( $this, 'mp_order_processing_notification' ), 10, 1);

            add_action( 'woocommerce_order_status_completed', array( $this, 'mp_order_completed_notification' ), 10, 1);

            add_action( 'marketplace_list_seller_option', array( $this, 'mp_seller_panel_notification_option' ) );

            add_action( 'wp_head', array( $this, 'mp_call_notification_page' ) );

        }

        function mp_order_completed_notification( $order_id )
        {
            global $wpdb;

            $table_name = $wpdb->prefix.'mp_notifications';

            $order = new WC_Order( $order_id );

            $items = $order->get_items();

            foreach ($items as $key => $item)
            {
                $product = get_post($item['product_id']);
                $seller_id[] = $product->post_author;
            }

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            foreach ( $seller_id as $key => $value )
            {

              $user_meta = get_userdata( $value );
              $user_roles = $user_meta->roles;

              if ( in_array( 'administrator', $user_roles )) {
                  $content = 'Order: <a href="'.admin_url('post.php?post='.$order_id.'&action=edit').'">#'.$order_id.'</a> status has been changed to <strong>Complete</strong>';
              }
              else {
                  $content = 'Order: <a href="'.site_url($page_name.'/order-history/'.$order_id).'">#'.$order_id.'</a> status has been changed to <strong>Complete</strong>';
              }

              $now = new DateTime('now');

              $sql = $wpdb->insert(
                    $table_name,
                    array(
                        'type'      => 'order',
                        'author_id' => $value,
                        'content'   => $content,
                        'timestamp' => $now->format('Y-m-d H:i:s')
                    )
                );

            }

        }

        function mp_order_processing_notification( $order_id )
        {
            global $wpdb;

            $table_name = $wpdb->prefix.'mp_notifications';

            $order = new WC_Order( $order_id );

            $items = $order->get_items();

            foreach ($items as $key => $item)
            {
                $product = get_post($item['product_id']);
                $seller_id[] = $product->post_author;
            }

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            foreach ( $seller_id as $key => $value )
            {

              $user_meta = get_userdata( $value );
              $user_roles = $user_meta->roles;

              if ( in_array( 'administrator', $user_roles )) {
                  $content = 'Order: <a href="'.admin_url('post.php?post='.$order_id.'&action=edit').'">#'.$order_id.'</a> status has been changed to <strong>Processing</strong>';
              }
              else {
                  $content = 'Order: <a href="'.site_url($page_name.'/order-history/'.$order_id).'">#'.$order_id.'</a> status has been changed to <strong>Processing</strong>';
              }

              $now = new DateTime('now');

              $sql = $wpdb->insert(
                    $table_name,
                    array(
                        'type'      => 'order',
                        'author_id' => $value,
                        'content'   => $content,
                        'timestamp' => $now->format('Y-m-d H:i:s')
                    )
                );

            }

        }

        function mp_low_stock( $product )
        {
            global $wpdb;

            $table_name = $wpdb->prefix.'mp_notifications';

            $product_data = get_post($product->get_id());
            $seller_id = $product_data->post_author;

            $user_meta = get_userdata( $seller_id );
            $user_roles = $user_meta->roles;

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            if ( in_array( 'administrator', $user_roles ))
            {
                $content = 'Product: <a href="'.admin_url('post.php?post='.$product->get_id().'&action=edit').'">'.$product->get_name().'</a> is low in stock. There are '.$product->get_stock_quantity().' left ';

            }
            else {
                $content = 'Product: <a href="'.site_url($page_name.'/product/edit/'.$product->get_id()).'">'.$product->get_name().'</a> is low in stock. There are '.$product->get_stock_quantity().' left ';
            }

            $now = new DateTime('now');

            $sql = $wpdb->insert(
                $table_name,
                array(
                    'type'      => 'product',
                    'author_id' => get_post_field( 'post_author', $product->get_id(), true ),
                    'content'   => $content,
                    'timestamp' => $now->format('Y-m-d H:i:s')
                )
            );
        }

        function mp_no_stock( $product )
        {
            global $wpdb;

            $table_name = $wpdb->prefix.'mp_notifications';

            $product_data = get_post($product->get_id());
            $seller_id = $product_data->post_author;

            $user_meta = get_userdata( $seller_id );
            $user_roles = $user_meta->roles;

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            if ( in_array( 'administrator', $user_roles ))
            {
                $content = 'Product: <a href="'.admin_url('post.php?post='.$product->get_id().'&action=edit').'">'.$product->get_name().'</a> is out of stock. ';

            }
            else {
                $content = 'Product: <a href="'.site_url($page_name.'/product/edit/'.$product->get_id()).'">'.$product->get_name().'</a> is out of stock. ';
            }

            $now = new DateTime('now');

            $sql = $wpdb->insert(
                $table_name,
                array(
                    'type'      => 'product',
                    'author_id' => get_post_field( 'post_author', $product->get_id(), true ),
                    'content'   => $content,
                    'timestamp' => $now->format('Y-m-d H:i:s')
                )
            );
        }

        function mp_save_seller_review_notification( $data, $sql )
        {
            global $wpdb;

            $table_name = $wpdb->prefix.'mp_notifications';

            $author_name = get_user_by( 'ID', $data['mp_wk_user'] )->display_name;

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            $shop_address = get_user_meta( $data['mp_wk_seller'], 'shop_address', true );

            $content = 'New review: <a href="'.site_url($page_name.'/feedback/'.$shop_address).'" target="_blank">#'.$sql.'</a> has been placed by <a href="'.admin_url('user-edit.php?user_id='.$data['mp_wk_user']).'">'.$author_name.'</a>';

            $now = new DateTime('now');

            $sql = $wpdb->insert(
                $table_name,
                array(
                    'type'      => 'seller',
                    'author_id' => $data['mp_wk_seller'],
                    'content'   => $content,
                    'timestamp' => $now->format('Y-m-d H:i:s')
                )
            );

        }

        function mp_save_on_product_update( $new_status, $old_status, $post )
        {
            global $wpdb;

            if( $old_status != 'publish' && $new_status == 'publish' && !empty($post->ID) && in_array( $post->post_type, array( 'product') ) ) {

                $table_name = $wpdb->prefix.'mp_notifications';

                $product_data = get_post($post->ID);
                $seller_id = $product_data->post_author;

                $user_meta = get_userdata( $seller_id );
                $user_roles = $user_meta->roles;

                $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

                if ( in_array( 'administrator', $user_roles ))
                {
                    $content = '<a href="'.get_edit_post_link($post->ID).'">'.$post->post_title.'</a> has been approved ';
                }
                else {
                    $content = '<a href="'.site_url($page_name.'/product/edit/'.$post->ID).'">'.$post->post_title.'</a> has been approved ';
                }

                $now = new DateTime('now');

                $sql = $wpdb->insert(
                    $table_name,
                    array(
                        'type'      => 'product',
                        'author_id' => $post->post_author,
                        'content'   => $content,
                        'timestamp' => $now->format('Y-m-d H:i:s')
                    )
                );
            }
        }

        function mp_custom_process_order( $order_id )
        {
            global $wpdb;

            $order = new WC_Order( $order_id );

            $table_name = $wpdb->prefix.'mp_notifications';

            $items = $order->get_items();

            $order_author = get_post_meta( $order_id, '_customer_user', true );

            if ( $order_author == 0 )
            {
                $author_name = 'Guest';
            }
            else
            {
                $author_name = get_user_by( 'ID', $order_author )->display_name;
            }

            foreach ($items as $key => $item)
            {
                $product = get_post($item['product_id']);
                $seller_id[] = $product->post_author;
            }

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            foreach ( $seller_id as $key => $value )
            {

              $user_meta = get_userdata( $value );
              $user_roles = $user_meta->roles;

              if ( in_array( 'administrator', $user_roles )) {
                  $content = 'New order: <a href="'.admin_url('post.php?post='.$order_id.'&action=edit').'">#'.$order_id.'</a> has been placed by <strong>'.$author_name.'</strong>';
              }
              else {
                  $content = 'New order: <a href="'.site_url($page_name.'/order-history/'.$order_id).'">#'.$order_id.'</a> has been placed by <strong>'.$author_name.'</strong>';
              }

              $now = new DateTime('now');

              $sql = $wpdb->insert(
                    $table_name,
                    array(
                        'type'      => 'order',
                        'author_id' => $value,
                        'content'   => $content,
                        'timestamp' => $now->format('Y-m-d H:i:s')
                    )
                );

            }

        }

        function mp_seller_panel_notification_option( $page_name )
        {
            global $wpdb;

            $user_id = get_current_user_id();

            $total = $wpdb->get_results("Select * from {$wpdb->prefix}mp_notifications where read_flag = '0' and author_id = '$user_id' ", ARRAY_A);

            $total_count = 0;

            foreach( $total as $key => $value )
            {
                if ( in_array( $user_id, explode(',',$value['author_id'])))
                {
                    $total_count++;
                }
            }

            echo '<li class="wkmp-selleritem"><a href="'.home_url("/".$page_name."/notification").'">';
            echo __('Notifications', 'marketplace');
            echo '<span class="noti-count">'.$total_count.'</span></a></li>';
        }

        function mp_call_notification_page()
        {
            global $current_user, $wpdb;

            $current_user = wp_get_current_user();

            $seller_info = $wpdb->get_var("SELECT user_id FROM ".$wpdb->prefix."mpsellerinfo WHERE user_id = '".$current_user->ID ."' and seller_value='seller'");

            $page_name = $wpdb->get_var("SELECT post_name FROM $wpdb->posts WHERE post_name ='".get_option('wkmp_seller_page_title')."'");

            $pagename = get_query_var('pagename');

            $main_page = get_query_var('main_page');

            if( !empty( $pagename ) )
            {

          			if ( $pagename == $page_name && $main_page == 'notification' && ($current_user->ID || $seller_info>0) )
                {
          				   add_shortcode( 'marketplace', array( $this, 'mp_seller_notifications' ) );
          			}
            }

        }

        function mp_seller_notifications()
        {
            global $wpdb;
            $user_id = get_current_user_id();
            ?>
            <h3><?php echo _e("Notifications"); ?></h3>
            <ul id='edit_notification_tab'>

                <li><a id='notification_order_tab'><?php echo _e( "Orders", "marketplace" ); ?></a></li>

                <li><a id='notification_products_tab' class="inactive"><?php echo _e( "Products", "marketplace" ); ?></a></li>

                <li><a id='notification_seller_tab' class="inactive"><?php echo _e( "Seller", "marketplace" ); ?></a></li>

            </ul>

            <div class="wkmp_container" id="notification_order_tabwk">
                <?php
                $sql = $wpdb->get_results("Select * From {$wpdb->prefix}mp_notifications where type='order' and author_id = '$user_id' order by id desc", ARRAY_A);

                $table_name = $wpdb->prefix.'mp_notifications';

    						echo '<ul class="mp-notification-list">';
    								foreach ($sql as $key => $value)
    								{

                        if ( $value['read_flag'] == 0 ) {
                            $wpdb->update(
                                $table_name,
                                array(
                                        'read_flag'=> '1',
                                        'timestamp'	=> $value['timestamp'],
                                ),
                                array(
                                        'id'	=> $value['id']
                                )
                            );
                        }
                        $datetime1 = new DateTime(date('F j, Y', strtotime($value['timestamp']) ) );
                        $datetime2 = new DateTime('now');
                        $interval = $datetime1->diff($datetime2);
                        echo '</strong><li class="notification-link" data-id="'.$value['id'].'">'.$value['content'].' <strong>'.$interval->days.' day(s) ago</strong>.</li>';

    								}
    						echo '</ul>';
                ?>
            </div>

            <div class="wkmp_container" id="notification_products_tabwk">
                <?php

                $sql = $wpdb->get_results("Select * From {$wpdb->prefix}mp_notifications where type='product' and author_id = '$user_id' order by id desc", ARRAY_A);

                $table_name = $wpdb->prefix.'mp_notifications';

                echo '<ul class="mp-notification-list">';
                    foreach ($sql as $key => $value)
                    {

                        if ( $value['read_flag'] == 0 ) {
                            $wpdb->update(
                                $table_name,
                                array(
                                        'read_flag'=> '1',
                                        'timestamp'	=> $value['timestamp'],
                                ),
                                array(
                                        'id'	=> $value['id']
                                )
                            );
                        }
                        $datetime1 = new DateTime(date('F j, Y', strtotime($value['timestamp']) ) );
                        $datetime2 = new DateTime('now');
                        $interval = $datetime1->diff($datetime2);
                        echo '<li class="notification-link" data-id="'.$value['id'].'">'.$value['content'].'<strong>'.$interval->days.' day(s) ago</strong>.</li>';

                    }
                echo '</ul>';
                ?>
            </div>

            <div class="wkmp_container" id="notification_seller_tabwk">
                <?php
                $sql = $wpdb->get_results("Select * From {$wpdb->prefix}mp_notifications where type='seller' and author_id = '$user_id' order by id desc", ARRAY_A);

                $table_name = $wpdb->prefix.'mp_notifications';

    						echo '<ul class="mp-notification-list">';
                    foreach ($sql as $key => $value)
                    {
                        if ( $value['read_flag'] == 0 ) {
                            $wpdb->update(
                                $table_name,
                                array(
                                        'read_flag'=> '1',
                                        'timestamp'	=> $value['timestamp'],
                                ),
                                array(
                                        'id'	=> $value['id']
                                )
                            );
                        }
                        $datetime1 = new DateTime(date('F j, Y', strtotime($value['timestamp']) ) );
                        $datetime2 = new DateTime('now');
                        $interval = $datetime1->diff($datetime2);
                        echo '<li class="notification-link" data-id="'.$value['id'].'">'.$value['content'].' <strong>'.$interval->days.' day(s) ago</strong>.</li>';
                    }
    						echo '</ul>';
                ?>
            </div>
            <?php
        }

    }

    new MP_Notifications();

}
