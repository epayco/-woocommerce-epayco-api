<?php

/**
 * Part of Woo Sdk Module
 * Author - Sdk
 * Developer
 * Copyright - Copyright(c) Sdk [https://www.epayco.com]
 * License - https://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 *
 * @package Sdk
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<p>
<p>
    <?php echo esc_html($print_ticket_label); ?>
</p>
<p><iframe src="<?php echo esc_attr($transaction_details); ?>" style="width:100%; height:600px;"></iframe></p>
</p>
