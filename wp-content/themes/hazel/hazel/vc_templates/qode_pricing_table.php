<?php

$args = array(
    "title"         			=> "",
    "price"         			=> "100",
    "price_font_weight"       	=> "",
    "currency"      			=> "$",
    "price_period"  			=> "monthly",
    "link"          			=> "",
    "target"        			=> "_self",
    "button_text"   			=> "Buy Now",
    "button_size"   			=> "",
    "active"        			=> "",
    "active_text"   			=> "Best price"
);

extract(shortcode_atts($args, $atts));

$html 					= "";
$pricing_table_clasess 	= '';
$price_style_array		= array();
$price_style			= array();

if($target == ""){
    $target = "_self";
}

if($active == "yes") {
    $pricing_table_clasess .= ' active';
}

if($price_font_weight !== '') {
	$price_style_array[] = 'font-weight: '.$price_font_weight;
}

if(is_array($price_style_array) && count($price_style_array)) {
	$price_style = 'style="'.implode(';', $price_style_array).'"';
} else {
	$price_style = '';
}


$html .= "<div class='q_price_table ".$pricing_table_clasess."'>";
$html .= "<div class='price_table_inner'>";

if($active == 'yes') {
    $html .= "<div class='active_text'><span>".__($active_text, 'qode')."</span></div>";
}

$html .= "<ul>";
$html .= "<li class='cell table_title'><h3 class='title_content'>".$title."</h3>";

$html .= "<li class='prices'>";
$html .= "<div class='price_in_table'>";
$html .= "<sup class='value'>".$currency."</sup>";
$html .= "<span class='price' ".$price_style.">".$price."</span>";
$html .= "<span class='mark'>".$price_period."</span>";

$html .= "</div>"; // close div.price_in_table
$html .= "</li>"; //close li.prices

$html .= "<li class='pricing_table_content'>";
$html .= do_shortcode($content); //append pricing table content
$html .= "</li>";

$html .="<li class='price_button'>";
$html .= "<a class='$button_size' href='$link' target='$target'>".__($button_text, 'qode')."</a>";
$html .= "</li>"; //close li.price_button

$html .= "</ul>";
$html .= "</div>"; //close div.price_table_inner
$html .="</div>"; //close div.q_price_table

echo $html;