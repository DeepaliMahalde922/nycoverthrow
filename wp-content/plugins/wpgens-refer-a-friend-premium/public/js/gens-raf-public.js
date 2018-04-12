(function( $ ) {
	'use strict';

	//Javascript GET cookie parameter
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	    function decode(s) {
	        return decodeURIComponent(s.split("+").join(" "));
	    }

	    $_GET[decode(arguments[1])] = decode(arguments[2]);
	});

    // Get time var defined in woo backend
    var $time = 1;
    if(typeof gens_raf !== 'undefined' && gens_raf.timee !== '') {
        $time = parseInt(gens_raf.timee);
    }

	//If raf is set, add cookie.
	if( typeof $_GET["raf"] !== 'undefined' && $_GET["raf"] !== null ){
		//console.log(window.location.hostname);
		cookie.set("gens_raf",$_GET["raf"],{ expires: $time, path:'/' });
	}

	// Share Shortcode
    $.fn.rafSocShare = function(opts) {
    	var $this = this;
    	var $win = $(window);
    	
    	opts = $.extend({
    		attr : 'href',
    		facebook : false,
    		google_plus : false,
    		twitter : false,
    		linked_in : false,
    		pinterest : false,
            whatsapp : false
    	}, opts);
    	
    	for(var opt in opts) {
    		
    		if(opts[opt] === false) {
    			continue;
    		}
    		
    		switch (opt) {
    			case 'facebook':
    				var url = 'https://www.facebook.com/sharer/sharer.php?u=';
    				var name = 'Facebook';
    				_popup(url, name, opts[opt], 400, 640);
    				break;
    			
    			case 'twitter':
                    var posttitle = $(".gens_raf_tw").data("title");
                    var via = $(".gens_raf_tw").data("via");
                    var url = 'https://twitter.com/intent/tweet?via='+via+'&text='+posttitle+'&url=';
    				var name = 'Twitter';
    				_popup(url, name, opts[opt], 440, 600);
    				break;
    			
				case 'google_plus':
    				var url = 'https://plus.google.com/share?url=';
    				var name = 'Google+';
    				_popup(url, name, opts[opt], 600, 600);
    				break;
    			
    			case 'linked_in':
    				var url = 'https://www.linkedin.com/shareArticle?mini=true&url=';
    				var name = 'LinkedIn';
    				_popup(url, name, opts[opt], 570, 520);
    				break;
				
				case 'pinterest':
    				var url = 'https://www.pinterest.com/pin/find/?url=';
    				var name = 'Pinterest';
    				_popup(url, name, opts[opt], 500, 800);
    				break;
                
                case 'mail':
                    var posttitle = $(".gens_raf_email").data("title");
                    var bodytext = $(".gens_raf_email").data("bodytext");
                    var url = 'mailto:?subject='+posttitle+'&body='+bodytext+' ';
                    var name = 'Email';
                    _popup(url, name, opts[opt], 500, 800);
                    break;

                case 'whatsapp':
                    var posttitle = $(".rafwhatsapp a").data("title");
                    var name = 'Whatsapp';
                    var url = 'whatsapp://send?text='+posttitle+'%20';
                    _popup(url, name, opts[opt], 500, 800);
				default:
					break;
    		}
    	}
    	
    	function _popup(url, name, opt, height, width) {
            if(opt !== false && $this.find(opt).length) {               
                $this.on('click', opt, function(e){
                    e.preventDefault();
                    
                    var top = (screen.height/2) - height/2;
                    var left = (screen.width/2) - width/2;
                    var share_link = $(this).attr(opts.attr);
                    
                    if(name != "whatsapp") {
                        window.open(
                            url+encodeURIComponent(share_link),
                            name,
                            'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height='+height+',width='+width+',top='+top+',left='+left
                        );
                    } else {
                        return false;
                    }
                    
                    return false;
                });
            }
        }
        return;
	};

	jQuery(document).ready(function(){
		$('.gens_raf_share').rafSocShare({
			facebook : '.gens_raf_fb',
			twitter : '.gens_raf_tw',
			google_plus : '.gens_raf_gplus',
			linked_in : '.gens_raf_linked',
			pinterest : '.gens_raf_pint',
	        mail : '.gens_raf_email',
	        whatsapp : '.gens_raf_whatsapp'
		});
	});

})( jQuery );