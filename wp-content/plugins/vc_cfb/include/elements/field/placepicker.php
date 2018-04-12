<?php
  class VC_CFB_Element_Field_Placepicker extends VC_CFB_Element_Field
  {
    protected $placeholder;
    protected $tab;
    protected $url;
    protected $size;
    protected $map = false;
    protected $latitude;
    protected $longitude;
    protected $mobile = false;

    function __construct( $name ) 
    {
      parent::__construct( $name );
      
      $this->type = 'placepicker';
    }

    protected function __default_attr( $attr )
    {
      return shortcode_atts( array(
        'name'                    => '',
        'api_key'                 => '',
        'map'                     => false,
        'map_height'              => '300px',
        'map_country'             => '',
        'language'                => '',
        'tab'                     => '',
        'required'                => false,
        'autofocus'               => false,
        'readonly'                => false,
        'mobile'                  => false,
        'latitude'                => '',
        'longitude'               => '',
        'placeholder'             => '',
        'size'                    => '',
        'classes'                 => '',
        'id'                      => '',
        'icon_align'              => 'left',
        'icon_type'               => 'fontawesome',
        'icon_color'              => '',
        'map_rotatecontrol_posistion'         => '',
        'map_maptypecontrol_posistion'        => '',
        'map_maptypecontrol_style'            => 'DEFAULT',
        'map_streetviewcontrol_posistion'     => '',
        'map_zoomcontrol_posistion'           => '',
        'map_zoomcontrol_size'                => 'LARGE',
        'map_pancontrol_posistion'            => '',
        'map_scalecontrol'                    => false,
        'map_draggable'                       => false,
        'add_icon'                => false,
        'icon_fontawesome'        => '',
        'icon_openiconic'         => '',
        'icon_typicons'           => '',
        'icon_entypo'             => '',
        'icon_linecons'           => '',
        'label'                   => '',
        'label_position'          => 'left',
        'label_width'             => 1,
      ), $attr );
    }

    protected function __set_attr( $attr, $content )
    {
      $attr = self::__default_attr( $attr );
      
      parent::__set_attr( $attr, $content );
      $this->__set_value();
      
      $this->size = $attr['size'];
      $this->latitude = str_replace( ',', '.', urldecode( $attr['latitude'] ) );
      $this->longitude = str_replace( ',', '.', urldecode( $attr['longitude'] ) );
      $this->mobile = (bool)$attr['mobile'];
      
      if( isset($attr['map']) && (bool)$attr['map'] ):
        $this->map = array( 'height' => $attr['map_height'], 'country' => $attr['map_country'], 'draggable' => (bool)$attr['map_draggable'] );

        if( empty($this->value['lat']) )
          $this->latitude = empty($this->latitude) ? 1 : $this->latitude;
        else
          $this->latitude = $this->value['lat'];

        if( empty($this->value['lng']) )
          $this->longitude = empty($this->longitude) ? 1 : $this->longitude;
        else
          $this->longitude = $this->value['lng'];


        foreach( array( 'panControl', 'streetViewControl', 'rotateControl', 'mapTypeControl', 'zoomControl' ) as $key ):
          $index = mb_strtolower('map_'.$key.'_posistion');
          if( isset($attr[$index]) && !empty($attr[$index]) )
            $this->map[$key] = array( 'position' => $attr[$index] );
          else
            $this->map[$key] = FALSE;
          unset($index);
        endforeach;
        
        if( isset($attr['map_zoomcontrol_posistion']) && !empty($attr['map_zoomcontrol_posistion']) )
          $this->map['zoomControl']['size'] = $attr['map_zoomcontrol_size'];

        if( isset($attr['map_maptypecontrol_posistion']) && !empty($attr['map_maptypecontrol_posistion']) )
          $this->map['mapTypeControl']['style'] = $attr['map_maptypecontrol_style'];

        if( isset($attr['map_scalecontrol']) )
          $this->map['scaleControl'] = (bool)$attr['map_scalecontrol'];
      endif;

      $url = array();
      if( !empty($attr['api_key']) )
        $url[] = 'key='.$attr['api_key']; 
      if( !empty($attr['language']) )
        $url[] = 'language='.$attr['language'];

      $url[] = 'libraries=places';

      $this->url = 'http://maps.googleapis.com/maps/api/js'.( sizeof($url) > 0 ? '?'.implode('&', $url) : '' );
    }

    public function __init_vc()
    {
      $this->settings['name'] = __( 'Place Picker', 'vc_cfb' );
      $this->settings['icon'] = VC_CFB_Manager::$url.'/images/elements/field/'.$this->type.'.png';
      $this->settings['params'] = VC_CFB_Shortcode::__params( 'field/placepicker' );
      $this->settings['description'] = __( 'Select location with Google Map', 'vc_cfb' );
      
      parent::__init_vc();
    }

    protected function __validate() 
    {
      if( $this->required && $this->value['address'] == '' ):
        $this->valid = FALSE; 
        $this->validation_messages[] = 'required';
        return;
      endif;

      if( $this->value != '' )
        if( !preg_match( '/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/i', $this->value['lat'] ) || !preg_match( '/^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/i', $this->value['lng'] ) ):
          $this->valid = FALSE; 
          $this->validation_messages[] = 'invalid';
          return;
        endif;
    
      $this->valid = TRUE;
    }

    protected function __render_wrap()
    {
      if( $this->valid === FALSE )
        $this->__show_error_message( array( 'field', 'all' ) ); 
    
      echo apply_filters( 'cfb_before_field', '', $this );
        $this->__render();
      echo apply_filters( 'cfb_after_field', '', $this );
      echo ( $this->map !== FALSE ? '<div id="field_'.$this->name.'_map" '.( !$this->mobile ? 'class="vc_hidden-xs"' : '' ).' style="width:100%;height:'.$this->map['height'].';"></div>' : '' ); 
    }

    protected function __set_value()
    {
      if( isset($_REQUEST[$this->name]) )
        $this->value['address'] = htmlspecialchars($_REQUEST[$this->name]);
      else
        $this->value['address'] = '';

      if( $this->value['address'] != '' )
      {
        if( isset($_REQUEST[$this->name.'_lat']) )
          $this->value['lat'] = htmlspecialchars($_REQUEST[$this->name.'_lat']);

        if( isset($_REQUEST[$this->name.'_lng']) )
          $this->value['lng'] = htmlspecialchars($_REQUEST[$this->name.'_lng']);
      }else{
        $this->value['lat'] = '';
        $this->value['lng'] = '';
      }

      $this->value = apply_filters( 'cfb_field_set_value', $this->value, $this->name, $this->type, $this );
    }

    protected function __render()
    {
      $this->__render_label( 'start' );
      ?>
      <script type="text/javascript">
        (function($) {
          $(document).ready(function() {
            map = $("form input[name=<?= $this->name;?>]").geocomplete({
              details: ".map-details-<?= $this->name;?>",
              detailsAttribute: "data-geo",
              <?php if( $this->map !== FALSE ): ?>
                <?= 'map: "#field_'.$this->name.'_map",';?>
                <?= ( !empty($this->map['country']) ? 'country: "'.$this->map['country'].'",' : '' );?>
                mapOptions: {
                  disableDefaultUI: true,
                  <?php 
                    foreach( array( 'panControl', 'streetViewControl', 'scaleControl', 'rotateControl', 'mapTypeControl', 'zoomControl' ) as $key )
                      if( $this->map[$key] == FALSE )
                        echo $key.': false,'."\r\n";
                      else{
                        echo $key.': true,'."\r\n";
                        echo $key.'Options: {';
                            if( !empty($this->map[$key]['position']) )
                              echo 'position: google.maps.ControlPosition.'.$this->map[$key]['position'].',';
                            if( $key == 'mapTypeControl' )
                              echo 'style: google.maps.MapTypeControlStyle.'.$this->map[$key]['style'].',';
                            if( $key == 'zoomControl' )
                              echo 'style: google.maps.ZoomControlStyle.'.$this->map[$key]['size'].',';
                        echo '},'."\r\n";
                      }
                  ?>
                },
                markerOptions: {
                  draggable: <?= self::__val_to_string( $this->map['draggable'] );?>
                }
              <?php endif; ?>
            });
            $("form input[name=<?= $this->name;?>]").geocomplete( "find", "<?= $this->latitude;?>,<?= $this->longitude;?>" );
            $("form input[name=<?= $this->name;?>]").bind("geocode:dragged", function(event, latLng){
              geocoder = new google.maps.Geocoder;
              latlng = {lat: latLng.lat(), lng: latLng.lng()};
              geocoder.geocode({'location': latlng}, function(results, status) { 
                if (status === google.maps.GeocoderStatus.OK)
                  if( results[0] )
                    $("form input[name=<?= $this->name;?>]").geocomplete( "find", results[0].formatted_address );
              });
            });
          });
        })(jQuery);
      </script>
      <div class="vc-cfb-null-wrap map-details-<?= $this->name;?>">
        <input type="hidden" name="<?= $this->name;?>_lat" value="" data-geo="lat"/>
        <input type="hidden" name="<?= $this->name;?>_lng" value="" data-geo="lng"/>
      </div>
      <?php
        $this->__show_icon();
      ?>
      <input 
        type="text"
        name="<?= $this->name;?>" 
        <?= !empty($this->placeholder) ? 'placeholder="'.$this->placeholder.'"' : ''; ?>
        <?= !empty($this->tab) ? 'tabindex="'.$this->tab.'"' : ''; ?>
        value="<?= $this->value['address'] == '' ? $this->default : $this->value['address']; ?>"
        <?= $this->__field_id();?>
        <?= $this->__field_classes();?>
        <?= !empty($this->size) ? 'size="'.$this->size.'"' : '' ;?>
        <?= (bool)$this->readonly ? 'readonly' : '' ;?>
        <?= (bool)$this->autofocus ? 'autofocus' : '' ;?>
        autocomplete="off"
        <?= (bool)$this->required ? 'required' : '' ;?>
        <?= apply_filters( 'cfb_'.$this->type.'_field_custom_attribute', '', $this ); ?>
      />
      <?php
        $this->__render_label( 'end' );
    }

    public function __get_value()
    {
      return apply_filters( 'cfb_field_get_value', $this->value['address'].' ( '.$this->value['lat'].', '.$this->value['lng'].' )', $this->name, $this->type, $this );
    }

    protected function __enqueue_custom_element_scripts()
    { 
      wp_register_script( 'vc_cfb_field_google_map', $this->__get_api_url( 'vc_cfb_field_google_map' ) );   
      wp_enqueue_script( 'vc_cfb_field_google_map' );

      wp_register_script( 'vc_cfb_field_placepicker', VC_CFB_Manager::$url.'/js/modules/geocomplete/jquery.geocomplete.min.js', array( 'vc_cfb_field_google_map' ), '1.6.5' );   
      wp_enqueue_script( 'vc_cfb_field_placepicker' );
    }
  }