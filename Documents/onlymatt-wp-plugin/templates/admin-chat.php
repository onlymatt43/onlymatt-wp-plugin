<div class="wrap">
    <h1>ONLYMATT AI Chat</h1>

    <div class="card">
        <div class="card-header">
            <h5>AI Chat Interface</h5>
        </div>
        <div class="card-body">
            <div id="onlymatt-chat-messages" class="chat-messages mb-3" style="height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                <div class="onlymatt-message onlymatt-assistant-message">
                    Hello! I'm your AI assistant. How can I help you today?
                </div>
            </div>
            <div id="onlymatt-typing" class="typing-indicator" style="display: none; font-style: italic; color: #666; padding: 10px;">
                AI is typing...
            </div>
            <div class="input-group">
                <input type="text" id="onlymatt-chat-input" class="form-control" placeholder="Type your message...">
                <button class="btn btn-primary" id="onlymatt-send-message">Send</button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Memory Management</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="onlymatt-memory-key" class="form-control" placeholder="Memory Key">
                </div>
                <div class="col-md-6">
                    <input type="text" id="onlymatt-memory-value" class="form-control" placeholder="Memory Value">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100" id="onlymatt-add-memory">Add</button>
                </div>
            </div>
            <button class="btn btn-danger btn-sm mb-3" id="onlymatt-clear-memory">Clear All Memory</button>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="onlymatt-memory-list">
                        <tr>
                            <td colspan="3">Loading memory items...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>