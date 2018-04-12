<?php
  class VC_CFB_Custom_Param_CustomOptions extends VC_CFB_Custom_Param
  {
    function __construct()
    {
      $this->name = 'custom_options';
      $this->script = VC_CFB_Manager::$url.'/js/vc/custom_options.js';
      $this->css = VC_CFB_Manager::$url.'/css/vc/custom_options.css';
      
      parent::__construct();
    }

    public static function __render( $settings, $value )
    {
      $string = '';

      ob_start();
?>
      <div class="ch-wrap cfix">
        <input type="hidden" class="ch-hidden wpb_vc_param_value wpb-textinput <?= $settings['param_name'].' '.$settings['type'];?>_field block-json-content" name="<?= $settings['param_name'];?>" value="<?= $value;?>"/>
        <div class="ch-list cfix"></div>
        <div class="vc_cfb_buttons">
          <a href="#" class="ch-add"></a>
        </div>
        <template class="ch-template">
          <?= $settings['format'];?>
        </template>
      </div>
      <div>      
      </div>
<?php
      $string = ob_get_contents();
      ob_end_clean(); 

      return $string;
    }
  }