<?php

/**
 * @var string $field_key
 * @var string $field_value
 * @var array $settings
 *
 * @see \Epayco\Woocommerce\Gateways\AbstractGateway
 */

if (!defined('ABSPATH')) {
    exit;
}

?>

<span id='<?php echo  esc_attr($field_key); ?>' value='<?php echo  esc_attr($field_value); ?>'></span>
