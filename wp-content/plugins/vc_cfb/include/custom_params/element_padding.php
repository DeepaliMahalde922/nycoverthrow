<?php
  class VC_CFB_Custom_Param_ElementPadding extends VC_CFB_Custom_Param
  {
    function __construct()
    {
      $this->name = 'element_padding';
      $this->script = VC_CFB_Manager::$url.'/js/vc/element_padding.js';
      $this->css = VC_CFB_Manager::$url.'/css/vc/element_padding.css';
      
      parent::__construct();
    }

    public static function __render( $settings, $value )
    {
      $string = '';

      ob_start();
?>
      <div class="vc_css-editor vc_css-element_padding vc_row_inner">
        <div class="vc_layout-onion vc_col-xs-12">
          <div class="vc_margin">
            <div class="vc_border">
              <div class="vc_padding">
                <input type="text" data-name="padding-top" class="cf-input vc_top" placeholder="-" value="">
                <input type="text" data-name="padding-right" class="cf-input vc_right" placeholder="-" value="">
                <input type="text" data-name="padding-bottom" class="cf-input vc_bottom" placeholder="-" value="">
                <input type="text" data-name="padding-left" class="cf-input vc_left" placeholder="-" value="">              
                <div class="vc_content">
                  <i></i>
                </div>          
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" class="cf-hidden wpb_vc_param_value wpb-textinput <?= $settings['param_name'].' '.$settings['type'];?>_field" name="<?= $settings['param_name'];?>" value="<?= $value;?>"/>
      </div>
<?php
      $string = ob_get_contents();
      ob_end_clean(); 

      return $string;
    }
  }