<?php

/**
 * Part of Woo Epayco Module
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


<?php if ($status) : ?>
<div style="height: 82px; background: black; display: flex; justify-content: center; align-items: center;">
    <img src="<?php echo esc_attr($epayco_icon); ?>" alt="epayco" style="width: 115px; height: 30px;">
</div>
<div id="transactionBody" style="
    height: 787px !important;
    max-width: 550px !important;
    margin: auto !important;
    position: relative !important;
">

    <div id="header" style="
    background: #000;
    position: static;
    height: 30px;
    border-radius: 20px;
">
        <div
                style="max-width: 510px;m;max-height: 777px;top: 10px;margin: 25px;/* padding: 129px; */font-family: 'Poppins', Arial, sans-serif;border: 1px solid #e5e5e5;border-radius: 0px 0px 5px 5px;padding: 20px;box-shadow: 0 0 10px rgba(0,0,0,0.1);background-color: #f9f9f9;z-index: 9999999;position: relative;"
        >
            <!-- Encabezado -->
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="<?php echo esc_attr($iconUrl); ?>" alt="Éxito" style="display: block; margin: auto; width: 70px; border-bottom: 25px;">
                <h2 style="color: <?php echo esc_attr($iconColor); ?>; font-size: 22px; font-family: 'Poppins'; font-weight: bold"><?php echo esc_html($message); ?></h2>
                <h2 style="font-size: 22px; font-family: 'Poppins';font-weight: bold"><?php echo esc_html($epayco_refecence); ?> #<?php echo esc_html($refPayco); ?></h2>
                <p><?php echo esc_html($fecha); ?></p>
            </div>


            <!-- Información de la transacción -->
            <div style="margin: auto;max-height:455px; max-width: 380px" >
                <p style="font-weight: bold;margin-bottom: 19px;font-size: 16px;color: #000;"><?php echo esc_html($paymentMethod); ?></p>
                <div style="display: grid;grid-template-columns: repeat(2, 1fr);grid-template-rows: repeat(4,1fr);gap: 19px; justify-content: space-between;">
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($paymentMethod); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($card); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($authorizations); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($authorization); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($receipt); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($factura); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($iPaddress); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($ip); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($response); ?></h3>
                        <p style="font-size: 15px;font-family: 'Poppins';"><?php echo esc_html($status); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title">pin</h3>
                        <p class="descripcion-payment"><?php echo esc_html($pin); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"class="description-title"><?php echo esc_html($code); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($codeProject); ?></p>
                    </div>
                </div>


                <p style="font-weight: bold;margin: 19px 0px;font-size: 16px;color: #000;"><?php echo esc_html($purchase); ?></p>
                <div style="display: grid;grid-template-columns: repeat(2, 1fr);grid-template-rows: repeat(3,1fr);gap: 19px; justify-content: space-between;">
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($reference); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($refPayco); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($description); ?></h3>
                        <p class="descripcion-payment"><?php echo esc_html($descripcion_order); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"><?php echo esc_html($totalValue); ?></h3>
                        <p class="descripcion-payment">$<?php echo esc_html($valor); ?> <?php echo esc_html($currency); ?></p>
                    </div>
                    <div class="div-description">
                        <h3 class="description-title"></h3>
                        <p class="descripcion-payment"></p>
                    </div>
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
        max-width: 550px;
        margin: auto;
        position: relative;
    }
    .div-description{
        max-height: 46px;
        display: flex;
        flex-direction: column;
    }
    .description-title{
        font-size: 16px;
        font-family: 'Poppins';
        color: darkgray;
        margin: 0px;
    }
    .descripcion-payment{
        font-size: 15px;
        font-family: 'Poppins';
        margin: 0px;
    }

    @media only screen and (max-width: 425px) {
        #transactionBody{
            padding-bottom: 200px;
        }
    }
</style>


