<?php

if( ! defined( 'ABSPATH' ) )

    exit;

?>

<div class="favourite-seller">

    <div id="notify-customer" class="modal fade">

        <div class="modal-dialog">

            <div class="modal-content">

                 <form action="" method="post" id="snotifier">

                  <div class="modal-header">

                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                      <h4 class="modal-title"><?php echo __('Confirmation', 'marketplace'); ?></h4>

                  </div>

                  <div class="modal-body">

                      <div class="form-group">

                  <label for="subject"><?php echo __('Subject: *', 'marketplace'); ?></label>

                  <input type="text" name="customer_subject" class="form-control" aria-describedby="subject" placeholder="Enter Subject">

                  <small id="subject" class="form-text text-muted"><?php echo __("We'll never share your email with anyone else.", "marketplace"); ?></small>

              </div>

              <div class="form-group">

                  <label for="message"><?php echo __('Message: *', 'marketplace'); ?></label>

                  <textarea name="customer_message" class="form-control" aria-describedby="message" placeholder="Enter Message" rows="4"></textarea>

                <input type="hidden" name="seller_id" value="<?php echo get_current_user_id(); ?>">

              </div>

                  </div>

                  <div class="modal-footer">

                <div class="final-result"></div>

                      <div class="reaction">

                        <button type="button" id="wk-cancel-mail"><?php echo __('Close', 'marketplace'); ?></button>

                        <button type="submit" id="wk-send-mail"><?php echo __('Send Mail', 'marketplace'); ?></button>

                      </div>


                  </div>
            </form>
            </div>

        </div>

    </div>

    <?php

      $current_user = get_current_user_id();

      $customer_list  = get_users(array(
        'meta_key'   =>'favourite_seller',
          'meta_value' => $current_user
          ));

    ?>
        <div class="filter-data">
          <div class="mail-to-follower">
            <button type="button"><?php echo __('Send Notification', 'marketplace'); ?></button>
          </div>
          <div class="action-delete">
            <button type="button"><?php echo __('Delete Follower', 'marketplace'); ?></button>
          </div>
        </div>
      <table class="shop-fol">
        <thead>

          <tr>
            <th style="position:relative">
              <div class="select-all-box">
                  <div class="icheckbox_square-blue">
                        <input type="checkbox" class="mass-action-checkbox">
                        <ins class="iCheck-helper"></ins>
                    </div>
              </div>
              </th>
            <th class=""><?php esc_html_e( 'Customer Name', 'marketplace' ); ?></th>
            <th class=""><?php esc_html_e( 'Customer Email', 'marketplace' ); ?></th>
            <th class=""><?php esc_html_e( 'Action', 'marketplace' ); ?></th>
          </tr>

        </thead>

        <tbody>

        <?php

        if(!empty($customer_list)) :

          foreach ($customer_list as $ckey => $cvalue) {
              $user_id=$cvalue->data->ID;
              $customer_country=get_user_meta($user_id,'shipping_country',true);
               ?>
              <td>
                <div class=icheckbox_square-blue>
                        <input type=checkbox class="mass-action-checkbox">
                        <ins class=iCheck-helper></ins>
                    </div>
                  </td>
          <?php
              echo "<td>".$cvalue->data->display_name."</td>";
              echo "<td class='c-mail' data-cmail=".$cvalue->data->user_email.">".$cvalue->data->user_email."</td>";
              echo "<td><span class='remove-icon' data-customer-id='$user_id' data-seller-id=".$current_user."></span></td>";
            echo "</tr>";
          }


        else:

            echo "<tr><td><strong>";
            echo __('No Followers Available', 'marketplace');
            echo "</strong></td></tr>";

        endif;

        ?>

        </tbody>

      </table>

  </div>
