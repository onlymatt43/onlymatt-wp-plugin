<div class="wrap">
    <h1>ONLYMATT AI Settings</h1>

    <div class="card">
        <div class="card-header">
            <h5>Plugin Settings</h5>
        </div>
        <div class="card-body">
            <form id="onlymatt-settings-form">
                <div class="mb-3">
                    <label for="onlymatt-api-base" class="form-label">API Gateway URL</label>
                    <input type="url" class="form-control" id="onlymatt-api-base"
                           value="<?php echo esc_attr(get_option('onlymatt_api_base', '')); ?>"
                           placeholder="https://your-gateway.onrender.com">
                    <div class="form-text">URL of your ONLYMATT AI Gateway deployment</div>
                </div>
                <div class="mb-3">
                    <label for="onlymatt-admin-key" class="form-label">Admin API Key</label>
                    <input type="password" class="form-control" id="onlymatt-admin-key"
                           value="<?php echo esc_attr(get_option('onlymatt_admin_key', '')); ?>">
                    <div class="form-text">API key for admin operations</div>
                </div>
                <div class="mb-3">
                    <label for="onlymatt-max-memory" class="form-label">Max Memory Items</label>
                    <input type="number" class="form-control" id="onlymatt-max-memory"
                           value="<?php echo esc_attr(get_option('onlymatt_max_memory', '100')); ?>"
                           min="10" max="1000">
                    <div class="form-text">Maximum number of memory items to store</div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="onlymatt-enable-logging"
                           <?php checked(get_option('onlymatt_enable_logging', '1'), '1'); ?>>
                    <label class="form-check-label" for="onlymatt-enable-logging">
                        Enable Debug Logging
                    </label>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="onlymatt-enable-widget"
                           <?php checked(get_option('onlymatt_enable_widget', '1'), '1'); ?>>
                    <label class="form-check-label" for="onlymatt-enable-widget">
                        Enable Chat Widget on Frontend
                    </label>
                </div>
                <button type="button" class="btn btn-primary" id="onlymatt-save-settings">Save Settings</button>
            </form>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>System Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Plugin Version:</strong> <?php echo ONLYMATT_AI_VERSION; ?></p>
            <p><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></p>
            <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Plugin Directory:</strong> <?php echo ONLYMATT_AI_PLUGIN_DIR; ?></p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Shortcode Usage</h5>
        </div>
        <div class="card-body">
            <p>Use these shortcodes to embed the AI assistant on your pages:</p>
            <ul>
                <li><code>[onlymatt_chat]</code> - Display the chat widget</li>
                <li><code>[onlymatt_admin]</code> - Display admin interface (admin users only)</li>
            </ul>
            <p><strong>Example usage:</strong></p>
            <pre><code>[onlymatt_chat]</code></pre>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#onlymatt-save-settings').on('click', function() {
        const settings = {
            api_base: $('#onlymatt-api-base').val().trim(),
            admin_key: $('#onlymatt-admin-key').val().trim(),
            max_memory: parseInt($('#onlymatt-max-memory').val()) || 100,
            enable_logging: $('#onlymatt-enable-logging').is(':checked'),
            enable_widget: $('#onlymatt-enable-widget').is(':checked')
        };

        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_save_settings',
            settings: JSON.stringify(settings),
            nonce: onlymatt_ajax.nonce
        })
        .done(function() {
            alert('Settings saved successfully.');
        })
        .fail(function() {
            alert('Error saving settings.');
        });
    });
});
</script>