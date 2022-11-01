<h2>Woo Product Shortcode: Dashboard</h2>
Use our simple shortcode builder by choosing your product and attribute below.
<br /><br />
<?php

global $wpdb;

$admin_url = admin_url();

$table_name___posts = $wpdb->prefix . 'posts';

$sql = "SELECT ID, post_title FROM " . $table_name___posts . " WHERE post_type = 'product'";

$sql_results = $wpdb->get_results($sql);

if(count($sql_results) > 0) {

?>
    <select name="product" onChange="shortcode()" id="group">
        <option value="">-- PLEASE SELECT --</option>
        <?php foreach ( $sql_results as $sql_results_row ) { ?>
            <option value="<?php echo $sql_results_row->ID; ?>"><?php echo $sql_results_row->post_title; ?></option>
        <?php } ?>
    </select>
    
    <select name="attribute" onChange="shortcode()" id="group2">
        <option value="price">Product Price</option>
        <option value="name">Product Name</option>
        <option value="short_description">Product Short Description</option>
        <option value="sku">SKU</option>
        <option value="regular_price">Regular Price</option>
        <option value="sale_price">Sale Price</option>
        <option value="price_formatted">COMING SOON! (Premium) Price (with country formatting)</option>
        <option value="regular_price_formatted">COMING SOON! (Premium) Regular Price (with country formatting)</option>
        <option value="sale_price_formatted">COMING SOON! (Premium) Sale Price (with country formatting)</option>
    </select>

    <p id="woo_product_shortcode___builder_output"></p>
    <button onclick="copyToClipboard('#woo_product_shortcode___builder_output')" class="button button-default">Copy ShortCode</button>
    
    <br />
    <br />
    <br />

    <script>
        function shortcode() {
            var product = document.getElementById("group").value;
            var attribute = document.getElementById("group2").value;
            document.getElementById("woo_product_shortcode___builder_output").innerHTML = "[woo-product-shortcodes id=" + product + " data_attribute='" + attribute +"']";
        }
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }
    </script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

<?php } else { ?>
    Add some products to your store first to use our shortcode builder.
<?php } ?>

<h2>Spread the Word</h2>
Your feedback is valuable to us and we love to help with any issue you face.
<br />
<br />
<a href="mailto:support@thewpmakers.com?subject=Support Required for Woo Product Shortcodes (Free)" class="button button-default">Email Support</a>
<a href="mailto:support@thewpmakers.com?subject=New Feature Request for Woo Product Shortcodes (Free)" class="button button-default">Request New Feature</a>
<a href="https://wordpress.org/support/plugin/woo-product-shortcodes/reviews/#new-post" class="button button-default" target="_blank">Leave a Review</a>
<a href="https://en-gb.wordpress.org/plugins/woo-product-shortcodes/#reviews" class="button button-default" target="_blank">Check Our Reviews</a>
