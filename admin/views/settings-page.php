<?php
// settings-page.php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$custom_url_router = new IC_CustomURLRouter();
$redirects = $custom_url_router->get_redirects();
?>
<div class="flex flex-col space-y-6 p-6" x-data="{ 
    activeTab: 'content-management',
    newRedirect: { from: '', to: '' },
    isLoading: false,
    message: '',
    submitNewRedirect() {
    // Валидация
    if (!this.newRedirect.from || !this.newRedirect.to) {
        this.message = { type: 'error', text: 'Оба поля должны быть заполнены' };
        return;
    }

    // Подготовка данных
    const data = new FormData();
    data.append('action', 'add_new_redirect');
    data.append('from', this.newRedirect.from);
    data.append('to', this.newRedirect.to);
    data.append('nonce', customUrlRouterData.nonce); // Предполагается, что nonce передан из PHP

    // Установка состояния загрузки
    this.isLoading = true;
    this.message = '';

    // AJAX запрос
    fetch(ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.message = { type: 'success', text: 'Редирект успешно добавлен' };
            // Очистка формы
            this.newRedirect = { from: '', to: '' };
            // Здесь можно добавить код для обновления списка редиректов
        } else {
            this.message = { type: 'error', text: data.data || 'Произошла ошибка при добавлении редиректа' };
        }
    })
    .catch(error => {
        console.error('Error:', error);
        this.message = { type: 'error', text: 'Произошла ошибка при отправке запроса' };
    })
    .finally(() => {
        this.isLoading = false;
    });
}
}">
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
                    <div class="overflow-x-auto bg-white shadow-md rounded-lg mb-4">
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

                    <!-- Новая форма для добавления редиректа -->
                    <div class="bg-white shadow-md rounded-lg overflow-hidden w-1/2">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Добавить новый редирект</h3>
                        </div>
                        <div class="p-6">
                            <form @submit.prevent="submitNewRedirect">
                                <div class="">
                                    <div>
                                        <label for="new-redirect-from" class="block text-sm font-medium text-gray-700">От</label>
                                        <input type="text" id="new-redirect-from" x-model="newRedirect.from" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="new-redirect-to" class="block text-sm font-medium text-gray-700">К</label>
                                        <input type="text" id="new-redirect-to" x-model="newRedirect.to" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" :disabled="isLoading">
                                        <span x-show="!isLoading">Добавить редирект</span>
                                        <span x-show="isLoading">Добавление...</span>
                                    </button>
                                </div>
                            </form>
                            <div x-show="message" class="mt-4 p-4 rounded-md" :class="{ 'bg-green-100 text-green-700': message.type === 'success', 'bg-red-100 text-red-700': message.type === 'error' }">
                                <p x-text="message.text"></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php submit_button('Save Settings', 'primary', 'submit', true, ['class' => 'mt-6']); ?>
</div>