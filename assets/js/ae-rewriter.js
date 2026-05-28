(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof window.ae_rewriter_settings === 'undefined') return;

		var rewriteUrl   = window.ae_rewriter_settings.rewrite_url;
		var validateUrl  = window.ae_rewriter_settings.validate_url;
		var selectedMode = 'standard';

		var inputEl         = document.getElementById('rewriter-input');
		var outputEl        = document.getElementById('rewriter-output');
		var submitBtn       = document.getElementById('rewriter-submit');
		var clearBtn        = document.getElementById('rewriter-clear');
		var copyBtn         = document.getElementById('rewriter-copy');
		var checkScoreBtn   = document.getElementById('rewriter-check-score');
		var loader          = document.getElementById('rewriter-loader');
		var errorEl         = document.getElementById('rewriter-error');
		var inputCount      = document.getElementById('rewriter-input-count');
		var outputCount     = document.getElementById('rewriter-output-count');
		var modeBtns        = document.querySelectorAll('.rewriter-mode-btn');
		var scorePanel      = document.getElementById('rewriter-score-panel');
		var scoreValue      = document.getElementById('rewriter-score-value');
		var scoreVerdict    = document.getElementById('rewriter-score-verdict');
		var scoreSignals    = document.getElementById('rewriter-score-signals');
		var scoreFlags      = document.getElementById('rewriter-score-flags');

		if (!inputEl || !outputEl || !submitBtn) return;

		// Explicitly hide elements on init — overrides any CSS that may win over [hidden]
		loader.style.display = 'none';
		errorEl.style.display = 'none';

		function countWords(str) {
			var trimmed = str.trim();
			if (!trimmed) return 0;
			return trimmed.split(/\s+/).length;
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

		var SIGNAL_LABELS = {
			perplexity_uniformity:  'Perplexity Uniformity',
			burstiness:             'Burstiness',
			structural_patterns:    'Structural Patterns',
			hedging_and_nuance:     'Hedging & Nuance',
			vocabulary_naturalness: 'Vocabulary Naturalness',
			grammatical_perfection: 'Grammatical Perfection',
			semantic_coherence:     'Semantic Coherence',
		};

		function renderScorePanel(validation) {
			if (!scorePanel || !validation) return;

			var score   = validation.overall_human_score || 0;
			var verdict = validation.verdict || '';
			var flags   = validation.flags || [];
			var signals = validation.signal_scores || {};

			// Overall score
			scoreValue.textContent = score;
			scoreValue.className = 'rewriter-score-panel__value rewriter-score-panel__value--' +
				(score >= 85 ? 'pass' : score >= 65 ? 'borderline' : 'fail');

			// Verdict badge
			scoreVerdict.textContent = verdict.replace(/_/g, ' ');
			scoreVerdict.className = 'rewriter-score-panel__verdict rewriter-score-panel__verdict--' +
				(verdict === 'LIKELY_HUMAN' ? 'pass' : verdict === 'BORDERLINE' ? 'borderline' : 'fail');

			// Signal bars
			scoreSignals.innerHTML = '';
			Object.keys(SIGNAL_LABELS).forEach(function (key) {
				var val   = typeof signals[key] === 'number' ? signals[key] : 0;
				var label = SIGNAL_LABELS[key];
				var cls   = val >= 7 ? 'good' : val >= 5 ? 'mid' : 'bad';
				var li    = document.createElement('li');
				li.className = 'rewriter-score-signals__item';
				li.innerHTML =
					'<span class="rewriter-score-signals__name">' + label + '</span>' +
					'<span class="rewriter-score-signals__track">' +
						'<span class="rewriter-score-signals__bar rewriter-score-signals__bar--' + cls + '" style="width:' + (val * 10) + '%"></span>' +
					'</span>' +
					'<span class="rewriter-score-signals__num">' + val + '/10</span>';
				scoreSignals.appendChild(li);
			});

			// Flags
			if (flags.length) {
				scoreFlags.innerHTML = '';
				flags.forEach(function (f) {
					var li = document.createElement('li');
					li.textContent = f;
					scoreFlags.appendChild(li);
				});
				scoreFlags.hidden = false;
			} else {
				scoreFlags.hidden = true;
			}

			scorePanel.hidden = false;
		}

		if (checkScoreBtn) {
			checkScoreBtn.addEventListener('click', function () {
				var text = outputEl.dataset.text || '';
				if (!text || !validateUrl) return;

				checkScoreBtn.disabled = true;
				checkScoreBtn.textContent = 'Checking…';
				if (scorePanel) scorePanel.hidden = true;

				fetch(validateUrl, {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify({ text: text }),
				})
					.then(function (res) { return res.json(); })
					.then(function (data) {
						renderScorePanel(data.validation);
					})
					.catch(function () {
						/* silently fail — score panel stays hidden */
					})
					.finally(function () {
						checkScoreBtn.disabled = false;
						checkScoreBtn.textContent = 'Check Score';
					});
			});
		}

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
			if (checkScoreBtn) checkScoreBtn.disabled = true;
			if (scorePanel) scorePanel.hidden = true;

			var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
			var timeoutId = setTimeout(function () {
				if (controller) controller.abort();
			}, 420000);

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
					if (checkScoreBtn) checkScoreBtn.disabled = false;
					if (scorePanel) scorePanel.hidden = true;
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
