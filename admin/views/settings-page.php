<?php
// settings-page.php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap custom-url-router-admin" x-data="{ activeTab: 'content-management' }">
    <h1 class="text-2xl font-bold mb-6"><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="custom-url-router-container">
        <div class="custom-url-router-sidebar">
            <ul class="custom-url-router-menu">
                <li class="custom-url-router-menu-item" :class="{ 'active': activeTab === 'content-management' }">
                    <a href="#content-management" @click.prevent="activeTab = 'content-management'">Content Management</a>
                </li>
                <li class="custom-url-router-menu-item" :class="{ 'active': activeTab === 'admin-interface' }">
                    <a href="#admin-interface" @click.prevent="activeTab = 'admin-interface'">Admin Interface</a>
                </li>
                <li class="custom-url-router-menu-item" :class="{ 'active': activeTab === 'redirects' }">
                    <a href="#redirects" @click.prevent="activeTab = 'redirects'">Redirects</a>
                </li>
            </ul>
        </div>
        
        <div class="custom-url-router-content">
            <form action="options.php" method="post">
                <?php settings_fields('custom_url_router_settings'); ?>
                
                <div x-show="activeTab === 'content-management'" class="custom-url-router-section">
                    <h2>Content Management</h2>
                    <?php do_settings_sections('custom-url-router-content'); ?>
                </div>
                
                <div x-show="activeTab === 'admin-interface'" class="custom-url-router-section">
                    <h2>Admin Interface</h2>
                    <?php do_settings_sections('custom-url-router-admin'); ?>
                </div>
                
                <div x-show="activeTab === 'redirects'" class="custom-url-router-section">
                    <h2>Redirects</h2>
                    <?php do_settings_sections('custom-url-router-redirects'); ?>
                </div>
                
                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
    </div>
</div>