<?php
/*
Plugin Name: Custom URL Router
Description: Handles custom URL routes with case sensitivity
Version: 0.7.04.1300
Author: IC
*/


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Добавляем ссылку "Настройки" на странице плагинов
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'custom_url_router_add_settings_link');

function custom_url_router_add_settings_link($links)
{
    $settings_link = '<a href="' . admin_url('options-general.php?page=custom-url-router') . '">Настройки</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Подключаем основной класс плагина
require_once plugin_dir_path(__FILE__) . 'includes/class-custom-url-router.php';

// Подключаем класс настроек
require_once plugin_dir_path(__FILE__) . 'admin/class-plugin-settings.php';

// Инициализация плагина
function run_custom_url_router()
{
    $plugin = new IC_CustomURLRouter();
    $plugin->init();

    $settings = new PluginSettings();
    $settings->init();
}

run_custom_url_router();

// Хуки активации и деактивации
register_activation_hook(__FILE__, [IC_CustomURLRouter::class, 'activate']);
register_deactivation_hook(__FILE__, [IC_CustomURLRouter::class, 'deactivate']);
