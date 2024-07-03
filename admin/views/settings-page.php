<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap custom-url-router-admin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="custom-url-router-container">
        <div class="custom-url-router-sidebar">
            <ul class="custom-url-router-menu">
                <li class="custom-url-router-menu-item active">
                    <a href="#content-management">Content Management</a>
                </li>
                <li class="custom-url-router-menu-item">
                    <a href="#admin-interface">Admin Interface</a>
                </li>
                <li class="custom-url-router-menu-item">
                    <a href="#redirects">Redirects</a>
                </li>
            </ul>
        </div>
        
        <div class="custom-url-router-content">
            <form action="options.php" method="post">
                <?php settings_fields('custom_url_router_settings'); ?>
                
                <div id="content-management" class="custom-url-router-section">
                    <h2>Content Management</h2>
                    <?php do_settings_sections('custom-url-router-content'); ?>
                </div>
                
                <div id="admin-interface" class="custom-url-router-section">
                    <h2>Admin Interface</h2>
                    <?php do_settings_sections('custom-url-router-admin'); ?>
                </div>
                
                <div id="redirects" class="custom-url-router-section">
                    <h2>Redirects</h2>
                    <?php do_settings_sections('custom-url-router-redirects'); ?>
                </div>
                
                <?php submit_button('Save Settings'); ?>
            </form>
        </div>
    </div>
</div>