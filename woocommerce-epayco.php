<?php

/**
 * Plugin Name: ePayco Gateway
 * Plugin URI: https://github.com/epayco/Plugin_ePayco_WooCommerce
 * Description: Configure the payment options and accept payments with cards, ticket and PSE.
 * Version: 7.6.4
 * Author: ePayco
 * Author URI: http://epayco.co
 * Text Domain: woocommerce-epayco
 * Domain Path: /i18n/languages/
 * WC requires at least: 5.5.2
 * WC tested up to: 9.0.2
 * Requires PHP: 7.4
 *
 * @package Sdk
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once dirname(__FILE__) . '/src/Startup.php';

if (!Epayco\Woocommerce\Startup::available()) {
    return false;
}

require_once dirname(__FILE__) . '/vendor/autoload.php';

use Automattic\WooCommerce\Utilities\FeaturesUtil;
use Epayco\Woocommerce\WoocommerceEpayco;

add_action('before_woocommerce_init', function () {
    if (class_exists(FeaturesUtil::class)) {
        FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__);
    }

    if (class_exists(FeaturesUtil::class)) {
        FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__);
    }
});

if (!class_exists('WoocommerceEpayco')) {
    $GLOBALS['epayco'] = new WoocommerceEpayco();
}

register_activation_hook(__FILE__, 'mp_register_activate');
register_deactivation_hook(__FILE__, 'mp_disable_plugin');
register_activation_hook(__FILE__, 'activate_epayco_customer');
function mp_register_activate()
{
    update_option('_mp_execute_activate', 'yes');
}

function mp_disable_plugin(): void
{
    $GLOBALS['epayco']->disablePlugin();
}

add_action( 'woocommerce_admin_order_data_after_payment_info', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta( $order ){
    $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    if( null !== get_post_meta( $order_id, 'modo', true ) && null !== get_post_meta( $order_id, 'fecha', true )
        && null !== get_post_meta( $order_id, 'franquicia', true ) && null !== get_post_meta( $order_id, 'autorizacion', true )
    ){
        echo '<br>
    <h3>Detalle de la transacción</h3>
    <div>
        <div class="order_data_column_container">
            <div class="order_data_column">
                <div class="address">    
                    <p><strong>'.__('Pago con ePayco').':</strong> ' . get_post_meta( $order_id, 'refPayco', true ) . '</p>
                    <p><strong>'.__('Modo').':</strong> ' . get_post_meta( $order_id, 'modo', true ) . '</p>
                </div>
            </div>
            <div class="order_data_column">
                <div class="address">    
                    <p><strong>'.__('Fecha y hora transacción').':</strong> ' . get_post_meta( $order_id, 'fecha', true ) . '</p>
                    <p><strong>'.__('Franquicia/Medio de pago').':</strong> ' . get_post_meta( $order_id, 'franquicia', true ) . '</p>
                </div>
            </div>
            <div class="order_data_column">
                <div class="address">    
                    <p><strong>'.__('Código de autorización').':</strong> ' . get_post_meta( $order_id, 'autorizacion', true ) . '</p>
                </div>
            </div>
        </div>
    </div>
    ';
    }

}
function activate_epayco_customer()
{
    global $wpdb;
    $table_epayco_customer = $wpdb->prefix . 'epayco_customer';
    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_epayco_customer '") !== $table_epayco_customer) {

        $sql = "CREATE TABLE IF NOT  EXISTS $table_epayco_customer (
            id INT NOT NULL AUTO_INCREMENT,
            customer_id TEXT NULL,
            token_id TEXT NULL,
            email TEXT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta($sql);
    }
}