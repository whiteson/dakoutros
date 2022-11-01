=== Plugin Name ===
Contributors: escitsupport
Donate link:
Tags: woo commerce, shortcodes, product
Requires at least: 4.3
Tested up to: 5.3.1
Stable tag: 2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Instead of using static content for product names, prices or SKU - use a shortcode instead to save changing the text again.


== Description ==

Sometimes you refer to a product in a post, page or on your homepage with reference to a price, product name or SKU.
With this plugin, you can replace the static text with a shortcode so the values are pulled dynamically from your woocommerce store.
Especially useful when referring to the price as this can change regularly leaving old static text out of date.

To get started, use the shortcode to show the price:
[woo-product-shortcodes id="20" data_attribute="price"]

Use can use data_attributes for "name", "short_description", "sku", "price", "regular_price", "sale_price" and more. See FAQ for details. 

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use our easy shortcode builder at TOOLS > WOO PRODUCT SHORTCODES
4. On a page or post, add the shortcode to show product information. See below in FAQ for shortcode examples.


== FAQ // Frequently Asked Questions ==

= What shortcode do I use? =

[woo-product-shortcodes id="20" data_attribute=“price”]

There are two variables to the shortcode:
1) id = the product id from the woocommerce shop
2) data_attribute = this defined what text will be shown - see below for possible options

= What data attributes can I use? =

data_attribute="name"
This will show the name of the product

data_attribute="short_description"
This will show the short description of the product

data_attribute="sku"
This will show the SKU of the product

data_attribute="price"
This will show the price of the product. If the product is on sale, then the sale price will be shown automatically.

data_attribute="regular_price"
This will show the regular price of the product

data_attribute="sale_price"
This will show the sale price of the product


== Error Codes ==

= ERR001: No valid attribute defined. =
REASON: This is triggered when you have not defined which data_attribute to return and show on the page/post.
FIX: Add the data_attribute variable to the shortcode with a permitted value

= ERR002: Invalid data_attribute defined. =
REASON: This is triggered when you have defined data_attribute but its not a permitted value
FIX: Read the list of permitted data_attribute options from FAQ and update your shortcode with a correct data_attribute


== Screenshots ==

1. This screen shot shows the setup of the shortcode which could be used
screenshot-1.png

2. This screen shot shows the output of the shortcode
screenshot-2.png


== Changelog ==

= 2.2 =
* Minor changes post internal review
= 2.1 =
* Complete change to logic post WooCommerce upgrade
= 1.1 =
* Updates to images and tags
= 1.0 =
* Initial release version


== Upgrade Notice ==

= 1.0 =
New.