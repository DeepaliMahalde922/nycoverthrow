=== Simple Mobile URL Redirect ===
Contributors: insideoutweb
Donate link: NA
Tags: mobile, redirect, url
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 1.7

This plugin allows you to put in a full path URL to redirect mobile traffic to.

== Description ==

A simple mobile redirect plugin for those who use a separate website URL for their mobile content.  Simply put in the full path URL and you are ready to send users to your mobile-only content.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `mobile-redirect.php` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to Settings > Mobile Redirect.
1. Check `Enable Redirect`.
1. Put in a full path URL (inluding http://) to the website you would like to redirect mobile traffic to.
1. Select the method of redirect you want.  301 is generally the best option.
1. Hit `Save` and you are done.

== Frequently Asked Questions ==

= I've disabled the plugin but the redirect is still happening, how do I fix this? =

First, check if this is happening on another computer. If not, the redirect is simply cached on your machine. Clear the cache and you should be fine.

If the redirect remains everywhere, check if there are other redirect scripts running.

= Can I link to pages inside of my site? =

(v1.1) Yes, but mobile users will be stuck only viewing that page as the plugin will redirect them back there if they navigate away.
You will still need to use a full path as well, and make sure the URL is exactly as what will be redirected including / at the end if necessary.
As of v1.2, you can use the Redirect Once option to redirect users to an internal page on their first visit, afterward they'll be able to browse the site normally.

= Does this redirect iPad traffic? =

Yes. The built in WordPress function redirects tablets as mobile

== Changelog ==

= 1.7.1 =
* Fixed home page only redirect
* Fixed various bugs in WordPress 4.1+
* Removed tablet option and moved entirely to wp_is_mobile() function

= 1.6.1 =
* Reverted a fix that broke redirect home page only

= 1.6 =
* Fixed issues with caching

= 1.5 =
* Added support for windows phones
* Added redirect home page option
* Added json redirect support
* Tested tablet support, appears to be working as intended

= 1.4.1 =
* Fixed php notice HTTP_ALL

= 1.4 =
* Added support to chose how to redirect tablet traffic

= 1.3 =
* Updated included instructions

= 1.2 =
* Code clean up
* Use core's mobile detection
* New option to redirect only once

= 1.1 =
* Fixed bug with missing functon is_mobile()

= 1.0 =
* Stable version and includes 301/302 option

== Screenshots ==
1. The admin page

== Upgrade Notice ==

= 1.4 =
Adds tablet redirect option.

= 1.2 =
Adds "Redirect Once" option.

= 1.0 =
No upgrades at this time.
