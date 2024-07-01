# Custom URL Router

## Описание
Custom URL Router - это плагин WordPress, который обеспечивает гибкую маршрутизацию URL с учетом регистра символов. Плагин позволяет создавать кастомные URL-маршруты, сохраняя при этом совместимость с системой WordPress.

## Основные возможности
- Создание пользовательских URL-маршрутов с сохранением желаемого регистра символов
- Автоматическое перенаправление на URL с правильным регистром
- Поддержка редиректа с '/parent/' на '/parent/slugRegistry/'

## Установка
1. Загрузите папку `custom-url-router` в директорию `/wp-content/plugins/`.
2. Активируйте плагин через меню 'Плагины' в WordPress.
3. Плагин автоматически применит необходимые правила перезаписи URL.

## Использование
После активации плагин автоматически обрабатывает следующие маршруты:
- `parent/slugRegistry` -> `parent/slugregistry`
- `parent/eduStandart` -> `parent/edustandart`

Также выполняется редирект с `/parent/` на `/parent/slugRegistry/`.

## Настройка
Для добавления новых маршрутов отредактируйте массив `$custom_routes` в файле `custom-url-router.php`:

```php
private static $custom_routes = [
    'desired/URL' => 'corresponding/wordpress-slug',
];