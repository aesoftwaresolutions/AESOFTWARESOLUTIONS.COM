/**
 * Aria Console frontend
 */

(function() {
    // DOM elements
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
    let heartbeatCounter = null;

    /**
     * Update status from REST endpoint
     */
    async function updateStatus() {
        try {
            const response = await fetch(ae_aria_console.status_url);
            if (!response.ok) throw new Error('Failed to fetch status');

            currentStatus = await response.json();
            statusLastUpdate = Date.now();
            renderStatus();
            startHeartbeatCounter();
        } catch (error) {
            console.error('Error updating status:', error);
            statusBadge.textContent = 'OFFLINE';
            statusBadge.classList.add('offline');
        }
    }

    /**
     * Render status information
     */
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
            const score = parseFloat(currentStatus.eval_score).toFixed(1);
            evalScoreEl.querySelector('.eval-badge').textContent = score + '/10';
            updateEvalGauge(parseFloat(score));
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

    /**
     * Start live heartbeat counter
     */
    function startHeartbeatCounter() {
        if (heartbeatCounter) clearInterval(heartbeatCounter);

        heartbeatCounter = setInterval(() => {
            if (statusLastUpdate) {
                heartbeatEl.textContent = formatTimeMs(Date.now() - statusLastUpdate);
            }
        }, 1000);
    }

    /**
     * Update eval score gauge
     */
    function updateEvalGauge(score) {
        const angle = (score / 10) * 360;
        const gaugeEl = document.getElementById('eval-gauge-fill');
        if (gaugeEl) {
            gaugeEl.style.setProperty('--fill-angle', angle + 'deg');
        }

        const textEl = document.getElementById('eval-gauge-text');
        if (textEl) {
            textEl.textContent = score.toFixed(1);
        }

        const verdictEl = document.getElementById('eval-verdict');
        if (verdictEl) {
            verdictEl.textContent = score >= 7 ? 'PASS' : 'FAIL';
            verdictEl.classList.remove('pass', 'fail');
            verdictEl.classList.add(score >= 7 ? 'pass' : 'fail');
        }
    }

    /**
     * Fetch and render tasks
     */
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

    /**
     * Parse TASKS.md and render as phases
     */
    function renderTasks(tasksText) {
        const phases = ['R', 'C', 'S', 'A', 'D', 'E', 'F', 'L', 'TAX'];
        const phaseTasks = {};

        phases.forEach((p) => (phaseTasks[p] = []));

        const lines = tasksText.split('\n');
        let currentPhase = null;

        lines.forEach((line) => {
            const phaseMatch = line.match(/^#+\s*(?:Phase\s+)?([RCSADEFLTAX]+)/i);
            if (phaseMatch) {
                const candidate = phaseMatch[1].toUpperCase();
                if (phases.includes(candidate)) {
                    currentPhase = candidate;
                }
            } else if (currentPhase && line.trim().startsWith('-')) {
                const taskText = line.trim().substring(1).trim();
                const statusMatch = taskText.match(/\[(TODO|DOING|DONE|BLOCKED)\]/);
                const status = statusMatch ? statusMatch[1] : 'TODO';
                phaseTasks[currentPhase].push({ text: taskText, status });
            }
        });

        const html = phases
            .filter((p) => phaseTasks[p].length > 0)
            .map((phase) => {
                const tasks = phaseTasks[phase];
                const done = tasks.filter((t) => t.status === 'DONE').length;
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
                            ${tasks
                                .map(
                                    (t) => `
                                <div class="task-item">
                                    <span class="task-status-badge badge-${t.status.toLowerCase()}">${t.status}</span>
                                    <span>${escapeHtml(t.text)}</span>
                                </div>
                            `
                                )
                                .join('')}
                        </div>
                    </div>
                `;
            })
            .join('');

        taskQueue.innerHTML = html || '<p class="loading">No tasks found</p>';
    }

    /**
     * Fetch and render messages
     */
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

    /**
     * Render message list
     */
    function renderMessages(messages) {
        if (!Array.isArray(messages) || messages.length === 0) {
            messageLog.innerHTML = '<p class="loading">No messages</p>';
            return;
        }

        messageLog.innerHTML = messages
            .map(
                (msg) => `
            <div class="message-item">
                <div class="message-direction">${
                    msg.direction === 'from_aria' ? 'FROM ARIA' : 'FROM ANTHONY'
                }</div>
                <p class="message-text">${escapeHtml(msg.text)}</p>
                <div class="message-time">${formatTime(msg.timestamp)}</div>
            </div>
        `
            )
            .join('');
    }

    /**
     * Toggle pause/resume
     */
    toggleBtn.addEventListener('click', async function () {
        const action = this.dataset.action;
        await sendControl({ action });
    });

    /**
     * Toggle directive input
     */
    sendDirectiveBtn.addEventListener('click', function () {
        const isHidden = directiveInputGroup.style.display === 'none';
        directiveInputGroup.style.display = isHidden ? 'flex' : 'none';
        if (isHidden) {
            directiveInput.focus();
        }
    });

    /**
     * Send custom directive
     */
    directiveSendBtn.addEventListener('click', async function () {
        const text = directiveInput.value.trim();
        if (!text) return;
        await sendControl({ action: 'directive', text });
        directiveInput.value = '';
        directiveInputGroup.style.display = 'none';
    });

    /**
     * Send approval buttons
     */
    document.querySelectorAll('.aria-btn-approve').forEach((btn) => {
        btn.addEventListener('click', async function () {
            const directive = this.dataset.directive;
            await sendControl({ action: 'directive', text: directive });
        });
    });

    /**
     * Send message reply
     */
    messageReplyBtn.addEventListener('click', async function () {
        const text = messageReplyInput.value.trim();
        if (!text) return;
        await sendControl({ action: 'directive', text });
        messageReplyInput.value = '';
    });

    /**
     * Send control command to VPS
     */
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

    /**
     * Format timestamp to relative time
     */
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

    /**
     * Format milliseconds to relative time
     */
    function formatTimeMs(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        const hours = Math.floor(ms / 3600000);
        const days = Math.floor(ms / 86400000);

        if (ms < 1000) return 'Just now';
        if (minutes < 1) return `${seconds}s ago`;
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        if (days < 7) return `${days}d ago`;
        return 'Long ago';
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Initialize on page load
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            updateStatus();
            updateTasks();
            updateMessages();

            // Set up polling
            setInterval(updateStatus, 30000);
            setInterval(updateTasks, 60000);
            setInterval(updateMessages, 30000);
        });
    } else {
        updateStatus();
        updateTasks();
        updateMessages();

        // Set up polling
        setInterval(updateStatus, 30000);
        setInterval(updateTasks, 60000);
        setInterval(updateMessages, 30000);
    }
})();
