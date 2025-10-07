<?php
if (!defined('ABSPATH')) exit;

class WPP_Activator {
    public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . "product_views";
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id BIGINT UNSIGNED NOT NULL,
            view_date DATETIME NOT NULL,
            PRIMARY KEY  (id),
            KEY product_id (product_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
