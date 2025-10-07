<?php
/**
 * Plugin Name: Woo Popular Products
 * Description: Display popular products based on WooCommerce user views over the last month.
 * Version: 1.0
 * Author: NikWeb
 * Text Domain: woo-popular-products
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // прямой доступ запрещён
}

// Подключаем нужные файлы
require_once plugin_dir_path(__FILE__) . 'includes/class-wpp-activator.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpp-tracker.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpp-widget.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpp-shortcode.php';
WPP_Shortcode::init();

// Хук активации
register_activation_hook(__FILE__, ['WPP_Activator', 'activate']);

// Локализация
add_action('plugins_loaded', 'wpp_load_textdomain');
function wpp_load_textdomain() {
    load_plugin_textdomain(
        'woo-popular-products',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}


// Инициализация трекера
add_action('template_redirect', ['WPP_Tracker', 'track_product_view']);

// Регистрация виджета
add_action('widgets_init', function () {
    register_widget('WPP_Popular_Products_Widget');
});
