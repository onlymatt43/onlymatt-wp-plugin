<div class="onlymatt-admin-panel">
    <h2>ONLYMATT AI Admin Panel</h2>

    <div class="onlymatt-admin-grid">
        <div class="onlymatt-admin-card">
            <h3>💬 Quick Chat</h3>
            <p>Send a quick message to the AI</p>
            <div class="mb-2">
                <input type="text" id="onlymatt-quick-message" class="form-control form-control-sm" placeholder="Ask something...">
            </div>
            <button class="onlymatt-btn" onclick="sendQuickMessage()">Send</button>
        </div>

        <div class="onlymatt-admin-card">
            <h3>🧠 Memory Status</h3>
            <p>Current memory items: <span id="memory-count">Loading...</span></p>
            <button class="onlymatt-btn" onclick="refreshMemoryStatus()">Refresh</button>
        </div>

        <div class="onlymatt-admin-card">
            <h3>📋 Recent Tasks</h3>
            <p>Active tasks: <span id="tasks-count">Loading...</span></p>
            <button class="onlymatt-btn" onclick="viewTasks()">View All</button>
        </div>

        <div class="onlymatt-admin-card">
            <h3>📊 Quick Report</h3>
            <p>Generate a usage summary</p>
            <button class="onlymatt-btn" onclick="generateQuickReport()">Generate</button>
        </div>
    </div>

    <div class="onlymatt-admin-card">
        <h3>⚙️ Quick Settings</h3>
        <div class="row">
            <div class="col-md-6">
                <label for="quick-api-url" class="form-label">API URL</label>
                <input type="url" class="form-control" id="quick-api-url"
                       value="<?php echo esc_attr(get_option('onlymatt_api_base', '')); ?>">
            </div>
            <div class="col-md-6">
                <label for="quick-api-key" class="form-label">API Key</label>
                <input type="password" class="form-control" id="quick-api-key"
                       value="<?php echo esc_attr(get_option('onlymatt_admin_key', '')); ?>">
            </div>
        </div>
        <button class="onlymatt-btn mt-2" onclick="saveQuickSettings()">Save Settings</button>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Load initial data
    refreshMemoryStatus();
    loadTaskCount();

    window.sendQuickMessage = function() {
        const message = $('#onlymatt-quick-message').val().trim();
        if (!message) return;

        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_chat',
            message: message,
            nonce: onlymatt_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                alert('AI Response: ' + (response.data.response || 'Message received!'));
            } else {
                alert('Error: Could not get AI response');
            }
            $('#onlymatt-quick-message').val('');
        })
        .fail(function() {
            alert('Error: Could not send message');
        });
    };

    window.refreshMemoryStatus = function() {
        // This would need a specific AJAX endpoint for memory count
        $('#memory-count').text('Feature coming soon');
    };

    window.viewTasks = function() {
        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_get_tasks',
            nonce: onlymatt_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                const tasks = response.data.tasks || [];
                let message = 'Recent Tasks:\n\n';
                tasks.slice(0, 5).forEach(task => {
                    message += `- ${task.title} (${task.status})\n`;
                });
                if (tasks.length > 5) message += `\n... and ${tasks.length - 5} more`;
                alert(message || 'No tasks found');
            } else {
                alert('Error loading tasks');
            }
        })
        .fail(function() {
            alert('Error loading tasks');
        });
    };

    window.generateQuickReport = function() {
        alert('Quick report generation feature coming soon!');
    };

    window.saveQuickSettings = function() {
        const settings = {
            api_base: $('#quick-api-url').val().trim(),
            admin_key: $('#quick-api-key').val().trim()
        };

        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_save_settings',
            settings: JSON.stringify(settings),
            nonce: onlymatt_ajax.nonce
        })
        .done(function() {
            alert('Settings saved successfully!');
        })
        .fail(function() {
            alert('Error saving settings');
        });
    };

    function loadTaskCount() {
        $.post(onlymatt_ajax.ajax_url, {
            action: 'onlymatt_get_tasks',
            nonce: onlymatt_ajax.nonce
        })
        .done(function(response) {
            if (response.success) {
                const tasks = response.data.tasks || [];
                const activeTasks = tasks.filter(task => task.status !== 'completed').length;
                $('#tasks-count').text(activeTasks);
            }
        });
    }
});
</script>