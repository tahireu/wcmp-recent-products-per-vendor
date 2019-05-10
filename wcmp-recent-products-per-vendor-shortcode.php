<?php
/**
 * Plugin Name:     WCMP Recent Products Per Vendor Shortcode
 * Description:     This <a href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/">WC Marketplace plugin</a> extension enables [wcmp_recent_products_per_vendor] shortcode for displaying specific number of recent products per vendor (default is 2) . You can optionally use it with "per_vendor" and "total" parameters, for example [wcmp_recent_products_per_vendor per_vendor="5" total="20"] .
 * Version:         1.0.0
 * Author:          Tahireu
 * Author URI:      https://github.com/tahireu/
 * License:         GPLv2 or later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
 */


/*
 * NOTE: RPPV stands for Recent Products Per Vendor
 * */

const TEXT_DOMAIN = "wcmp_rppv";


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'dc-woocommerce-multi-vendor/dc_product_vendor.php' ) ) {

    /*
     * Current plugin version - https://semver.org
     * This should be updated as new versions are released
     * */
    if( !defined( 'RPPV_VERSION' ) ) {
        define( 'RPPV_VERSION', '1.0.0' );
    }

    add_shortcode( 'wcmp_recent_products_per_vendor', 'rppv_wcmp_recent_products_per_vendor_callback' );
    function rppv_wcmp_recent_products_per_vendor_callback( $atts ){

        $users = get_users();
        $i = 0;
        $a = shortcode_atts( array(
            'per_vendor' => '2',
            'total' => '47'
        ), $atts );

        foreach ($users as $user) {

            $roles = $user->roles;

            foreach ($roles as $role) {
                if ($role == "dc_vendor") {

                    do_shortcode('[wcmp_recent_products per_page="' . $a['per_vendor'] . '" vendor="' . $user->id . '" ]');
                    $i++;

                    if ($i >= $a['total']) {
                        return;
                    }
                }
            }
        }
    }

} else {

    deactivate_plugins('/wcmp-recent-products-per-vendor-shortcode/wcmp-recent-products-per-vendor-shortcode.php');

    add_action('admin_notices', 'rppv_display_admin_notice_error');
    function rppv_display_admin_notice_error()
    {
        ?>
        <!-- hide the 'Plugin Activated' default message -->
        <style>
            #message.updated {
                display: none;
            }
        </style>
        <!-- display error message -->
        <div class="error">
            <p>
                <b><?php echo __('WCMP Recent Products Per Vendor Shortcode plugin could not be activated because WC Marketplace plugin is not installed and active.', TEXT_DOMAIN); ?></b>
            </p>
            <p><?php echo __('Please install and activate ', TEXT_DOMAIN); ?><a
                    href="https://wordpress.org/plugins/dc-woocommerce-multi-vendor/"
                    title="WC Marketplace plugin">WC Marketplace plugin</a><?php echo __(' before activating this plugin.', TEXT_DOMAIN); ?>
            </p>
        </div>
        <?php
    }
}