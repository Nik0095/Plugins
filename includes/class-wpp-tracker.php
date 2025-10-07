<?php
if (!defined('ABSPATH')) exit;

class WPP_Tracker {
    public static function track_product_view() {
        if (is_product()) {
            global $post, $wpdb;
            $table_name = $wpdb->prefix . "product_views";

            $wpdb->insert(
                $table_name,
                [
                    'product_id' => $post->ID,
                    'view_date' => current_time('mysql')
                ],
                ['%d', '%s']
            );
        }
    }
}
