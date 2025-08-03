<?php

namespace Epayco\Woocommerce\Helpers;

use WP_User;

if (!defined('ABSPATH')) {
    exit;
}

class Cron
{

    public function __construct()
    {
        $this->registerSyncStatusOrdersAction();
    }

    /**
     * Register an scheduled event
     *
     * @return void
     */
    public function registerScheduledEvent(string $periodicy, $hook): void
    {
        try {
            if (!wp_next_scheduled($hook)) {
                wp_schedule_event(time(), $periodicy, $hook);
            }
            if ( function_exists( 'as_next_scheduled_action' ) && false === as_next_scheduled_action( $hook ) ) {
                //as_schedule_recurring_action(time() + 3600, 3600, $hook );
            }
        } catch (\Exception $ex) {
            if ( class_exists( 'WC_Logger' ) ) {
                $logger = new \WC_Logger();
                $logger->add( 'ePayco',"Unable to unregister event {$hook}, got error: {$ex->getMessage()}" );
            }
        }
    }

    /**
     * Unregister an scheduled event
     *
     * @return void
     */
    public function unregisterScheduledEvent(string $hook): void
    {
        try {
            $timestamp = wp_next_scheduled($hook);
            if ($timestamp) {
                wp_unschedule_event($timestamp, $hook);
            }
            //wp_clear_scheduled_hook($hook);
            //as_unschedule_action($hook);

        } catch (\Exception $ex) {
            if ( class_exists( 'WC_Logger' ) ) {
                $logger = new \WC_Logger();
                $logger->add( 'ePayco',"Unable to unregister event {$hook}, got error: {$ex->getMessage()}" );
            }
        }
    }

    /**
     * Register schedules payment event
     *
     * @return void
     */
    public function registerSchedulesPaymentEvent(): void
    {
        if ( class_exists( 'WC_Logger' ) ) {
            $logger = new \WC_Logger();
            //$logger->add( 'ePaycoEvent',"se registra event epayco_event" );
        }
        add_filter('cron_schedules', function ( $schedules) {
            $schedules[ 'every_five_minutes' ] = array(
                'interval' => 300,
                'display'  => 'Every 5 minutes',
            );
            return $schedules;
        });

        if( !wp_next_scheduled( 'epaycoEvent' ) )
        {
            wp_schedule_event( time(), 'every_five_minutes', 'epaycoEvent' );
        }
    }

    public function registerSyncStatusOrdersAction(): void
    {
        add_action('epaycoEvent', function () {
            try {
                if ( class_exists( 'WC_Logger' ) ) {
                    $logger = new \WC_Logger();
                    //$logger->add( 'ePaycoEvent',"event epayco_event" );
                }
                do_action('epayco_sync_pending_status_order_action');
            } catch (\Exception $ex) {
                $error_message = "Unable to update batch of orders on action got error: {$ex->getMessage()}";

                if ( class_exists( 'WC_Logger' ) ) {
                    $logger = new \WC_Logger();
                    $logger->add( 'ePayco',$error_message);
                }

            }
        });
    }
}