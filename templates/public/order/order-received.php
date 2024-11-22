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


<?php if ($status) : ?>
    <div style="max-width: 600px; margin: auto; font-family: 'Poppins', Arial, sans-serif; border: 1px solid #e5e5e5; border-radius: 10px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); background-color: #f9f9f9;">
        <!-- Encabezado -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://www.citypng.com/public/uploads/preview/hd-green-round-tick-check-mark-vector-icon-png-701751694973140j6wd4pfqgl.png" alt="Éxito" style="display: block; margin: auto; width: 70px; border-bottom: 25px;">
            <h2 style="color: #28a745; font-size: 22px; font-family: 'Poppins';"><?php echo esc_html($success_message); ?> <span style="color: #000;"><?php echo esc_html($valor); ?> <?php echo esc_html($currency); ?></span></h2>
            <!--<p style="color: #666; font-size: 14px;"><?php echo esc_html($name); ?></p>-->
        </div>

        <!-- Separador -->
        <div style="border-top: 1px solid #e5e5e5; margin: 20px 0;"></div>

        <!-- Información de la transacción -->
        <p style="font-weight: bold; margin: 0; font-size: 16px; color: #000;">REF. <?php echo esc_html($factura); ?></p>
        <table style="width: 100%; margin-top: 10px; font-size: 14px; color: #333; border-collapse: collapse; line-height: 1.6;">
            <tr>
                <td style="padding: 5px 0;"><?php echo esc_html($payment_method); ?></td>
                <td style="text-align: right;"><?php echo esc_html($card); ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 0;"><?php echo esc_html($dateandtime); ?>:</td>
                <td style="text-align: right;"><?php echo esc_html($fecha); ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 0;"><?php echo esc_html($statusandresponse); ?>:</td>
                <td style="text-align: right;"><?php echo esc_html($estado); ?> - <?php echo esc_html($respuesta); ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">Ref ePayco:</td>
                <td style="text-align: right;"><?php echo esc_html($refPayco); ?></td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">IVA:</td>
                <td style="text-align: right;"><?php echo esc_html($iva); ?> <?php echo esc_html($currency); ?></td>
            </tr>
            <tr style="font-weight: bold;">
                <td style="padding: 5px 0;">Total:</td>
                <td style="text-align: right;"><?php echo esc_html($valor); ?> <?php echo esc_html($currency); ?></td>
            </tr>
        </table>
    </div>
    <br> <br>
<?php else : ?>
    <div style="max-width: 600px; margin: auto; font-family: 'Poppins', Arial, sans-serif; text-align: center; color: #d9534f; background-color: #fef2f2; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="font-size: 20px; font-weight: bold; font-family: 'Poppins';"><?php echo esc_html($error_message); ?></h2>
        <p style="font-size: 14px; font-family: 'Poppins';"><?php echo esc_html($error_description); ?></p>
    </div>
<?php endif; ?>

<!-- Fuente personalizada -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
</style>


