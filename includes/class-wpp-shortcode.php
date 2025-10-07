<?php
if (!defined('ABSPATH')) exit;

class WPP_Shortcode {

    public static function init() {
        add_shortcode('wpp_popular_products', [__CLASS__, 'render']);
    }

    public static function render($atts) {
        global $wpdb;

        $atts = shortcode_atts([
            'limit' => 5,
            'title' => __('Popular Today', 'woo-popular-products'),
        ], $atts, 'wpp_popular_products');

        $table_name = $wpdb->prefix . "product_views";

        $results = $wpdb->get_results($wpdb->prepare("
            SELECT product_id, COUNT(*) as views
            FROM $table_name
            WHERE view_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
            GROUP BY product_id
            ORDER BY views DESC
            LIMIT %d
        ", absint($atts['limit'])));

        ob_start();

        if (!empty($atts['title'])) {
            echo '<h3>' . esc_html($atts['title']) . '</h3>';
        }

        if ($results) {
            echo '<ul class="wpp-popular-products">';
            foreach ($results as $row) {
                $product = wc_get_product($row->product_id);
                if ($product) {
                    $svg_eye = '<svg fill="#000000" width="20px" height="20px" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <path d="M33.62,17.53c-3.37-6.23-9.28-10-15.82-10S5.34,11.3,2,17.53L1.72,18l.26.48c3.37,6.23,9.28,10,15.82,10s12.46-3.72,15.82-10l.26-.48ZM17.8,26.43C12.17,26.43,7,23.29,4,18c3-5.29,8.17-8.43,13.8-8.43S28.54,12.72,31.59,18C28.54,23.29,23.42,26.43,17.8,26.43Z"></path>
                        <path d="M18.09,11.17A6.86,6.86,0,1,0,25,18,6.86,6.86,0,0,0,18.09,11.17Zm0,11.72A4.86,4.86,0,1,1,23,18,4.87,4.87,0,0,1,18.09,22.89Z"></path>
                    </svg>';

                    echo '<li>';
                    echo '<a href="' . esc_url(get_permalink($product->get_id())) . '">'
                        . $product->get_image('thumbnail') . ' '
                        . esc_html($product->get_name()) .
                        '</a>';
                    echo '<span class="views">' . $svg_eye . ' ' . intval($row->views) . '</span>';
                    echo '</li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No data.', 'woo-popular-products') . '</p>';
        }

        return ob_get_clean();
    }
}
