<?php
/*
Plugin Name: Custom URL Router
Description: Handles custom URL routes with case sensitivity
Version: 0.0.701.1040
Author: IC
*/

if (!class_exists('IC_CustomURLRouter')) {
    class IC_CustomURLRouter
    {
        /**
         * Массив кастомных маршрутов.
         * Ключ - желаемый URL, значение - соответствующий WordPress slug.
         *
         * @var array
         */
        private static $custom_routes = [
            'parent/slugRegistry' => 'parent/slugregistry',
            'parent/eduStandart' => 'parent/edustandart',
        ];

        /**
         * Инициализация плагина.
         * Подключает все необходимые хуки.
         *
         * @return void
         */
        public static function init()
        {
            add_action('init', [self::class, 'register_routes']);
            add_filter('request', [self::class, 'handle_custom_routes']);
            add_action('template_redirect', [self::class, 'redirect_incorrect_case']);
            add_action('template_redirect', [self::class, 'redirect_parent_to_slugregistry']);
        }

        /**
         * Регистрирует правила перезаписи для кастомных маршрутов.
         *
         * @return void
         */
        public static function register_routes()
        {
            foreach (self::$custom_routes as $custom_route => $wp_route) {
                add_rewrite_rule(
                    '^' . $custom_route . '/?$',
                    'index.php?pagename=' . $wp_route . '&custom_route=' . $custom_route,
                    'top'
                );
            }
        }

        /**
         * Обрабатывает кастомные маршруты в запросе.
         * 
         * @param array $query_vars Переменные запроса WordPress
         * @return array Модифицированные переменные запроса
         */
        public static function handle_custom_routes($query_vars)
        {
            if (isset($query_vars['custom_route'])) {
                $query_vars['pagename'] = self::$custom_routes[$query_vars['custom_route']];
            }
            return $query_vars;
        }

        /**
         * Выполняет редирект на URL с правильным регистром.
         *
         * @return void
         */
        public static function redirect_incorrect_case()
        {
            global $wp;
            $current_url = trailingslashit(home_url($wp->request));

            foreach (self::$custom_routes as $custom_route => $wp_route) {
                $correct_url = trailingslashit(home_url($custom_route));
                if (strtolower($current_url) === strtolower($correct_url) && $current_url !== $correct_url) {
                    wp_redirect($correct_url, 301);
                    exit;
                }
            }
        }

        /**
         * Выполняет редирект с /parent/ на /parent/slugregistry/
         *
         * @return void
         */
        public static function redirect_parent_to_slugregistry()
        {
            global $wp;
            $current_url = trailingslashit(home_url($wp->request));
            $parent_url = trailingslashit(home_url('parent'));

            if ($current_url === $parent_url) {
                wp_redirect(home_url('parent/slugRegistry/'), 301);
                exit;
            }
        }

        /**
         * Сбрасывает правила перезаписи WordPress.
         * Вызывается при активации плагина.
         *
         * @return void
         */
        public static function flush_rules()
        {
            self::register_routes();
            flush_rewrite_rules();
        }
    }

    IC_CustomURLRouter::init();
    register_activation_hook(__FILE__, [IC_CustomURLRouter::class, 'flush_rules']);
    register_deactivation_hook(__FILE__, 'flush_rewrite_rules');
}
