(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof window.ae_transcriber_settings === 'undefined') return;

		var transcribeUrl = window.ae_transcriber_settings.transcribe_url;

		var recordBtn      = document.getElementById('transcriber-record-btn');
		var recordStatus   = document.getElementById('transcriber-record-status');
		var recordTimer    = document.getElementById('transcriber-record-timer');
		var fileInput      = document.getElementById('transcriber-file-input');
		var fileNameEl     = document.getElementById('transcriber-file-name');
		var submitBtn      = document.getElementById('transcriber-submit');
		var outputEl       = document.getElementById('transcriber-output');
		var outputCount    = document.getElementById('transcriber-output-count');
		var loader         = document.getElementById('transcriber-loader');
		var errorEl        = document.getElementById('transcriber-error');
		var copyBtn        = document.getElementById('transcriber-copy');
		var clearOutputBtn = document.getElementById('transcriber-clear-output');

		if (!recordBtn || !submitBtn || !outputEl) return;

		loader.style.display = 'none';
		errorEl.style.display = 'none';

		// ── State ─────────────────────────────────────────────────────────────
		var mediaRecorder   = null;
		var recordedChunks  = [];
		var recordedBlob    = null;
		var uploadedFile    = null;
		var timerInterval   = null;
		var elapsedSeconds  = 0;
		var MAX_RECORD_SECS = 300; // 5 min hard cap

		var micSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
		if (!micSupported) {
			recordBtn.disabled = true;
			recordStatus.textContent = 'Microphone not available in this browser.';
		}

		// ── Helpers ───────────────────────────────────────────────────────────
		function countWords(str) {
			var t = str.trim();
			return t ? t.split(/\s+/).length : 0;
		}

		function showError(msg) {
			errorEl.textContent = msg;
			errorEl.style.display = 'block';
		}

		function hideError() {
			errorEl.style.display = 'none';
			errorEl.textContent = '';
		}

		function setLoading(on) {
			loader.style.display = on ? 'flex' : 'none';
			submitBtn.disabled = on;
			submitBtn.classList.toggle('is-loading', on);
		}

		function formatTime(s) {
			var m = Math.floor(s / 60);
			var sec = s % 60;
			return m + ':' + (sec < 10 ? '0' : '') + sec;
		}

		function startTimer() {
			elapsedSeconds = 0;
			recordTimer.hidden = false;
			recordTimer.textContent = '0:00';
			timerInterval = setInterval(function () {
				elapsedSeconds++;
				recordTimer.textContent = formatTime(elapsedSeconds);
				if (elapsedSeconds >= MAX_RECORD_SECS) stopRecording();
			}, 1000);
		}

		function stopTimer() {
			clearInterval(timerInterval);
			timerInterval = null;
		}

		function setAudioReady(blob, label) {
			recordedBlob = blob;
			uploadedFile = null;
			submitBtn.disabled = false;
			recordStatus.textContent = label;
		}

		// ── Recording ─────────────────────────────────────────────────────────
		function startRecording() {
			if (!micSupported) return;
			navigator.mediaDevices.getUserMedia({ audio: true })
				.then(function (stream) {
					recordedChunks = [];
					recordedBlob   = null;

					var mimeType = MediaRecorder.isTypeSupported('audio/webm;codecs=opus')
						? 'audio/webm;codecs=opus'
						: 'audio/webm';

					mediaRecorder = new MediaRecorder(stream, { mimeType: mimeType });

					mediaRecorder.ondataavailable = function (e) {
						if (e.data && e.data.size > 0) recordedChunks.push(e.data);
					};

					mediaRecorder.onstop = function () {
						stream.getTracks().forEach(function (t) { t.stop(); });
						var blob = new Blob(recordedChunks, { type: mimeType });
						setAudioReady(blob, 'Recording ready (' + formatTime(elapsedSeconds) + ')');
						stopTimer();
						recordTimer.hidden = true;
					};

					mediaRecorder.start(200); // collect every 200ms
					recordBtn.classList.add('is-recording');
					recordStatus.textContent = 'Recording…';
					startTimer();
				})
				.catch(function (err) {
					showError('Microphone access denied or unavailable: ' + err.message);
				});
		}

		function stopRecording() {
			if (mediaRecorder && mediaRecorder.state !== 'inactive') {
				mediaRecorder.stop();
				recordBtn.classList.remove('is-recording');
			}
		}

		recordBtn.addEventListener('click', function () {
			if (!micSupported) return;
			if (mediaRecorder && mediaRecorder.state === 'recording') {
				stopRecording();
			} else {
				// Clear any previously selected file
				uploadedFile = null;
				fileInput.value = '';
				if (fileNameEl) fileNameEl.hidden = true;
				startRecording();
			}
		});

		// ── File upload ───────────────────────────────────────────────────────
		fileInput.addEventListener('change', function () {
			var file = fileInput.files[0];
			if (!file) return;

			// Stop any active recording
			stopRecording();

			var MAX_BYTES = 25 * 1024 * 1024;
			if (file.size > MAX_BYTES) {
				showError('File is too large. Maximum size is 25 MB.');
				fileInput.value = '';
				return;
			}

			hideError();
			uploadedFile = file;
			recordedBlob = null;
			submitBtn.disabled = false;
			recordStatus.textContent = 'Press to record';
			recordTimer.hidden = true;

			if (fileNameEl) {
				fileNameEl.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
				fileNameEl.hidden = false;
			}
		});

		// ── Submit ────────────────────────────────────────────────────────────
		submitBtn.addEventListener('click', function () {
			var audioSource = uploadedFile || recordedBlob;
			if (!audioSource) {
				showError('Record from your microphone or upload a file first.');
				return;
			}

			hideError();
			setLoading(true);
			outputEl.innerHTML = '';
			outputCount.textContent = '0';
			copyBtn.disabled = true;
			if (clearOutputBtn) clearOutputBtn.disabled = true;

			var fileName = uploadedFile
				? uploadedFile.name
				: ('recording-' + Date.now() + '.webm');

			var form = new FormData();
			form.append('audio', audioSource, fileName);

			fetch(transcribeUrl, { method: 'POST', body: form })
				.then(function (res) {
					return res.json().then(function (data) {
						if (!res.ok) {
							throw new Error(data.message || ('Server error ' + res.status));
						}
						return data;
					});
				})
				.then(function (data) {
					var text = (data.text || '').trim();
					if (!text) {
						outputEl.innerHTML = '<span class="transcriber-output__placeholder">No speech detected in the audio.</span>';
					} else {
						outputEl.dataset.text = text;
						outputEl.textContent  = text;
						outputCount.textContent = countWords(text);
						copyBtn.disabled = false;
						if (clearOutputBtn) clearOutputBtn.disabled = false;
					}
				})
				.catch(function (err) {
					showError(err.message || 'Something went wrong. Please try again.');
				})
				.finally(function () {
					setLoading(false);
				});
		});

		// ── Copy ──────────────────────────────────────────────────────────────
		copyBtn.addEventListener('click', function () {
			var text = outputEl.dataset.text || '';
			if (!text) return;
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(text).then(function () {
					copyBtn.textContent = 'Copied!';
					setTimeout(function () { copyBtn.textContent = 'Copy'; }, 2000);
				});
			} else {
				var ta = document.createElement('textarea');
				ta.value = text;
				ta.style.cssText = 'position:fixed;opacity:0';
				document.body.appendChild(ta);
				ta.select();
				document.execCommand('copy');
				document.body.removeChild(ta);
				copyBtn.textContent = 'Copied!';
				setTimeout(function () { copyBtn.textContent = 'Copy'; }, 2000);
			}
		});

		// ── Clear output ─────────────────────────────────────────────────────
		if (clearOutputBtn) {
			clearOutputBtn.addEventListener('click', function () {
				outputEl.innerHTML = '<span class="transcriber-output__placeholder">Your transcript will appear here.</span>';
				delete outputEl.dataset.text;
				outputCount.textContent = '0';
				copyBtn.disabled = true;
				clearOutputBtn.disabled = true;
				hideError();
			});
		}
	});
})();
