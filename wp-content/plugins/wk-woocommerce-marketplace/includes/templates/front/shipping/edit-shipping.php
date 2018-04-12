<?php

  global $wpdb;

  $user_id=get_current_user_id();

  $mainpage = get_query_var( 'main_page' );

  $zone_id=get_query_var('zone_id');

  $zones=new WC_Shipping_Zone($zone_id);

  $shop_address=get_user_meta($user_id,'shop_address',true);

  $table_name_check = $wpdb->prefix . "mpseller_meta";

  $seller_zone_check = $wpdb->get_results( "SELECT * FROM $table_name_check where seller_id=".$user_id." and zone_id=".$zone_id);

  $zone_name=$zones->get_zone_name();

  $zone_locations=$zones->get_zone_locations();

  $final_obj=new SaveShipingOptions();

  if( $mainpage == $shop_address && ! empty( $seller_zone_check ) ) :

  ?>

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

            <input type="submit" name="update_shipping_details" class="button button-primary wc-shipping-zone-update" value="<?php esc_attr_e( 'Update changes', 'woocommerce' ); ?>" />

          </td>

        </tr>

      </tfoot>

      <tbody class="wc-shipping-zone-rows ui-sortable">
      <?php
        // if(!empty($zone_locations)) :
                $ship_locations=$final_obj->get_formatted_location($zone_locations);
                $ship_locations=explode(",", $ship_locations);
                $ship_code_array=$final_obj->get_formatted_code($zone_locations);
                $ship_code_array=explode(",", $ship_code_array);
          ?>

        <tr class="final-editing">

          <td><label for="mp_zone_name"><?php echo _e("Zone Name", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>

          <td class="wc-shipping-zone-name">

            <input type="text" name="mp_zone_name" value="<?php echo $zone_name;?>" data-attribute="zone_name"  placeholder="Zone Name">

          </td>

        </tr>



        <tr>

          <td class="wc-shipping-zone-region"><label for="mp_zone_region"><?php echo _e("Zone Region", "marketplace");?><span class="required">*</span>&nbsp;&nbsp;:</label></td>

          <input type="hidden" name="hidden_user" value="<?php echo $user_id; ?>">

          <td class="wc-shipping-zone-region">
             <div class="edit">
              <div class="mp_select_country">
                <?php
                            $i=0;
                            foreach ($ship_locations as $key_location => $value_location) {
                                echo '<div class="mp_ship_tags" data-value='.$ship_code_array[$i].'>'.$value_location.'<a class="mp_del_tag">x</a></div>';
                                $i++;
                              }
                          ?>
                          <input type="text" name="new_zone_locations" id="unused_elm" placeholder="Select regions within this zone" class="live-search-box" placeholder="search here" />
                          <input type="hidden" value="<?php echo $zone_id; ?>" name="mp_zone_id" />
                          <input type="hidden" id="mp_set_zone_location" name="zone_locations" value="<?php
                            $prefix = '';
                            foreach ($zone_locations as $zone_key => $zone_value) {
                              $arr = $prefix.$zone_value->type.":".$zone_value->code;
                              echo $arr;
                              $prefix=',';
                              }
                           ?>">
                          <ul class="live-search-list"></ul>
              </div>
              <a class="wc-shipping-zone-postcodes-toggle" href="#"><?php echo __('Limit to specific ZIP/postcodes', 'marketplace'); ?></a>
              <div class="wc-shipping-zone-postcodes">
                <textarea name="zone_postcodes" placeholder="List 1 postcode per line" class="input-text large-text" cols="25" rows="5"></textarea>
                <span class="description"><?php echo __('Postcodes containing wildcards (e.g. CB23*) and fully numeric ranges (e.g. <code>90210...99000</code>) are also supported.', 'marketplace'); ?></span>
              </div>
              </div>

          </td>
        </tr>

        <tr>

          <td><label for="mp_zone_shipping"><?php echo _e("Shipping Method", "marketplace");?>&nbsp;&nbsp;:</label></td>

          <td class="wc-shipping-zone-methods shipping-extended">

            <div>

              <ul>
                <?php

                  $methods   = $zones->get_shipping_methods();

                  if ( ! empty( $methods ) ) {

                    foreach ( $methods as $method ) {

                      $settings_html = $method->generate_settings_html( $method->get_instance_form_fields(), false );

                      $ship_slug=$method->get_rate_id();

                      $ship_slug=explode(':', $ship_slug); ?>

                      <div id="modal-ship-rate<?php echo $ship_slug[1]; ?>" style="display:none">

                        <div class="shipping-method-add-cost">

                            <table class="form-table">

                              <?php echo $settings_html; ?>

                            </table>

                          <input type="hidden" name="instance_id" value="<?php echo $method->instance_id; ?>">

                          <button class='button button-primary btn-save-cost' ><?php echo __('Save Changes', 'marketplace'); ?></button>

                        </div>

                      </div>

                    <?php	$class_name = 'yes' === $method->enabled ? 'method_enabled' : 'method_disabled';
                        echo '<li class="wc-shipping-zone-method"><a href="#TB_inline?width=800&height=500&inlineId=modal-ship-rate'.$ship_slug[1].'" class="' . esc_attr( $class_name ) . ' thickbox" title="'.esc_html( $method->get_title() ).' Setting">' . esc_html( $method->get_title() ) . '</a></li>';
                      }
                    } else {
                    echo '<p>' . __( 'No shipping methods offered to this zone.', 'marketplace' ) . '</p>';
                  }

                  add_thickbox();

                 ?>

                <li class="wc-shipping-zone-methods-add-row"><a href="#TB_inline?width=600&height=280&inlineId=modal-window-id" class="thickbox add_shipping_method tips" title="Add Shipping Method" data-tip="<?php esc_attr_e( 'Add shipping method', 'marketplace' ); ?>" data-disabled-tip="<?php esc_attr_e( 'Save changes to continue adding shipping methods to this zone', 'marketplace' ); ?>"><?php _e( 'Add shipping method', 'marketplace' ); ?></a></li>
              </ul>



              <div id="modal-window-id" style="display:none">

                <div class="shipping-method-modal">
                  <br />
                  <p><?php echo __('Choose the shipping method you wish to add. Only shipping methods which support zones are listed.', 'marketplace'); ?></p>


                    <select name="add_method_id" id="add_method_id" data-get-zone="<?php echo $zone_id; ?>">
                    <?php
                        global $woocommerce;

                        $shipping_methods = $woocommerce->shipping->load_shipping_methods();
                        foreach ($shipping_methods as $key => $value) {
                          echo "<option value='$value->id'>".$value->method_title."</option>";
                        }
                        ?>
                    </select>

                  <br />
                  <br />
                  <p><strong><?php echo __('Lets you charge a fixed rate for shipping.', 'marketplace'); ?></strong></p>

                  <button class='button button-primary add-ship-method'><?php echo __('Add Shipping Method', 'marketplace'); ?></button>

                </div>

              </div>


            </div>
          </td>
        </tr>

      <?php

      // endif;

      ?>

      </tbody>

    </table>

  </form>

<?php else : ?>

 <h1>Cheating huh ???</h1>
 <p>Sorry, You can't edit other seller's shipping zone.</p>

<?php endif; ?>
