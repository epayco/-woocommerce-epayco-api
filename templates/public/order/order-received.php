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
<div id="transactionBody" >
    <div id="header" style="
    background: #000;
    position: static;
    height: 30px;
    border-radius: 20px;
">
        <div style="max-width: 600px;m;top: 10px;margin: 25px;/* padding: 129px; */font-family: 'Poppins', Arial, sans-serif;border: 1px solid #e5e5e5;border-radius: 0px 0px 10px 10px;padding: 20px;box-shadow: 0 0 10px rgba(0,0,0,0.1);background-color: #f9f9f9;z-index: 9999999;position: relative;">
            <!-- Encabezado -->
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="<?php echo esc_attr($iconUrl); ?>" alt="Éxito" style="display: block; margin: auto; width: 70px; border-bottom: 25px;">
                <h2 style="color: <?php echo esc_attr($iconColor); ?>; font-size: 22px; font-family: 'Poppins'; font-weight: bold"><?php echo esc_html($message); ?></h2>
                <h2 style="font-size: 22px; font-family: 'Poppins';font-weight: bold"><?php echo esc_html($epayco_refecence); ?> #<?php echo esc_html($refPayco); ?></h2>
                <p><?php echo esc_html($fecha); ?></p>
            </div>

            <!-- Separador -->
            <div style="border-top: 1px solid #e5e5e5; margin: 20px 0;"></div>

            <!-- Información de la transacción -->
            <p style="font-weight: bold; margin: 0; font-size: 16px; color: #000;"><?php echo esc_html($paymentMethod); ?></p>
            <div style="width: 100%;font-size: 14px;color: #333;border-collapse: collapse;line-height: 1.6;display: flex;flex-wrap: wrap;flex-direction: row;align-content: center;align-items: center;justify-content: space-between;">
                <div>
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($payment_method); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($card); ?></p>
                </div>
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($authorizations); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($authorization); ?></p>
                </div>
            </div>
            <div style="width: 100%;font-size: 14px;color: #333;border-collapse: collapse;line-height: 1.6;display: flex;flex-wrap: wrap;flex-direction: row;align-content: center;align-items: center;justify-content: space-between;">
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($receipt); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($factura); ?></p>
                </div>
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($iPaddress); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($ip); ?></p>
                </div>
            </div>
            <div style="width: 100%;font-size: 14px;color: #333;border-collapse: collapse;line-height: 1.6;display: flex;flex-wrap: wrap;flex-direction: row;align-content: center;align-items: center;justify-content: space-between;">
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($response); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($respuesta); ?></p>
                </div>
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"></h3>
                    <p style="margin: 2px 0px;"></p>
                </div>
            </div>

            <p style="font-weight: bold; margin: 0; font-size: 16px; color: #000;"><?php echo esc_html($purchase); ?></p>
            <div style="width: 100%;font-size: 14px;color: #333;border-collapse: collapse;line-height: 1.6;display: flex;flex-wrap: wrap;flex-direction: row;align-content: center;align-items: center;justify-content: space-between;">
                <div>
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($reference); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($refPayco); ?></p>
                </div>
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($description); ?></h3>
                    <p style="margin: 2px 0px;"><?php echo esc_html($descripcion_order); ?></p>
                </div>
            </div>
            <div style="width: 100%;font-size: 14px;color: #333;border-collapse: collapse;line-height: 1.6;display: flex;flex-wrap: wrap;flex-direction: row;align-content: center;align-items: center;justify-content: space-between;">
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"><?php echo esc_html($totalValue); ?></h3>
                    <p style="margin: 2px 0px;">$<?php echo esc_html($valor); ?> <?php echo esc_html($currency); ?></p>
                </div>
                <div style="margin: 10px 0px;">
                    <h3 style="font-size: 16px;font-family: 'Poppins';color: darkgray;margin: 1px 0px;"></h3>
                    <p style="margin: 2px 0px;"></p>
                </div>
            </div>

        </div>
        <?php endif; ?>
    </div>
</div>


<!-- Fuente personalizada -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
    #transactionBody{
        height: 550px;
        max-width: 510px;
        margin: auto;
        position: relative;
    }
    @media only screen and (max-width: 425px) {
        #transactionBody{
            padding-bottom: 200px;
        }
    }
</style>


