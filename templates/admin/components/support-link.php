<?php

/**
 * @var array $settings
 *
 * @see \Epayco\Woocommerce\Gateways\AbstractGateway
 */

if (!defined('ABSPATH')) {
    exit;
}

?>


<p  class="ep-support-link-text">
   <span class="ep-support-link-bold_text"><?php echo esc_html($settings['bold_text']) ?></span>
   <span><?php echo  esc_html($settings['text_before_link']) ?></span>
   <span><a href="<?php echo  esc_html($settings['support_link']) ?>" target="_blank" class="ep-support-link-text-with-link"><?php echo  esc_html($settings['text_with_link']) ?></a></span>
   <span><?php echo  esc_html($settings['text_after_link']) ?></span>
</p>
