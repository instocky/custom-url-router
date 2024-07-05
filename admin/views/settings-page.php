<?php
// settings-page.php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$custom_url_router = new IC_CustomURLRouter();
$redirects = $custom_url_router->get_redirects();
?>
<div class="flex flex-col space-y-6 p-6" x-data="{ activeTab: 'content-management' }">
    <h1 class="text-2xl font-bold"><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="flex">
        <div class="w-1/4 pr-6">
            <!-- Навигация -->
            <nav class="flex flex-col space-y-2 text-xl">
                <a href="#" @click.prevent="activeTab = 'content-management'" :class="{ 
           'font-bold text-black border-r-[3px] border-blue-700': activeTab === 'content-management', 
           'text-gray-700 hover:bg-blue-100': activeTab !== 'content-management' 
       }" class="px-4 py-2 rounded-l transition duration-200">Content Management</a>
                <a href="#" @click.prevent="activeTab = 'admin-interface'" :class="{ 
           'font-bold text-black border-r-[3px] border-blue-700': activeTab === 'admin-interface', 
           'text-gray-700 hover:bg-blue-100': activeTab !== 'admin-interface' 
       }" class="px-4 py-2 rounded-l transition duration-200">Admin Interface</a>
                <a href="#" @click.prevent="activeTab = 'redirects'" :class="{ 
           'font-bold text-black border-r-[3px] border-blue-700': activeTab === 'redirects', 
           'text-gray-700 hover:bg-blue-100': activeTab !== 'redirects' 
       }" class="px-4 py-2 rounded-l transition duration-200">Redirects</a>
            </nav>
        </div>

        <div class="w-3/4 border-l-4 border-gray-200 pl-4">
            <!-- Контент -->
            <div x-show="activeTab === 'content-management'">
                <h2 class="text-xl font-semibold mb-4">Content Management</h2>
                <?php do_settings_sections('custom-url-router-content'); ?>
            </div>

            <div x-show="activeTab === 'admin-interface'" x-cloak>
                <h2 class="text-xl font-semibold mb-4">Admin Interface</h2>
                <?php do_settings_sections('custom-url-router-admin'); ?>
            </div>

            <div x-show="activeTab === 'redirects'" x-cloak>
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Текущие редиректы</h3>
                    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">От</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">К</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($redirects as $from => $to) : ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html($from); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo esc_html($to); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php submit_button('Save Settings', 'primary', 'submit', true, ['class' => 'mt-6']); ?>
</div>