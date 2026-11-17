(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof window.ae_summarizer_settings === 'undefined') return;

		var apiUrl       = window.ae_summarizer_settings.summarize_url;
		var selectedMode = 'brief';

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

		loader.hidden = true; loader.style.display = 'none';
		errorEl.hidden = true; errorEl.style.display = 'none';

		var FAIL_MSG = 'That request did not go through. Check your connection and try again.';
		var submitLabel = submitBtn.querySelector('.rewriter-btn__text');
		var idleLabel = submitLabel ? submitLabel.textContent : '';

		function countWords(str) {
			var t = str.trim();
			return t ? t.split(/\s+/).length : 0;
		}

		function showError(msg) {
			errorEl.textContent = msg;
			errorEl.hidden = false; errorEl.style.display = 'block';
		}

		function hideError() {
			errorEl.hidden = true; errorEl.style.display = 'none';
			errorEl.textContent = '';
		}

		function setLoading(on) {
			loader.hidden = !on; loader.style.display = on ? 'flex' : 'none';
			submitBtn.disabled = on;
			submitBtn.classList.toggle('is-loading', on);
			submitBtn.setAttribute('aria-busy', on ? 'true' : 'false');
			if (submitLabel) submitLabel.textContent = on ? 'Working' : idleLabel;
		}

		// Plain failure message with a retry button. Technical detail goes to the console only.
		function showFailure(err, again) {
			if (window.console && err) console.error('Tool request failed:', err);
			errorEl.textContent = FAIL_MSG + ' ';
			errorEl.hidden = false; errorEl.style.display = 'block';
			var retry = document.createElement('button');
			retry.type = 'button';
			retry.className = 'rewriter-btn rewriter-btn--ghost rewriter-retry';
			retry.textContent = 'Try again';
			retry.addEventListener('click', function () {
				hideError();
				(again || function () { submitBtn.click(); })();
			});
			errorEl.appendChild(retry);
		}

		// Try an example: fills the input so the panels are not empty on first visit.
		var EXAMPLE = 'A small plumbing company keeps missing calls while the crew is out on jobs. Most callers do not leave a voicemail, and many of them simply call the next company on the list. The owner starts tracking every missed call for two weeks and finds that about a third of them were new customers asking for quotes. She sets up an automatic text reply that asks what the caller needs and sends a booking link. Within a month the crew is booking more of those callers, and the owner spends less time returning calls in the evening. She also starts replying to every online review, which helps new customers trust the business before they ever call.';
		var exampleBtn = document.createElement('button');
		exampleBtn.type = 'button';
		exampleBtn.id = 'rewriter-example';
		exampleBtn.className = 'rewriter-btn rewriter-btn--ghost';
		exampleBtn.textContent = 'Try an example';
		exampleBtn.addEventListener('click', function () {
			inputEl.value = EXAMPLE;
			inputCount.textContent = countWords(EXAMPLE);
			hideError();
			inputEl.focus();
		});
		if (clearBtn && clearBtn.parentNode) clearBtn.parentNode.insertBefore(exampleBtn, clearBtn);

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
			if (submitBtn.disabled) return; // double click guard
			var text = inputEl.value.trim();
			if (!text) {
				showError('Please paste some text to summarise.');
				inputEl.focus();
				return;
			}
			if (countWords(text) < 50) {
				showError('Please paste at least 50 words. Shorter texts do not need summarising.');
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
				body: JSON.stringify({ text: text, length: selectedMode }),
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
					var summary = data.summary || '';
					outputEl.dataset.text = summary;
					// Preserve newlines (important for bullet-point mode)
					outputEl.innerHTML = summary
						.replace(/&/g, '&amp;')
						.replace(/</g, '&lt;')
						.replace(/>/g, '&gt;')
						.replace(/\n/g, '<br>');
					outputCount.textContent = countWords(summary);
					copyBtn.disabled = false;
				})
				.catch(function (err) {
					clearTimeout(timeoutId);
					showFailure(err);
				})
				.finally(function () { setLoading(false); });
		});
	});
})();
