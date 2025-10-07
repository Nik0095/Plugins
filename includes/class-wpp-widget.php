<?php
if (!defined('ABSPATH')) exit;

class WPP_Popular_Products_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'wpp_popular_products',
            __('Popular products (WooCommerce)', 'woo-popular-products'),
            ['description' => __('Displays the most popular products for the last month', 'woo-popular-products')]
        );
    }

    public function widget($args, $instance) {
        global $wpdb;

        $limit = !empty($instance['limit']) ? absint($instance['limit']) : 5;
        $table_name = $wpdb->prefix . "product_views";

        $results = $wpdb->get_results($wpdb->prepare("
            SELECT product_id, COUNT(*) as views
            FROM $table_name
            WHERE view_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
            GROUP BY product_id
            ORDER BY views DESC
            LIMIT %d
        ", $limit));

        echo $args['before_widget'];
        echo $args['before_title'] . __("Most popular products", "woo-popular-products") . $args['after_title'];

        if ($results) {
            echo "<ul class='wpp-popular-products'>";
            foreach ($results as $row) {
                $product = wc_get_product($row->product_id);
                if ($product) {
                    echo "<li>
                        <a href='" . get_permalink($product->get_id()) . "'>" 
                            . $product->get_image('thumbnail') . " " 
                            . esc_html($product->get_name()) . 
                        "</a>
                        <span class='views'>" . sprintf(__('Views: %d', 'woo-popular-products'), $row->views) . "</span>
                    </li>";
                }
            }
            echo "</ul>";
        } else {
            echo "<p>" . __("No data.", "woo-popular-products") . "</p>";
        }

        echo $args['after_widget'];
    }

    public function form($instance) {
        $limit = !empty($instance['limit']) ? absint($instance['limit']) : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('limit')); ?>">
                <?php _e("Number of products:", "woo-popular-products"); ?>
            </label>
            <input type="number" 
                   id="<?php echo esc_attr($this->get_field_id('limit')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('limit')); ?>" 
                   value="<?php echo esc_attr($limit); ?>" 
                   min="1" style="width: 60px;">
        </p>
        <?php
    }
}
