<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$redirects = $args['redirects'] ?? [];
?>

<div class="overflow-x-auto">
    <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-3 px-4 text-left">От</th>
                <th class="py-3 px-4 text-left">К</th>
                <th class="py-3 px-4 text-left">Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($redirects as $from => $to): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-2 px-4">
                        <input type="text" name="<?php echo esc_attr($args['option_name']); ?>[redirects][from][]" 
                               value="<?php echo esc_attr($from); ?>" 
                               class="w-full px-2 py-1 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </td>
                    <td class="py-2 px-4">
                        <input type="text" name="<?php echo esc_attr($args['option_name']); ?>[redirects][to][]" 
                               value="<?php echo esc_attr($to); ?>" 
                               class="w-full px-2 py-1 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </td>
                    <td class="py-2 px-4">
                        <button type="button" class="remove-redirect px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Удалить
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<button type="button" id="add-redirect" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
    Добавить редирект
</button>