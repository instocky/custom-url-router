<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div id="custom-url-router-redirects">
    <?php
    $redirects = $args['redirects'] ?? [];
    foreach ($redirects as $index => $redirect):
        require __DIR__ . '/single-redirect.php';
    endforeach;
    ?>
</div>
<?php submit_button('Add Redirect', 'secondary', 'add_redirect', false); ?>