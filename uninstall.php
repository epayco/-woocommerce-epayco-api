<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;

// Validar el nombre de la tabla (aunque esté basado en $wpdb->prefix, asegúrate de que no sea inyectado)
$table_subscription_epayco = $wpdb->prefix . 'epayco_subscription';

// Escapar manualmente el nombre de la tabla para evitar inyecciones
$table_safe = esc_sql( $table_subscription_epayco );

// Ejecutar directamente, no es necesario prepare() aquí
$wpdb->query( "DROP TABLE IF EXISTS `$table_safe`" );
