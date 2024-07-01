<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="wrap custom-url-router-admi">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('custom_url_router_settings');
        do_settings_sections('custom-url-router');
        submit_button('Save Settings');
        ?>
    </form>
</div>