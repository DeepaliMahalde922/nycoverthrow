<?php


  $user_id=get_current_user_id();

  ?>

  <ul id='edit_ship_tab' class="edit-mp-tab">

      <li><a data-link-id='ship_zones'><?php echo _e("Shipping Zone", "marketplace"); ?></a></li>

      <li><a data-link-id='ship_class'><?php echo _e("Shipping Classes", "marketplace"); ?></a></li>

  </ul>

  <div class="shipping-container" id="ship_zones">

    <form action="" method="post">

       <?php wp_nonce_field( 'shipping_action', 'shipping_nonce' ); ?>

        <table class="wc-shipping-zones widefat">

          <thead>
            <tr>

            </tr>
          </thead>

          <tfoot>

            <tr>

              <td colspan="4">

                <input type="submit" name="save_shipping_details" class="button button-primary wc-shipping-zone-save" value="<?php esc_attr_e( 'Add shipping zone', 'marketplace' ); ?>" />
              </td>

            </tr>

          </tfoot>

          <tbody class="wc-shipping-zone-rows ui-sortable">


            <tr>

              <td class="wc-shipping-zones-blank-state" colspan="4">

                <p class="main"><?php echo __('A shipping zone is a geographic region where a certain set of shipping methods and rates apply.', 'marketplace'); ?></p>

                <p><?php echo __('For example', 'marketplace'). ':'; ?></p>

                <ul>
                  <li><?php echo __('Local Zone = California ZIP 90210 = Local pickup', 'marketplace'); ?></li>
                  <li><?php echo __('US Domestic Zone = All US states = Flat rate shipping', 'marketplace'); ?></li>
                  <li><?php echo __('Europe Zone = Any country in Europe = Flat rate shipping', 'marketplace'); ?></li>
                </ul>

                <p></php echo __('Add as many zones as you need – customers will only see the methods available for their address.'.'marketplace'); ?></p>

              </td>

            </tr>

            <tr>

              <td><label for="mp_zone_name"><?php echo _e("Zone Name", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>

              <td class="wc-shipping-zone-name">

                <input type="text" name="mp_zone_name" data-attribute="zone_name"  placeholder="Zone Name">

              </td>

            </tr>

            <tr>

              <td><label for="mp_zone_region"><?php echo _e("Zone Name", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>

              <td class="wc-shipping-zone-region">
                 <div class="edit">
                  <div class="mp_select_country">

                    <input type="text" name="new_zone_locations" id="unused_elm" placeholder="Select regions within this zone" class="live-search-box" placeholder="search here" />
                    <input type="hidden" name="mp_zone_id" />
                    <input type="hidden" id="mp_set_zone_location" name="zone_locations" value="">
                      <ul class="live-search-list"></ul>
                  </div>

                  <a class="wc-shipping-zone-postcodes-toggle" href="#"><?php echo __('Limit to specific ZIP/postcodes', 'marketplace'); ?></a>

                  <div class="wc-shipping-zone-postcodes">
                    <textarea name="zone_postcodes" placeholder="List 1 postcode per line" class="input-text large-text" cols="25" rows="5"></textarea>

                    <input type="hidden" name="hidden_user" value="<?php echo $user_id; ?>">

                    <span class="description"><?php echo __('Postcodes containing wildcards (e.g. CB23*) and fully numeric ranges (e.g. <code>90210...99000</code>) are also supported.', 'marketplace'); ?></span>
                  </div>
                  </div>
              </td>

            </tr>
            <tr>

              <td>
                <label><?php echo __('Add Shipping Method', 'marketplace'); ?></label>
              </td>

              <td class="wc-shipping-zone-methods">
                <div>
                  <ul>
                    <li class="wc-shipping-zone-method"><?php echo __('You can add shipping method once you have create shipping zone.', 'marketplace'); ?></li>
                  </ul>
                </div>
              </td>

            </tr>


          </tbody>


        </table>

    </form>

  </div>

  <div class="shipping-container" id="ship_class">

    <?php

      $shipping_class_columns = apply_filters( 'woocommerce_shipping_classes_columns', array(
        'wc-shipping-class-name'        => __( 'Shipping Class', 'marketplace' ),
        'wc-shipping-class-slug'        => __( 'Slug', 'marketplace' ),
        'wc-shipping-class-description' => __( 'Description', 'marketplace' ),
        'wc-shipping-class-count'       => __( 'Product Count', 'marketplace' ),
      ) );

    ?>
    <form id="ship_data">
      <br>
      <h2>
        <?php _e( 'Shipping Classes', 'woocommerce' ); ?>
        <?php echo wc_help_tip( __( 'Shipping classes can be used to group products of similar type and can be used by some Shipping Methods (such as Flat Rate Shipping) to provide different rates to different classes of product.', 'marketplace' ) ); ?>
      </h2>

      <table class="wc-shipping-classes widefat">

        <thead>

          <tr>

            <?php foreach ( $shipping_class_columns as $class => $heading ) : ?>

              <th class="<?php echo esc_attr( $class ); ?>"><?php echo esc_html( $heading ); ?></th>

            <?php endforeach; ?>

          </tr>

        </thead>

        <tfoot>

          <tr>

            <td colspan="<?php echo absint( sizeof( $shipping_class_columns ) ); ?>">

              <input type="submit" name="save" class="button button-primary wc-shipping-class-save" value="<?php esc_attr_e( 'Save Shipping Classes', 'marketplace' ); ?>" disabled />

              <a class="button button-secondary wc-shipping-class-add" href="#"><?php esc_html_e( 'Add Shipping Class', 'marketplace' ); ?></a>

            </td>

          </tr>

        </tfoot>

        <tbody class="wc-shipping-class-rows">

          <?php
              $ship_class_obj = new WC_Shipping();

              $ship_classes = $ship_class_obj->get_shipping_classes();

              $user_shipping_classes = get_user_meta($user_id,'shipping-classes',true);

              if (!empty($user_shipping_classes)) :

                $u_shipping_classes = maybe_unserialize($user_shipping_classes);


                foreach ($ship_classes as $ship_key => $ship_value) :


                    if(in_array($ship_value->term_id, $u_shipping_classes)):

                      ?>

                      <tr data-id="<?php echo $ship_value->term_id;?>">

                        <?php

                          foreach ( $shipping_class_columns as $class => $heading ) {

                            echo '<td class="' . esc_attr( $class ) . '">';

                            switch ( $class ) {
                              case 'wc-shipping-class-name' :
                                ?>
                              <div class="view">

                              <?php echo $ship_value->name;?>

                                <div class="row-actions">
                                  <a class="wc-shipping-class-edit" href="#"><?php _e( 'Edit', 'marketplace' ); ?></a>
                                </div>
                              </div>
                              <div class="edit">
                                <input type="hidden" value="<?php echo $ship_value->term_id;?>" name="term_id[<?php echo $ship_value->term_id;?>]">
                                <input type="text" name="name[<?php echo $ship_value->term_id;?>]" data-attribute="name" value="<?php echo $ship_value->name;?>" placeholder="<?php esc_attr_e( 'Shipping Class Name', 'marketplace' ); ?>" />
                              </div>
                              <?php
                            break;
                            case 'wc-shipping-class-slug' :
                              ?>
                              <div class="view"> <?php echo $ship_value->slug;?> </div>
                              <div class="edit"><input type="text" name="slug[<?php echo $ship_value->term_id;?>]" data-attribute="slug" value="<?php echo $ship_value->slug;?>" placeholder="<?php esc_attr_e( 'Slug', 'marketplace' ); ?>" /></div>
                              <?php
                            break;
                            case 'wc-shipping-class-description' :
                              ?>
                              <div class="view"><?php echo $ship_value->description;?></div>
                              <div class="edit"><input type="text" name="description[<?php echo $ship_value->term_id;?>]" data-attribute="description" value="<?php echo $ship_value->description;?>" placeholder="<?php esc_attr_e( 'Description for your reference', 'marketplace' ); ?>" /></div>
                              <?php
                            break;
                            case 'wc-shipping-class-count' :
                              ?>
                              <a href="javascript:void(0)"> <?php echo $ship_value->count;?>  </a>
                              <?php
                            break;
                            default :
                              do_action( 'woocommerce_shipping_classes_column_' . $class );
                            break;
                          }
                          echo '</td>';
                        }
                      ?>
                    </tr>

              <?php	endif;


                endforeach;

              endif;
          ?>


        </tbody>

      </table>

    </form>

  </div>
