<div class="wrap">
    <h1>ONLYMATT AI Dashboard</h1>

    <div class="onlymatt-admin-grid">
        <div class="onlymatt-admin-card">
            <h3>💬 Chat Interface</h3>
            <p>Interact with the AI assistant directly</p>
            <a href="<?php echo admin_url('admin.php?page=onlymatt-ai-chat'); ?>" class="onlymatt-btn">Open Chat</a>
        </div>

        <div class="onlymatt-admin-card">
            <h3>🧠 Memory Management</h3>
            <p>View and manage AI memory items</p>
            <a href="<?php echo admin_url('admin.php?page=onlymatt-ai-chat'); ?>" class="onlymatt-btn">Manage Memory</a>
        </div>

        <div class="onlymatt-admin-card">
            <h3>📋 Task Management</h3>
            <p>Create and track AI tasks</p>
            <a href="<?php echo admin_url('admin.php?page=onlymatt-ai-tasks'); ?>" class="onlymatt-btn">View Tasks</a>
        </div>

        <div class="onlymatt-admin-card">
            <h3>⚙️ Settings</h3>
            <p>Configure API connections and preferences</p>
            <a href="<?php echo admin_url('admin.php?page=onlymatt-ai-settings'); ?>" class="onlymatt-btn">Configure</a>
        </div>

        <div class="onlymatt-admin-card">
            <h3>📊 Reports</h3>
            <p>Generate usage and performance reports</p>
            <button class="onlymatt-btn" onclick="generateReport()">Generate Report</button>
        </div>

        <div class="onlymatt-admin-card">
            <h3>🔍 Analysis</h3>
            <p>Run data analysis on AI interactions</p>
            <button class="onlymatt-btn" onclick="runAnalysis()">Run Analysis</button>
        </div>
    </div>

    <div class="onlymatt-admin-card" style="margin-top: 20px;">
        <h3>📖 Shortcode Usage</h3>
        <p>Use these shortcodes to embed the AI assistant on your pages:</p>
        <ul>
            <li><code>[onlymatt_chat]</code> - Display the chat widget</li>
            <li><code>[onlymatt_admin]</code> - Display admin interface (admin users only)</li>
        </ul>
    </div>
</div>

<script>
function generateReport() {
    alert('Report generation feature coming soon!');
}

function runAnalysis() {
    alert('Analysis feature coming soon!');
}
</script>