<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$from_id = "redirect_from_{$index}";
$to_id = "redirect_to_{$index}";
?>
<div class="redirect-row">
    <label for="<?php echo esc_attr($from_id); ?>">From:</label>
    <input type="text" id="<?php echo esc_attr($from_id); ?>"
           name="<?php echo esc_attr($args['option_name'] . "[redirects][{$index}][from]"); ?>"
           value="<?php echo esc_attr($redirect['from']); ?>" class="regular-text">

    <label for="<?php echo esc_attr($to_id); ?>">To:</label>
    <input type="text" id="<?php echo esc_attr($to_id); ?>"
           name="<?php echo esc_attr($args['option_name'] . "[redirects][{$index}][to]"); ?>"
           value="<?php echo esc_attr($redirect['to']); ?>" class="regular-text">

    <?php submit_button('Remove', 'delete', "remove_redirect_{$index}", false, ['class' => 'button-secondary remove-redirect']); ?>
</div>