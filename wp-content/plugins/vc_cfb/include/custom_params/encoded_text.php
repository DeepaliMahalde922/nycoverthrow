<?php
  class VC_CFB_Custom_Param_EncodedText extends VC_CFB_Custom_Param
  {
    function __construct()
    {
      $this->name = 'encoded_text';
      $this->script = VC_CFB_Manager::$url.'/js/vc/encoded_text.js';
      
      parent::__construct();
    }

    public static function __render( $settings, $value )
    {
      $string = '';

      ob_start();
?>
      <div class="e-t-wrap">
        <input type="hidden" class="e-t-hidden wpb_vc_param_value wpb-textinput <?= $settings['param_name'].' '.$settings['type'];?>_field block-json-content" name="<?= $settings['param_name'];?>" value="<?= $value;?>"/>
        <?php if( isset($settings['is_textarea']) && $settings['is_textarea'] ): ?>
        <textarea class="wpb_vc_param_value wpb-textarea_raw_html textarea_raw_html e-t-text" rows="16"></textarea>
        <?php else: ?>
          <input type="text" class="e-t-text" name="" value="" />
        <?php endif;?>
      </div>
<?php
      $string = ob_get_contents();
      ob_end_clean(); 

      return $string;
    }
  }