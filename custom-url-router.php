<?php
/*
Plugin Name: Custom URL Router
Description: Handles custom URL routes with case sensitivity
Version: 0.0.703.1800
Author: IC
*/


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
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
