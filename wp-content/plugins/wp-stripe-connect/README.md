1. **INSTALLATION PROCESS**
	* Download Wordpress WooCommerce Marketplace Stripe Connect from (WEBKUL-Wordpress-Woocommerce-Marketplace-Stripe-Connect ) [https://store.webkul.com/]
	* ```Important!!``` woocommerce and marketplace plugin should be installed and activated before activating woocommerce-marketplace-stripe-connect
	* Steps For Uploading Zip file.
		1. Download zip file from http://webkul.com.
		2. Unzip the file.
		3. Select the following files and directories
			* assets
			* includes
			* readme.txt
			* license.txt
			* stripe-connect.php
			* payment-stripe.png
			* index.htm
		4. Now make a zip file selecting all mentioned files and directories and rename the zip file as stripe-connect
		5. After making zip file you should have a zip file name **stripe-connect.zip** with all mentioned files i.e assets, includes, readme.txt, license.txt, payment-stripe.png, index.htm, and stripe-connect.php in it.
		6. Goto wordpress admin dashboard.
		7. Goto add new plugin.
		8. Goto upload plugin and browse to stripe-connect.zip file.
		9. After uplaoding it activate plugin.
2. **SETTING DESCRIPTION**
	* After activating Marketplace -
		* *Inside Admin dashboard
			* * Now goto woocommerce menu -> setting -> checkout -> Stripe Connect.
			* * Fill in all important options necessary details for plugin i.e Client id (both production and development) , Secret Key (Test/Live) and Publishable Key (Test/Live).*
			* * Now enable Stripe Connect for getting payment via stripe connect.
3. **VERSION**
	* version 1.0.1
		* woocommerce 3.0 compatible version.
	* version 1.0
		* The current version.

4. **FEATURES**
	* Wordpress WooCommerce Marketplace Stripe Connect :
		1. Working with stripe supported currencies
		2. Better Security as given by oauth stripe. For creating token when receiving card details, so that no information of card could be received by our own server.
		3. Email notification on transaction
		4. Support for almost all kind of cards including Visa, Mastercard, American Express, Discover etc.
		5. Well integrated with WordPress WooCommerce Marketplace.
		6. No Modification required in core files
		7. Check and utilized both Test and Live server modes on the payment gateway
