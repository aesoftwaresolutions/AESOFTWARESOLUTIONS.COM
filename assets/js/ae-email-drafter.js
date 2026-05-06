(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof window.ae_email_settings === 'undefined') return;

		var apiUrl       = window.ae_email_settings.email_url;
		var selectedMode = 'professional';

		var inputEl    = document.getElementById('rewriter-input');
		var outputEl   = document.getElementById('rewriter-output');
		var submitBtn  = document.getElementById('rewriter-submit');
		var clearBtn   = document.getElementById('rewriter-clear');
		var copyBtn    = document.getElementById('rewriter-copy');
		var loader     = document.getElementById('rewriter-loader');
		var errorEl    = document.getElementById('rewriter-error');
		var inputCount = document.getElementById('rewriter-input-count');
		var outputCount= document.getElementById('rewriter-output-count');
		var modeBtns   = document.querySelectorAll('.rewriter-mode-btn');

		if (!inputEl || !outputEl || !submitBtn) return;

		loader.style.display  = 'none';
		errorEl.style.display = 'none';

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
				ta.style.cssText = 'position:fixed;opacity:0';
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
				showError('Please describe what you need to say.');
				inputEl.focus();
				return;
			}
			hideError();
			setLoading(true);
			outputEl.innerHTML = '';
			outputEl.dataset.text = '';
			outputCount.textContent = '0';
			copyBtn.disabled = true;

			var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
			var timeoutId  = setTimeout(function () {
				if (controller) controller.abort();
			}, 120000);

			var opts = {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify({ prompt: text, tone: selectedMode }),
			};
			if (controller) opts.signal = controller.signal;

			fetch(apiUrl, opts)
				.then(function (res) {
					clearTimeout(timeoutId);
					if (!res.ok) {
						return res.json().catch(function () { return {}; }).then(function (d) {
							throw new Error(d.message || 'Server error (' + res.status + '). Please try again.');
						});
					}
					return res.json();
				})
				.then(function (data) {
					var email = data.email || '';
					outputEl.dataset.text = email;
					// Preserve line breaks in email output
					outputEl.innerHTML = email
						.replace(/&/g, '&amp;')
						.replace(/</g, '&lt;')
						.replace(/>/g, '&gt;')
						.replace(/\n/g, '<br>');
					outputCount.textContent = countWords(email);
					copyBtn.disabled = false;
				})
				.catch(function (err) {
					clearTimeout(timeoutId);
					var msg = err.name === 'AbortError'
						? 'Request timed out. Please try again.'
						: (err.message || 'Something went wrong. Please try again.');
					showError(msg);
				})
				.finally(function () { setLoading(false); });
		});
	});
})();
