<?php
// Template for onlymatt_hey_hi shortcode in the github clone.
$site = isset($site_info) ? $site_info : array();
?>
<div class="onlymatt-hey-hi-widget">
    <h3><?php echo esc_html( $site['site_title'] ?? get_bloginfo('name') ); ?></h3>
    <p><?php echo esc_html( $site['site_description'] ?? get_bloginfo('description') ); ?></p>
    <div class="onlymatt-hey-hi-actions">
        <button class="onlymatt-hey-hi-open">Dire bonjour</button>
    </div>
</div>
