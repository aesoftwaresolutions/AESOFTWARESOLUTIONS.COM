/**
 * Presence check (Online Presence page).
 * Each checkbox has a weight; the score is the ticked share of the total,
 * shown on a ring that fills as you go, with the three biggest unticked
 * items listed as gaps. Nothing is sent anywhere.
 */
(function () {
  'use strict';
  var form = document.getElementById('ae-presence');
  if (!form) return;

  var boxes = Array.prototype.slice.call(form.querySelectorAll('input[type="checkbox"]'));
  var num = document.getElementById('op-score-num');
  var bar = document.querySelector('.op-score__bar');
  var verdict = document.getElementById('op-score-verdict');
  var gaps = document.getElementById('op-score-gaps');
  var gapList = document.getElementById('op-score-gap-list');
  var total = boxes.reduce(function (s, b) { return s + Number(b.dataset.weight || 1); }, 0);
  var shown = 0;
  var raf;

  function animateTo(target) {
    cancelAnimationFrame(raf);
    var calm = document.documentElement.classList.contains('ae-calm');
    if (calm) { shown = target; num.textContent = target; return; }
    (function step() {
      shown += (target - shown) * 0.2;
      if (Math.abs(target - shown) < 0.5) shown = target;
      num.textContent = Math.round(shown);
      if (shown !== target) raf = requestAnimationFrame(step);
    })();
  }

  function update() {
    var got = 0;
    var missing = [];
    boxes.forEach(function (b) {
      var w = Number(b.dataset.weight || 1);
      if (b.checked) got += w; else missing.push({ label: b.dataset.label, w: w });
    });
    var score = Math.round(got / total * 100);
    var touched = boxes.some(function (b) { return b.checked; });

    animateTo(score);
    bar.style.strokeDasharray = score + ' 100';
    form.closest('.op-check').dataset.level = score >= 80 ? 'high' : score >= 50 ? 'mid' : 'low';

    if (!touched) {
      verdict.textContent = 'Start ticking — your score updates as you go.';
    } else if (score >= 80) {
      verdict.textContent = 'Strong. A few small fixes and monthly upkeep will keep you ahead of competitors.';
    } else if (score >= 50) {
      verdict.textContent = 'Solid start, but customers searching nearby are likely finding someone else first.';
    } else {
      verdict.textContent = 'Plenty of quick wins here. Most of these can be fixed in the first two weeks.';
    }

    missing.sort(function (a, b) { return b.w - a.w; });
    gapList.innerHTML = '';
    missing.slice(0, 3).forEach(function (m) {
      var li = document.createElement('li');
      li.textContent = m.label;
      gapList.appendChild(li);
    });
    gaps.hidden = !touched || missing.length === 0;
  }

  form.addEventListener('change', update);
  update();
})();
