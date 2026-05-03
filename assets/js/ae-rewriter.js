(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof window.ae_rewriter_settings === 'undefined') return;

		var rewriteUrl   = window.ae_rewriter_settings.rewrite_url;
		var selectedMode = 'standard';

		var inputEl      = document.getElementById('rewriter-input');
		var outputEl     = document.getElementById('rewriter-output');
		var submitBtn    = document.getElementById('rewriter-submit');
		var clearBtn     = document.getElementById('rewriter-clear');
		var copyBtn      = document.getElementById('rewriter-copy');
		var loader       = document.getElementById('rewriter-loader');
		var errorEl      = document.getElementById('rewriter-error');
		var inputCount   = document.getElementById('rewriter-input-count');
		var outputCount  = document.getElementById('rewriter-output-count');
		var modeBtns     = document.querySelectorAll('.rewriter-mode-btn');

		if (!inputEl || !outputEl || !submitBtn) return;

		function countWords(str) {
			var trimmed = str.trim();
			if (!trimmed) return 0;
			return trimmed.split(/\s+/).length;
		}

		function showError(msg) {
			errorEl.textContent = msg;
			errorEl.hidden = false;
		}

		function hideError() {
			errorEl.hidden = true;
			errorEl.textContent = '';
		}

		function setLoading(on) {
			loader.hidden = !on;
			submitBtn.disabled = on;
			submitBtn.classList.toggle('is-loading', on);
		}

		inputEl.addEventListener('input', function () {
			inputCount.textContent = countWords(inputEl.value);
		});

		modeBtns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				modeBtns.forEach(function (b) { b.classList.remove('active'); });
				btn.classList.add('active');
				selectedMode = btn.dataset.mode;
			});
		});

		clearBtn.addEventListener('click', function () {
			inputEl.value = '';
			inputCount.textContent = '0';
			inputEl.focus();
		});

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
				ta.style.position = 'fixed';
				ta.style.opacity = '0';
				document.body.appendChild(ta);
				ta.select();
				document.execCommand('copy');
				document.body.removeChild(ta);
				copyBtn.textContent = 'Copied!';
				setTimeout(function () { copyBtn.textContent = 'Copy'; }, 2000);
			}
		});

		submitBtn.addEventListener('click', function () {
			var text = inputEl.value.trim();
			if (!text) {
				showError('Please paste some text before rewriting.');
				inputEl.focus();
				return;
			}
			hideError();
			setLoading(true);
			outputEl.innerHTML = '';
			outputCount.textContent = '0';
			copyBtn.disabled = true;

			var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
			var timeoutId = setTimeout(function () {
				if (controller) controller.abort();
			}, 30000);

			var fetchOptions = {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ text: text, mode: selectedMode }),
			};
			if (controller) fetchOptions.signal = controller.signal;

			fetch(rewriteUrl, fetchOptions)
				.then(function (res) {
					clearTimeout(timeoutId);
					if (!res.ok) {
						return res.json().catch(function () { return {}; }).then(function (data) {
							throw new Error(data.message || 'Server error (' + res.status + '). Please try again.');
						});
					}
					return res.json();
				})
				.then(function (data) {
					var rewritten = data.rewritten || '';
					outputEl.dataset.text = rewritten;
					outputEl.textContent = rewritten;
					outputCount.textContent = countWords(rewritten);
					copyBtn.disabled = false;
				})
				.catch(function (err) {
					clearTimeout(timeoutId);
					var msg = err.name === 'AbortError'
						? 'Request timed out. Please try again.'
						: (err.message || 'Something went wrong. Please try again.');
					showError(msg);
				})
				.finally(function () {
					setLoading(false);
				});
		});
	});
})();
