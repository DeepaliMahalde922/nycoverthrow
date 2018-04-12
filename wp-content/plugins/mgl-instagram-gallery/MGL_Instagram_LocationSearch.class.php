<?php
class MGL_Instagram_LocationSearch {

    public $lat,
           $lng,
           $debug,

           $locations;

    public $instagramAccount,
           $accessToken;

    public function __construct( $args ){
        extract(shortcode_atts(array(
            'lat'     => 41.387012,
            'lng'     => 2.170014,
            'debug' => 'false'
        ), $args));

        $this->lat = $lat;
        $this->lng = $lng;

        if($debug == 'true') { $this->debug = true; } else { $this->debug = false; }

        $this->instagramAccount = $this->getInstagramAccount();
        $this->accessToken = $this->getAccessToken();

        if( $this->accessToken ){
            $this->locations = $this->getLocations($lat, $lng, $debug);

            if( !empty( $this->locations ) ){
                $this->renderLocations( $this->locations );
            }else{
                _e( 'Locations not founded', 'mgl_instagram_gallery' );
            }

        }else{
            echo 'AccessToken unavailable';
        }
    }

    public function renderLocations( $locations ){
        // Start printing
        ob_start();
        ?>
           <table class="mgl_instagram_table">
               <thead>
                   <tr>
                       <th>ID</th>
                       <th>Name</th>
                       <th>Latitude</th>
                       <th>Longitude</th>
                   </tr>
               </thead>
               <tbody>
                <?php
                foreach($locations as $location)
                {
                     ?>
                     <tr>
                        <td><?php echo $location->id ?></td>
                        <td><?php echo $location->name ?></td>
                        <td><?php echo $location->latitude ?></td>
                        <td><?php echo $location->longitude ?></td>
                    </tr>
                       <?php
                }
                ?>
            </tbody>
           </table>
        <?php

        $result = ob_get_clean();

        echo $result;
    }

    public function getInstagramAccount(){
       return unserialize( base64_decode( get_option( 'mgl_instagram_userinfo' ) ) );
    }

    public function getAccessToken(){
        //$accessToken = $this->instagramAccount->access_token;
        $instagram_gallery_options = get_option('MGLInstagramGallery_option_name');
        $access_token = '';
        if(isset($instagram_gallery_options['configuration']['access_token'])) {
          $access_token = $instagram_gallery_options['configuration']['access_token'];
        }

        if( $access_token == '' ) {
            echo sprintf(__("Error: You don't have an access token, have you configurated correctly the plugin from %s?", 'mgl_instagram_gallery'), '<a href="'.site_url().'/wp-admin/options-general.php?page=mgl-instagram-gallery">'.__('here','mgl_instagram_gallery').'</a>');
            return false;
        }

        return $access_token;
    }

    public function getInstagramResponse( $url ){
        $request = new WP_Http;
        $result = $request->request(
            $url,
            array(
                'method' => 'GET',
                'headers' => array()
            )
        );

        if( $result['response']['code'] != 200 ){
            _e( 'Error: Cannot retrive places', 'mgl_instagram_gallery');
            return false;
        }

        return $result['body'];
    }

    public function getLocations(){
        $url = "https://api.instagram.com/v1/locations/search?lat=".$this->lat."&lng=".$this->lng."&access_token=".$this->accessToken;

        $instagramResponse = $this->getInstagramResponse( $url );
        $locations = json_decode( $instagramResponse )->data;

        if($this->debug == true) { echo '<pre>'; print_r($locations); echo '</pre>'; }

        return $locations;
    }
}
