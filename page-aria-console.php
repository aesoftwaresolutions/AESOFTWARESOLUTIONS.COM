<?php
/**
 * Template Name: Aria Console
 * Description: Admin-only control center for Aria autonomous agent
 *
 * @package AESoftwaresTheme
 */

// Require admin access
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

get_header(); ?>

<div class="aria-console-container">
    <!-- Status Bar -->
    <div class="aria-status-bar">
        <div class="aria-avatar" id="aria-avatar">
            <div class="avatar-glow"></div>
        </div>
        <div class="aria-status-info">
            <div class="status-main">
                <span class="status-badge" id="aria-status-badge">UNKNOWN</span>
                <span class="status-heartbeat" id="aria-heartbeat">Not connected</span>
            </div>
            <div class="status-task" id="aria-current-task">Idle</div>
        </div>
        <div class="aria-eval-score" id="aria-eval-score">
            <div class="eval-badge">--/10</div>
        </div>
    </div>

    <!-- Quick Controls -->
    <div class="aria-controls-panel">
        <div class="control-group">
            <button class="aria-btn aria-btn-primary" id="aria-toggle-btn">Pause Aria</button>
            <button class="aria-btn aria-btn-secondary" id="aria-send-directive-btn">Send Directive</button>
        </div>
        <div class="directive-input-group" id="directive-input-group" style="display: none;">
            <input type="text" id="aria-directive-input" placeholder="Enter directive text..." class="aria-input">
            <button class="aria-btn aria-btn-primary" id="aria-directive-send-btn">Send</button>
        </div>
        <div class="approval-buttons">
            <button class="aria-btn aria-btn-approve" data-directive="OUTREACH APPROVED">OUTREACH APPROVED</button>
            <button class="aria-btn aria-btn-approve" data-directive="SOCIAL LIVE">SOCIAL LIVE</button>
            <button class="aria-btn aria-btn-approve" data-directive="CONTINUE">CONTINUE</button>
            <button class="aria-btn aria-btn-approve" data-directive="LEGAL APPROVED">LEGAL APPROVED</button>
        </div>
    </div>

    <div class="aria-console-grid">
        <!-- Task Queue -->
        <section class="aria-panel task-queue-panel">
            <h2>Task Queue</h2>
            <div id="task-queue" class="task-queue">
                <p class="loading">Loading tasks...</p>
            </div>
        </section>

        <!-- Research Progress -->
        <section class="aria-panel research-progress-panel">
            <h2>Research Progress</h2>
            <div id="research-progress" class="research-progress">
                <div class="eval-gauge">
                    <div class="gauge-circle">
                        <div class="gauge-fill" id="eval-gauge-fill"></div>
                        <div class="gauge-text" id="eval-gauge-text">0.0</div>
                    </div>
                </div>
                <div class="verdict-box">
                    <span class="verdict-badge" id="eval-verdict">--</span>
                </div>
                <div class="feedback-box">
                    <p id="eval-feedback">No evaluation data</p>
                </div>
                <div class="strengths-gaps">
                    <div class="strengths">
                        <h4>Strengths</h4>
                        <ul id="eval-strengths"></ul>
                    </div>
                    <div class="gaps">
                        <h4>Gaps</h4>
                        <ul id="eval-gaps"></ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Browser Live View -->
        <section class="aria-panel browser-view-panel">
            <h2>Browser Live View</h2>
            <div id="browser-view" class="browser-view">
                <div class="browser-url-bar" id="browser-url-bar">about:blank</div>
                <div class="browser-canvas" id="browser-canvas">
                    <div class="browser-fallback">Browser not active</div>
                </div>
            </div>
        </section>

        <!-- Message Log -->
        <section class="aria-panel message-log-panel">
            <h2>Message Log</h2>
            <div id="message-log" class="message-log">
                <p class="loading">Loading messages...</p>
            </div>
            <div class="message-reply-box">
                <input type="text" id="message-reply-input" placeholder="Reply to Aria..." class="aria-input">
                <button class="aria-btn aria-btn-primary" id="message-reply-btn">Send</button>
            </div>
        </section>

        <!-- System Health -->
        <section class="aria-panel system-health-panel">
            <h2>System Health</h2>
            <div class="health-status">
                <div class="health-item">
                    <span class="health-label">Heartbeat Age</span>
                    <span class="health-value" id="heartbeat-age">--</span>
                </div>
                <div class="health-item">
                    <span class="health-label">Scripts Status</span>
                    <span class="health-value" id="scripts-status">Unknown</span>
                </div>
                <div class="health-item">
                    <span class="health-label">iptables Port 5432</span>
                    <span class="health-value">Protected</span>
                </div>
                <div class="health-item">
                    <span class="health-label">iptables Port 9100</span>
                    <span class="health-value">Protected</span>
                </div>
                <div class="health-item">
                    <span class="health-label">iCloud Sync</span>
                    <span class="health-value" id="icloud-sync">Unknown</span>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    :root {
        --ae-bg: #0a0e27;
        --ae-accent: #00d4ff;
        --ae-accent-dark: #0099bb;
        --ae-text: #e0e6f2;
        --ae-text-muted: #8a92b2;
        --ae-card-bg: #131b3f;
        --ae-border: #1f2a5c;
        --ae-success: #00d084;
        --ae-warning: #ffb700;
        --ae-danger: #ff4d6d;
    }

    .aria-console-container {
        background: var(--ae-bg);
        color: var(--ae-text);
        padding: 20px;
        max-width: 1600px;
        margin: 0 auto;
    }

    /* Status Bar */
    .aria-status-bar {
        background: var(--ae-card-bg);
        border: 1px solid var(--ae-border);
        border-radius: 8px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    .aria-avatar {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--ae-accent), var(--ae-accent-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .aria-avatar.paused {
        background: var(--ae-danger);
    }

    .avatar-glow {
        position: absolute;
        inset: -8px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.3) 0%, transparent 70%);
        animation: glow-pulse 2s infinite;
    }

    @keyframes glow-pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    .aria-status-info {
        flex-grow: 1;
    }

    .status-main {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 6px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.85rem;
        background: rgba(0, 212, 255, 0.1);
        color: var(--ae-accent);
    }

    .status-badge.paused {
        background: rgba(255, 77, 109, 0.1);
        color: var(--ae-danger);
    }

    .status-heartbeat {
        color: var(--ae-text-muted);
        font-size: 0.9rem;
    }

    .status-task {
        color: var(--ae-text-muted);
        font-size: 0.9rem;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .aria-eval-score {
        text-align: right;
    }

    .eval-badge {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--ae-accent);
    }

    /* Controls Panel */
    .aria-controls-panel {
        background: var(--ae-card-bg);
        border: 1px solid var(--ae-border);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 24px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-start;
    }

    .control-group {
        display: flex;
        gap: 10px;
    }

    .directive-input-group {
        display: flex;
        gap: 10px;
    }

    .approval-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .aria-btn {
        padding: 10px 18px;
        border: 1px solid var(--ae-border);
        background: transparent;
        color: var(--ae-text);
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .aria-btn:hover {
        transform: translateY(-2px);
    }

    .aria-btn-primary {
        background: var(--ae-accent);
        color: var(--ae-bg);
        border-color: var(--ae-accent);
    }

    .aria-btn-primary:hover {
        background: var(--ae-accent-dark);
        border-color: var(--ae-accent-dark);
    }

    .aria-btn-secondary {
        border-color: var(--ae-accent);
        color: var(--ae-accent);
    }

    .aria-btn-secondary:hover {
        background: rgba(0, 212, 255, 0.1);
    }

    .aria-btn-approve {
        background: rgba(0, 208, 132, 0.1);
        color: var(--ae-success);
        border-color: var(--ae-success);
        padding: 8px 14px;
        font-size: 0.85rem;
    }

    .aria-btn-approve:hover {
        background: rgba(0, 208, 132, 0.2);
    }

    .aria-input {
        flex-grow: 1;
        padding: 10px 14px;
        background: rgba(0, 212, 255, 0.05);
        border: 1px solid var(--ae-border);
        border-radius: 6px;
        color: var(--ae-text);
        font-size: 0.95rem;
    }

    .aria-input::placeholder {
        color: var(--ae-text-muted);
    }

    .aria-input:focus {
        outline: none;
        border-color: var(--ae-accent);
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.1);
    }

    /* Console Grid */
    .aria-console-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .aria-panel {
        background: var(--ae-card-bg);
        border: 1px solid var(--ae-border);
        border-radius: 8px;
        padding: 20px;
    }

    .aria-panel h2 {
        margin: 0 0 16px 0;
        font-size: 1.2rem;
        color: var(--ae-accent);
    }

    /* Task Queue */
    .task-queue {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .task-phase {
        border-left: 3px solid var(--ae-accent);
        padding-left: 12px;
    }

    .task-phase-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        padding: 8px 0;
        user-select: none;
    }

    .task-phase-header:hover {
        color: var(--ae-accent);
    }

    .task-phase-name {
        font-weight: 600;
        font-size: 1rem;
    }

    .task-phase-progress {
        font-size: 0.85rem;
        color: var(--ae-text-muted);
    }

    .task-phase-active-badge {
        background: var(--ae-success);
        color: var(--ae-bg);
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .task-progress-bar {
        width: 100%;
        height: 6px;
        background: rgba(0, 212, 255, 0.1);
        border-radius: 3px;
        margin: 8px 0;
        overflow: hidden;
    }

    .task-progress-fill {
        height: 100%;
        background: var(--ae-accent);
        transition: width 0.3s ease;
    }

    .task-items {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
    }

    .task-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        padding: 6px 0;
    }

    .task-status-badge {
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-todo {
        background: rgba(138, 146, 178, 0.2);
        color: var(--ae-text-muted);
    }

    .badge-doing {
        background: rgba(255, 183, 0, 0.2);
        color: var(--ae-warning);
    }

    .badge-done {
        background: rgba(0, 208, 132, 0.2);
        color: var(--ae-success);
    }

    .badge-blocked {
        background: rgba(255, 77, 109, 0.2);
        color: var(--ae-danger);
    }

    .loading {
        color: var(--ae-text-muted);
        font-style: italic;
    }

    /* Research Progress */
    .research-progress {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .eval-gauge {
        text-align: center;
    }

    .gauge-circle {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(0, 212, 255, 0.1);
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gauge-fill {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: conic-gradient(var(--ae-accent) 0deg, var(--ae-accent) var(--fill-angle, 0deg), rgba(0, 212, 255, 0.1) var(--fill-angle, 0deg));
    }

    .gauge-text {
        position: relative;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--ae-accent);
    }

    .verdict-box {
        text-align: center;
    }

    .verdict-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 600;
        background: rgba(0, 212, 255, 0.1);
        color: var(--ae-accent);
    }

    .verdict-badge.pass {
        background: rgba(0, 208, 132, 0.1);
        color: var(--ae-success);
    }

    .verdict-badge.fail {
        background: rgba(255, 77, 109, 0.1);
        color: var(--ae-danger);
    }

    .feedback-box p {
        margin: 0;
        color: var(--ae-text-muted);
        font-size: 0.95rem;
    }

    .strengths-gaps {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .strengths, .gaps {
        font-size: 0.9rem;
    }

    .strengths h4, .gaps h4 {
        margin: 0 0 8px 0;
        color: var(--ae-accent);
    }

    .strengths ul, .gaps ul {
        margin: 0;
        padding-left: 16px;
        list-style: circle;
        color: var(--ae-text-muted);
    }

    .strengths li, .gaps li {
        margin-bottom: 4px;
    }

    /* Browser View */
    .browser-view {
        display: flex;
        flex-direction: column;
        height: 400px;
        border: 1px solid var(--ae-border);
        border-radius: 6px;
        overflow: hidden;
    }

    .browser-url-bar {
        padding: 12px;
        background: rgba(0, 212, 255, 0.05);
        border-bottom: 1px solid var(--ae-border);
        font-size: 0.85rem;
        color: var(--ae-text-muted);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .browser-canvas {
        flex-grow: 1;
        background: var(--ae-bg);
        position: relative;
        overflow: hidden;
    }

    .browser-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--ae-text-muted);
        font-style: italic;
    }

    /* Message Log */
    .message-log {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 12px;
    }

    .message-item {
        padding: 12px;
        background: rgba(0, 212, 255, 0.05);
        border-left: 3px solid var(--ae-accent);
        border-radius: 4px;
    }

    .message-direction {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--ae-accent);
        margin-bottom: 4px;
    }

    .message-text {
        margin: 0;
        color: var(--ae-text);
        font-size: 0.95rem;
        word-wrap: break-word;
    }

    .message-time {
        font-size: 0.75rem;
        color: var(--ae-text-muted);
        margin-top: 6px;
    }

    .message-reply-box {
        display: flex;
        gap: 8px;
    }

    /* System Health */
    .health-status {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .health-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        background: rgba(0, 212, 255, 0.05);
        border-radius: 4px;
        border-left: 2px solid var(--ae-accent);
    }

    .health-label {
        color: var(--ae-text-muted);
        font-size: 0.9rem;
    }

    .health-value {
        font-weight: 600;
        color: var(--ae-text);
    }

    .health-value.green {
        color: var(--ae-success);
    }

    .health-value.yellow {
        color: var(--ae-warning);
    }

    .health-value.red {
        color: var(--ae-danger);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .aria-console-grid {
            grid-template-columns: 1fr;
        }

        .strengths-gaps {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .aria-status-bar {
            flex-direction: column;
            gap: 12px;
        }

        .aria-controls-panel {
            flex-direction: column;
        }

        .control-group, .approval-buttons {
            width: 100%;
            flex-direction: column;
        }

        .aria-btn {
            width: 100%;
        }
    }
</style>

<script>
(function() {
    const statusBadge = document.getElementById('aria-status-badge');
    const heartbeatEl = document.getElementById('aria-heartbeat');
    const currentTaskEl = document.getElementById('aria-current-task');
    const evalScoreEl = document.getElementById('aria-eval-score');
    const toggleBtn = document.getElementById('aria-toggle-btn');
    const sendDirectiveBtn = document.getElementById('aria-send-directive-btn');
    const directiveInputGroup = document.getElementById('directive-input-group');
    const directiveInput = document.getElementById('aria-directive-input');
    const directiveSendBtn = document.getElementById('aria-directive-send-btn');
    const taskQueue = document.getElementById('task-queue');
    const messageLog = document.getElementById('message-log');
    const messageReplyInput = document.getElementById('message-reply-input');
    const messageReplyBtn = document.getElementById('message-reply-btn');

    let currentStatus = null;
    let statusLastUpdate = null;

    // Fetch status every 30 seconds
    async function updateStatus() {
        try {
            const response = await fetch(ae_aria_console.status_url);
            if (!response.ok) throw new Error('Failed to fetch status');

            currentStatus = await response.json();
            statusLastUpdate = Date.now();
            renderStatus();
        } catch (error) {
            console.error('Error updating status:', error);
            statusBadge.textContent = 'OFFLINE';
            statusBadge.classList.add('offline');
        }
    }

    // Render status information
    function renderStatus() {
        if (!currentStatus) return;

        const isActive = currentStatus.status === 'active';
        statusBadge.textContent = currentStatus.status.toUpperCase();
        statusBadge.classList.remove('paused');
        if (currentStatus.status === 'paused') {
            statusBadge.classList.add('paused');
        }

        heartbeatEl.textContent = formatTime(currentStatus.last_heartbeat);
        currentTaskEl.textContent = (currentStatus.current_task || 'Idle').substring(0, 60);

        if (currentStatus.eval_score !== undefined) {
            evalScoreEl.querySelector('.eval-badge').textContent = currentStatus.eval_score.toFixed(1) + '/10';
        }

        toggleBtn.textContent = isActive ? 'Pause Aria' : 'Resume Aria';
        toggleBtn.dataset.action = isActive ? 'pause' : 'resume';

        // Update avatar glow
        const avatar = document.getElementById('aria-avatar');
        avatar.classList.remove('paused');
        if (!isActive) {
            avatar.classList.add('paused');
        }
    }

    // Fetch tasks every 60 seconds
    async function updateTasks() {
        try {
            const response = await fetch(ae_aria_console.tasks_url);
            if (!response.ok) throw new Error('Failed to fetch tasks');

            const tasksText = await response.text();
            renderTasks(tasksText);
        } catch (error) {
            console.error('Error updating tasks:', error);
            taskQueue.innerHTML = '<p class="loading">Unable to load tasks</p>';
        }
    }

    // Parse and render TASKS.md
    function renderTasks(tasksText) {
        const phases = ['R', 'C', 'S', 'A', 'D', 'E', 'F', 'L', 'TAX'];
        const phaseTasks = {};

        // Simple parse: look for phase headers and task items
        const lines = tasksText.split('\n');
        let currentPhase = null;

        phases.forEach(p => phaseTasks[p] = []);

        lines.forEach(line => {
            const phaseMatch = line.match(/^#+\s*(?:Phase\s+)?([R|C|S|A|D|E|F|L|TAX]+)/i);
            if (phaseMatch) {
                currentPhase = phaseMatch[1].toUpperCase();
                if (!phases.includes(currentPhase)) currentPhase = null;
            } else if (currentPhase && line.trim().startsWith('-')) {
                const taskText = line.trim().substring(1).trim();
                const statusMatch = taskText.match(/\[(TODO|DOING|DONE|BLOCKED)\]/);
                const status = statusMatch ? statusMatch[1] : 'TODO';
                phaseTasks[currentPhase].push({ text: taskText, status });
            }
        });

        const html = phases
            .filter(p => phaseTasks[p].length > 0)
            .map(phase => {
                const tasks = phaseTasks[phase];
                const done = tasks.filter(t => t.status === 'DONE').length;
                const isActive = ['R', 'C'].includes(phase);
                const progress = done / tasks.length;

                return `
                    <div class="task-phase">
                        <div class="task-phase-header">
                            <span class="task-phase-name">Phase ${phase}</span>
                            <span class="task-phase-progress">${done}/${tasks.length}</span>
                            ${isActive ? '<span class="task-phase-active-badge">ACTIVE</span>' : ''}
                        </div>
                        <div class="task-progress-bar">
                            <div class="task-progress-fill" style="width: ${progress * 100}%"></div>
                        </div>
                        <div class="task-items">
                            ${tasks.map(t => `
                                <div class="task-item">
                                    <span class="task-status-badge badge-${t.status.toLowerCase()}">${t.status}</span>
                                    <span>${escapeHtml(t.text)}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }).join('');

        taskQueue.innerHTML = html || '<p class="loading">No tasks found</p>';
    }

    // Fetch messages every 30 seconds
    async function updateMessages() {
        try {
            const response = await fetch(ae_aria_console.messages_url);
            if (!response.ok) throw new Error('Failed to fetch messages');

            const messages = await response.json();
            renderMessages(messages);
        } catch (error) {
            console.error('Error updating messages:', error);
            messageLog.innerHTML = '<p class="loading">Unable to load messages</p>';
        }
    }

    // Render messages
    function renderMessages(messages) {
        messageLog.innerHTML = messages.map(msg => `
            <div class="message-item">
                <div class="message-direction">${msg.direction === 'from_aria' ? 'FROM ARIA' : 'FROM ANTHONY'}</div>
                <p class="message-text">${escapeHtml(msg.text)}</p>
                <div class="message-time">${formatTime(msg.timestamp)}</div>
            </div>
        `).join('');
    }

    // Control handlers
    toggleBtn.addEventListener('click', async function() {
        const action = this.dataset.action;
        await sendControl({ action });
    });

    sendDirectiveBtn.addEventListener('click', function() {
        directiveInputGroup.style.display = directiveInputGroup.style.display === 'none' ? 'flex' : 'none';
        if (directiveInputGroup.style.display === 'flex') {
            directiveInput.focus();
        }
    });

    directiveSendBtn.addEventListener('click', async function() {
        const text = directiveInput.value.trim();
        if (!text) return;
        await sendControl({ action: 'directive', text });
        directiveInput.value = '';
        directiveInputGroup.style.display = 'none';
    });

    document.querySelectorAll('.aria-btn-approve').forEach(btn => {
        btn.addEventListener('click', async function() {
            const directive = this.dataset.directive;
            await sendControl({ action: 'directive', text: directive });
        });
    });

    messageReplyBtn.addEventListener('click', async function() {
        const text = messageReplyInput.value.trim();
        if (!text) return;
        await sendControl({ action: 'directive', text });
        messageReplyInput.value = '';
    });

    // Send control command to VPS
    async function sendControl(data) {
        try {
            const response = await fetch(ae_aria_console.control_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': ae_aria_console.nonce,
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) throw new Error('Control command failed');
            updateStatus(); // Refresh immediately
        } catch (error) {
            console.error('Error sending control:', error);
            alert('Failed to send command: ' + error.message);
        }
    }

    // Utilities
    function formatTime(timestamp) {
        if (!timestamp) return 'Never';
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);

        if (minutes < 1) return 'Just now';
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return date.toLocaleDateString();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initial load
    updateStatus();
    updateTasks();
    updateMessages();

    // Set up polling
    setInterval(updateStatus, 30000);
    setInterval(updateTasks, 60000);
    setInterval(updateMessages, 30000);
})();
</script>

<?php get_footer(); ?>
