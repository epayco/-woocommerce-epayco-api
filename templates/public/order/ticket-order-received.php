<?php

/**
 * Part of Epayco Sdk Module
 * Author - Epayco
 * Developer
 * Copyright - Copyright(c) Epayco [https://www.epayco.com]
 * License - https://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 *
 * @package Epayco
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
