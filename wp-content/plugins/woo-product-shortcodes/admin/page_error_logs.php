<h2>Page error logs</h2>
<div class="wrap">
<form method="post" action="#">
   <?php settings_fields( 'woo_product_shortcodes_group' ); ?>
   <?php do_settings_sections( 'woo_product_shortcodes_group' ); ?>
   <table class="form-table">
       <tr valign="top">
       <th scope="row">API Key</th>
       <td><input type="text" name="woo_product_shortcodes___api_key" value="<?php echo esc_attr( get_option('woo_product_shortcodes___api_key') ); ?>" /></td>
       </tr>
   </table>
   <?php submit_button(); ?>
</form>
</div>
<br />
<a href="<?php echo esc_url( admin_url( 'tools.php?page=product_shortcodes_main___dashboard' ) ); ?>" class="button button-info">Back to Dashboard</a>
$var___output = 'ERROR101: Invalid Data Attribute sent';
}
} elseif(empty($var___get_post_type)) {
$var___output = 'ERROR103: Invalid ID sent.';
}else {
$var___output = 'ERROR104: Invalid ID sent. Expecting a product but received ' . $var___get_post_type;
}
} else {
$var___output = 'ERROR102: No Product ID sent';
