<?php

$example_url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] .'/';
if (strpos($example_url,'hazel1/') || strpos($example_url,'hazel7/') || strpos($example_url,'hazel9/') || strpos($example_url,'hazel14/')){
    $position = 'right';
}else{
    $position = 'left';
}

?>
    <div id="panel" class="default <?php echo $position; ?>" style="margin-<?php echo $position; ?>: -199px;">
        <div id="panel-admin">
            <h6>Pick a color</h6>
            <div class="panel-admin-box">
                <div class="accordion_toolbar">

                    <div class="accordion_toolbar_content">
                        <div id="tootlbar_colors">
                            <ul>
                                <li><div class="color active color1" data-color="#ecad81" style="background-color:#ecad81;"></div></li>
                                <li><div class="color color2" data-color="#79bc90" style="background-color:#79bc90;"></div></li>
                                <li><div class="color color3" data-color="#63a69f" style="background-color:#63a69f;"></div></li>
                                <li><div class="color color4" data-color="#7e8aa2" style="background-color:#7e8aa2;"></div></li>
                                <li><div class="color color5" data-color="#c84564" style="background-color:#c84564;"></div></li>
                                <li><div class="color color6" data-color="#49535a" style="background-color:#49535a;"></div></li>
                                <li><div class="color color7" data-color="#dc3522" style="background-color:#dc3522;"></div></li>
                                <li><div class="color color8" data-color="#d0a825" style="background-color:#d0a825;"></div></li>
                                <li><div class="color color9" data-color="#046380" style="background-color:#046380;"></div></li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a class="open" href="#"><span><i class="fa fa-cog"></i></span></a>
    </div>