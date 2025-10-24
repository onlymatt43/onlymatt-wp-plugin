<div class="wrap">
    <h1>ONLYMATT AI Tasks</h1>

    <div class="card">
        <div class="card-header">
            <h5>Task Management</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="onlymatt-task-title" class="form-control" placeholder="Task Title">
                </div>
                <div class="col-md-6">
                    <input type="text" id="onlymatt-task-description" class="form-control" placeholder="Task Description (optional)">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100" id="onlymatt-add-task">Add Task</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="onlymatt-tasks-list">
                        <tr>
                            <td colspan="4">Loading tasks...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Reports & Analysis</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <select id="onlymatt-report-type" class="form-select">
                        <option value="usage">Usage Report</option>
                        <option value="performance">Performance Report</option>
                        <option value="memory">Memory Report</option>
                        <option value="tasks">Tasks Report</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="onlymatt-generate-report">Generate Report</button>
                </div>
            </div>
            <div id="onlymatt-report-content" class="mb-4 p-3 bg-light rounded">
                <p class="text-muted">Select a report type and click Generate to view results.</p>
            </div>

            <h6>Analysis</h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <select id="onlymatt-analysis-type" class="form-select">
                        <option value="conversations">Conversation Analysis</option>
                        <option value="memory_usage">Memory Usage Analysis</option>
                        <option value="task_completion">Task Completion Analysis</option>
                        <option value="performance">Performance Analysis</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" id="onlymatt-run-analysis">Run Analysis</button>
                </div>
            </div>
            <div id="onlymatt-analysis-results" class="p-3 bg-light rounded">
                <p class="text-muted">Select an analysis type and click Run Analysis to view results.</p>
            </div>
        </div>
    </div>
</div>