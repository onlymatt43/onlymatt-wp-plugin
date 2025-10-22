/**
 * ONLYMATT AI Admin JavaScript
 * Handles admin panel functionality and API communication
 */

jQuery(document).ready(function($) {
    'use strict';

    // Admin panel functionality
    const OnlyMattAdmin = {
        apiUrl: onlymatt_ajax.api_base,

        init: function() {
            this.bindEvents();
            this.loadInitialData();
        },

        callAPI: async function(endpoint, data) {
            const response = await fetch(`${this.apiUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`API call failed: ${response.status}`);
            }

            return await response.json();
        },

        bindEvents: function() {
            // Chat functionality
            $('#onlymatt-send-message').on('click', this.sendChatMessage.bind(this));
            $('#onlymatt-chat-input').on('keypress', (e) => {
                if (e.which === 13) {
                    this.sendChatMessage();
                }
            });

            // Memory management
            $('#onlymatt-add-memory').on('click', this.addMemory.bind(this));
            $('#onlymatt-clear-memory').on('click', this.clearMemory.bind(this));

            // Tasks
            $('#onlymatt-add-task').on('click', this.addTask.bind(this));
            $(document).on('click', '.onlymatt-complete-task', this.completeTask.bind(this));
            $(document).on('click', '.onlymatt-delete-task', this.deleteTask.bind(this));

        // Reports
        $('#onlymatt-generate-report').on('click', this.generateReport.bind(this));
        $('#onlymatt-view-report').on('click', '.onlymatt-view-report', this.viewReport.bind(this));
        $('#onlymatt-delete-report').on('click', '.onlymatt-delete-report', this.deleteReport.bind(this));

        // Analysis
        $('#onlymatt-run-analysis').on('click', this.runAnalysis.bind(this));            // Settings
            $('#onlymatt-save-settings').on('click', this.saveSettings.bind(this));
        },

        loadInitialData: function() {
            this.loadTasks();
            this.loadReports();
        },

        // Chat functionality
        sendChatMessage: function() {
            const input = $('#onlymatt-chat-input');
            const message = input.val().trim();

            if (!message) return;

            this.addChatMessage(message, 'user');
            input.val('');

            this.showTyping();

            this.callAPI('/ai/chat', { messages: [{role: 'user', content: message}], model: 'llama-3.3-70b-versatile', temperature: 0.7 })
                .done((response) => {
                    this.hideTyping();
                    this.addChatMessage(response.response, 'assistant');
                })
                .fail(() => {
                    this.hideTyping();
                    this.addChatMessage('Error: Could not get response from AI.', 'assistant');
                });
        },

        addChatMessage: function(message, type) {
            const messages = $('#onlymatt-chat-messages');
            const messageClass = type === 'user' ? 'onlymatt-user-message' : 'onlymatt-assistant-message';
            const html = `<div class="onlymatt-message ${messageClass}">${this.escapeHtml(message)}</div>`;
            messages.append(html);
            messages.scrollTop(messages[0].scrollHeight);
        },

        showTyping: function() {
            $('#onlymatt-typing').show();
        },

        hideTyping: function() {
            $('#onlymatt-typing').hide();
        },

        // Memory management
        addMemory: function() {
            const key = $('#onlymatt-memory-key').val().trim();
            const value = $('#onlymatt-memory-value').val().trim();

            if (!key || !value) {
                alert('Please enter both key and value.');
                return;
            }

            this.callAPI('/ai/memory/remember', { user_id: 'admin', persona: 'wp_admin', key, value })
                .done(() => {
                    $('#onlymatt-memory-key, #onlymatt-memory-value').val('');
                    this.loadMemory();
                })
                .fail(() => {
                    alert('Error adding memory.');
                });
        },

        loadMemory: function() {
            this.callAPI('/ai/memory/recall?user_id=admin&persona=wp_admin', {})
                .done((response) => {
                    const memoryList = $('#onlymatt-memory-list');
                    memoryList.empty();

                    if (response.ok && response.memories && response.memories.length > 0) {
                        response.memories.forEach(item => {
                            memoryList.append(`
                                <tr>
                                    <td>${this.escapeHtml(item.key)}</td>
                                    <td>${this.escapeHtml(item.value)}</td>
                                    <td>
                                        <button class="button onlymatt-delete-memory" data-key="${this.escapeHtml(item.key)}">Delete</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        memoryList.html('<tr><td colspan="3">No memory items stored.</td></tr>');
                    }
                });
        },

        clearMemory: function() {
            alert('Clear memory functionality not yet implemented in the API.');
        },

        // Tasks functionality
        addTask: function() {
            const title = $('#onlymatt-task-title').val().trim();
            const description = $('#onlymatt-task-description').val().trim();

            if (!title) {
                alert('Please enter a task title.');
                return;
            }

            // Call the API directly to create task
            $.ajax({
                url: this.apiUrl + '/admin/tasks',
                method: 'POST',
                data: JSON.stringify({
                    title: title,
                    description: description,
                    priority: 'medium'
                }),
                contentType: 'application/json',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key
                }
            })
            .done((response) => {
                if (response.ok) {
                    $('#onlymatt-task-title, #onlymatt-task-description').val('');
                    this.loadTasks();
                } else {
                    alert('Error adding task.');
                }
            })
            .fail(() => {
                alert('Error adding task.');
            });
        },

        loadTasks: function() {
            $.ajax({
                url: this.apiUrl + '/admin/tasks',
                method: 'GET',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key
                }
            })
            .done((response) => {
                const tasksList = $('#onlymatt-tasks-list');
                tasksList.empty();

                if (response.ok && response.tasks && response.tasks.length > 0) {
                    response.tasks.forEach(task => {
                        const statusClass = task.status === 'completed' ? 'completed' : 'pending';
                        tasksList.append(`
                            <tr class="${statusClass}">
                                <td>${this.escapeHtml(task.title)}</td>
                                <td>${this.escapeHtml(task.description || '')}</td>
                                <td>${this.escapeHtml(task.status)}</td>
                                <td>
                                    ${task.status !== 'completed' ?
                                        `<button class="button onlymatt-complete-task" data-id="${task.id}">Complete</button>` : ''}
                                    <button class="button onlymatt-delete-task" data-id="${task.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tasksList.html('<tr><td colspan="4">No tasks found.</td></tr>');
                }
            })
            .fail(() => {
                $('#onlymatt-tasks-list').html('<tr><td colspan="4">Error loading tasks.</td></tr>');
            });
        },

        completeTask: function(e) {
            const taskId = $(e.target).data('id');
            $.ajax({
                url: this.apiUrl + '/admin/tasks/' + taskId,
                method: 'PUT',
                data: JSON.stringify({ status: 'completed' }),
                contentType: 'application/json',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key
                }
            })
            .done((response) => {
                if (response.ok) {
                    this.loadTasks();
                } else {
                    alert('Error completing task.');
                }
            })
            .fail(() => {
                alert('Error completing task.');
            });
        },

        deleteTask: function(e) {
            const taskId = $(e.target).data('id');
            if (!confirm('Are you sure you want to delete this task?')) return;

            $.ajax({
                url: this.apiUrl + '/admin/tasks/' + taskId,
                method: 'DELETE',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key
                }
            })
            .done((response) => {
                if (response.ok) {
                    this.loadTasks();
                } else {
                    alert('Error deleting task.');
                }
            })
            .fail(() => {
                alert('Error deleting task.');
            });
        },

        // Reports functionality
        generateReport: function() {
            const reportType = $('#onlymatt-report-type').val();

            // Generate a basic report based on type
            let reportData = {
                type: reportType,
                title: `${reportType.charAt(0).toUpperCase() + reportType.slice(1)} Report`,
                content: `Generated ${reportType} report at ${new Date().toISOString()}`
            };

            // Call the API to create the report
            $.ajax({
                url: this.apiUrl + '/admin/reports',
                method: 'POST',
                data: JSON.stringify(reportData),
                contentType: 'application/json',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key || 'test_key'
                }
            })
            .done((response) => {
                if (response.ok) {
                    $('#onlymatt-report-content').html(`<pre>${this.escapeHtml(JSON.stringify(reportData, null, 2))}</pre>`);
                    this.loadReports();
                } else {
                    alert('Error generating report.');
                }
            })
            .fail(() => {
                alert('Error generating report.');
            });
        },

        viewReport: function(e) {
            e.preventDefault();
            const reportId = $(e.target).data('id');
            // For now, just show an alert - could be enhanced to show modal
            alert('View report functionality - Report ID: ' + reportId);
        },

        deleteReport: function(e) {
            e.preventDefault();
            const reportId = $(e.target).data('id');
            if (!confirm('Are you sure you want to delete this report?')) return;

            // Note: DELETE endpoint for reports not implemented in FastAPI yet
            alert('Delete report functionality not yet implemented in the API.');
        },

        loadReports: function() {
            $.ajax({
                url: this.apiUrl + '/admin/reports',
                method: 'GET',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key
                }
            })
            .done((response) => {
                const reportsList = $('#onlymatt-reports-list');
                reportsList.empty();

                if (response.ok && response.reports && response.reports.length > 0) {
                    response.reports.forEach(report => {
                        reportsList.append(`
                            <tr>
                                <td>${this.escapeHtml(report.type)}</td>
                                <td>${new Date(report.created_at).toLocaleString()}</td>
                                <td>
                                    <button class="button onlymatt-view-report" data-id="${report.id}">View</button>
                                    <button class="button onlymatt-delete-report" data-id="${report.id}">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    reportsList.html('<tr><td colspan="3">No reports found.</td></tr>');
                }
            })
            .fail(() => {
                $('#onlymatt-reports-list').html('<tr><td colspan="3">Error loading reports.</td></tr>');
            });
        },

        // Analysis functionality
        runAnalysis: function() {
            const analysisType = $('#onlymatt-analysis-type').val();

            // Run analysis by calling the API
            $.ajax({
                url: this.apiUrl + '/admin/analyses',
                method: 'POST',
                data: JSON.stringify({
                    type: analysisType,
                    results: `Analysis ${analysisType} completed at ${new Date().toISOString()}`
                }),
                contentType: 'application/json',
                headers: {
                    'X-OM-Key': onlymatt_ajax.admin_key
                }
            })
            .done((response) => {
                if (response.ok) {
                    $('#onlymatt-analysis-results').html(`<pre>${this.escapeHtml(JSON.stringify(response, null, 2))}</pre>`);
                } else {
                    alert('Error running analysis.');
                }
            })
            .fail(() => {
                alert('Error running analysis.');
            });
        },

        // Settings functionality
        saveSettings: function() {
            const settings = {
                api_url: $('#onlymatt-api-url').val().trim(),
                api_key: $('#onlymatt-api-key').val().trim(),
                max_memory: parseInt($('#onlymatt-max-memory').val()) || 100,
                enable_logging: $('#onlymatt-enable-logging').is(':checked')
            };

            // Save to WordPress options
            $.post(onlymatt_ajax.ajax_url, {
                action: 'onlymatt_save_settings',
                settings: JSON.stringify(settings),
                nonce: onlymatt_ajax.nonce
            })
            .done(() => {
                alert('Settings saved successfully.');
            })
            .fail(() => {
                alert('Error saving settings.');
            });
        },

        // Utility functions
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Initialize admin functionality
    if (typeof onlymatt_ajax !== 'undefined') {
        OnlyMattAdmin.init();
    }

    // Bind delete memory events (delegated)
    $(document).on('click', '.onlymatt-delete-memory', function() {
        const key = $(this).data('key');
        alert('Delete memory functionality not yet implemented in the API.');
    });

    // Bind view report events (delegated)
    $(document).on('click', '.onlymatt-view-report', function() {
        const reportId = $(this).data('id');
        // Implementation for viewing specific report
        alert('View report functionality not yet implemented.');
    });

    // Bind delete report events (delegated)
    $(document).on('click', '.onlymatt-delete-report', function() {
        const reportId = $(this).data('id');
        if (!confirm('Are you sure you want to delete this report?')) return;

        OnlyMattAdmin.callAPI('/admin/reports', {
            action: 'delete',
            id: reportId
        })
        .done(() => {
            OnlyMattAdmin.loadReports();
        })
        .fail(() => {
            alert('Error deleting report.');
        });
    });
});