<h2>Woo Product Shortcode: Settings</h2>

<div class="wrap">
    <form method="post" action="options.php">
        <?php settings_fields( 'woo_product_shortcodes_group' ); ?>
        <?php do_settings_sections( 'woo_product_shortcodes_group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">API Key</th>
                <td>
                    <input type="text" name="woo_product_shortcodes___api_key" value="<?php echo esc_attr( get_option('woo_product_shortcodes___api_key') ); ?>" />
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>

<a href="<?php echo esc_url(admin_url('tools.php?page=product_shortcodes_main___dashboard')); ?>" class="button button-info">Back to Dashboard</a>