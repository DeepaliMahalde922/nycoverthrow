<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
															</div>
														</td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        	<tr class="email_product_section_title">
                               <td align="center" valign="top">SHOP </td> 
                            </tr>
                            <tr class="email_product_section">    
                                <td style="padding-bottom:20px" align="center" valign="top">                                 
                                    <?php
                                        $args = array(
                                            'post_type' => 'product',
                                            'posts_per_page' => 12,
                                            'product_cat' => 'apparel'
                                            );
                                        $currency_symbol = get_woocommerce_currency_symbol();
                                        $loop = new WP_Query( $args );
                                        $loop_counter = 1;
                                        $third = "";
                                        if ( $loop->have_posts() ) {
                                            while ( $loop->have_posts() ) : $loop->the_post();
                                            if( $loop_counter % 3 == 0 ){
                                            	$third = " third";
                                            }else{
                                            	$third = "";
                                            }
                                            //echo "<div class='email_footer_products$third'>";
                                                //wc_get_template_part( 'content', 'product' );
                                            ?>
                                                <div class='email_footer_products<?php echo $third;?>'>
                                                <li style="list-style:none;" class="first type-product status-publish has-post-thumbnail hentry product_cat-apparel">

                                                    <div style="height:200px;max-height:200px" class="top-product-section">                                                            
                                                        <a href="<?php echo get_the_permalink(); ?>">
                                                            <?php the_post_thumbnail('medium'); ?>
                                                        </a>
                                                    </div>

                                                    <a class="product-category" href="<?php echo get_the_permalink(); ?>">                                                        
                                                        <?php $_product = wc_get_product( get_the_ID() ); ?>
                                                        <?php
                                                        	$price = get_post_meta( get_the_ID(), '_regular_price', true);
                                                            
                                                        	if( $_product->is_type( 'simple' ) || $_product->is_type( 'gift-card' ) ) {                                                        	
                                                        		$price = $_product->get_price_html();
                                                        	}else{
                                                        		$available_variations = $_product->get_available_variations();
                                                        		if( !empty($available_variations) ){
    																$count1 = 0;
    																foreach ($available_variations as $value) {
																        $variationnn_id = $available_variations[$count1]['variation_id'];
																        $product_variation = new WC_Product_Variation($variationnn_id);
																        $price = $product_variation->get_price_html();
																    }
    															}
                                                        	}
                                                        ?>
                                                        <?php if($price == ""){$price = "10.00";} ?>
                                                        <h6><?php echo get_the_title(); ?></h6>                                                        
                                                        <span class="price"><span class="amount"><?php echo $price; ?></span></span>
                                                    </a>
                                                </li>
                                            <?php
                                            echo '</div>';
                                            	$loop_counter++;
                                            endwhile;
                                        }
                                        wp_reset_postdata();
                                    ?>
                                </td>        
                            </tr>

                            <tr class="trainers_section">
                                <td align="center" valign="top">
                                    <a style="display:block;line-height:0;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" target="_blank" href="http://instagram.com/overthrownewyork">
                                        <img align="none" style="max-width: 975px;height: auto;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="https://overthrownyc.com/wp-content/uploads/2015/09/top_img.jpg">
                                    </a>
                                    <a style="display:block;line-height:0;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" target="_blank" href="<?php echo home_url(); ?>">
                                        <img align="none" style="max-width: 975px;height: auto;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="https://overthrownyc.com/wp-content/uploads/2015/09/bottom_img.jpg">
                                    </a>
                                    <div class="social_icons">
                                        <a target="_blank" style="font-size: 13px;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" href="http://instagram.com/overthrownewyork"><img width="45" height="45" align="none" style="width: 45px;height: 45px;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="<?php echo bloginfo('template_url'); ?>/img/instagram_email_template.jpg"></a>
                                        <a target="_blank" style="margin: 0 10px;font-size: 13px;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" href="https://www.facebook.com/EnjoyGoodDays?ref=hl"><img width="45" height="45" align="none" style="width: 45px;height: 45px;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="<?php echo bloginfo('template_url'); ?>/img/facebook_email_template.jpg"></a>
                                        <a target="_blank" style="font-size: 13px;word-wrap: break-word;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #6DC6DD;font-weight: normal;text-decoration: underline;" href="https://twitter.com/overthrownyc"><img width="45" height="45" align="none" style="width: 45px;height: 45px;margin: 0px;border: 0;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;" src="<?php echo bloginfo('template_url'); ?>/img/twitter_email_template.jpg"></a>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
