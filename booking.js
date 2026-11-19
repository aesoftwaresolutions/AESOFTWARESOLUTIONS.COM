/* Booking flow. Talks to admin-ajax (inc/ae-booking.php). No dependencies.
 * All dates are New York calendar days as YYYY-MM-DD strings; the server sends today and maxDate,
 * so nothing here depends on the visitor's own timezone. */
(function () {
	'use strict';
	var cfg = window.AE_BOOKING;
	var root = document.getElementById('ae-book');
	if (!cfg || !root) { return; }

	var $ = function (id) { return document.getElementById(id); };
	var el = {
		flow: $('ae-book-flow'), days: $('ae-book-days'), month: $('ae-book-month'),
		prev: $('ae-book-prev'), next: $('ae-book-next'),
		stepTime: $('ae-book-step-time'), stepDetails: $('ae-book-step-details'),
		slots: $('ae-book-slots'), slotStatus: $('ae-book-slotstatus'), timeNote: $('ae-book-timenote'),
		chosen: $('ae-book-chosen'), form: $('ae-book-form'), error: $('ae-book-error'),
		submit: $('ae-book-submit'), done: $('ae-book-done'), when: $('ae-book-when'),
		doneMsg: $('ae-book-donemsg'), meetRow: $('ae-book-meetrow'), meet: $('ae-book-meet')
	};
	var WD = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
	var WD_SHORT = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
	var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var DAY = 86400000;

	var state = { weekStart: null, date: null, slot: null, req: 0, busy: false };

	/* ---- date helpers (UTC math on plain calendar days) ---- */
	function toMs(s) { var p = s.split('-'); return Date.UTC(+p[0], +p[1] - 1, +p[2]); }
	function toStr(ms) {
		var d = new Date(ms), m = d.getUTCMonth() + 1, day = d.getUTCDate();
		return d.getUTCFullYear() + '-' + (m < 10 ? '0' : '') + m + '-' + (day < 10 ? '0' : '') + day;
	}
	function mondayOf(s) { var ms = toMs(s), dow = new Date(ms).getUTCDay(); return ms - ((dow + 6) % 7) * DAY; }
	function longDate(s) {
		var d = new Date(toMs(s));
		return WD[d.getUTCDay()] + ', ' + MONTHS[d.getUTCMonth()] + ' ' + d.getUTCDate() + ', ' + d.getUTCFullYear();
	}
	function reduced() { return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches; }
	function reveal(node) {
		node.hidden = false;
		node.scrollIntoView({ behavior: reduced() ? 'auto' : 'smooth', block: 'nearest' });
	}
	function clear(node) { while (node.firstChild) { node.removeChild(node.firstChild); } }
	function make(tag, cls, text) {
		var n = document.createElement(tag);
		if (cls) { n.className = cls; }
		if (text !== undefined) { n.textContent = text; }
		return n;
	}

	/* ---- step 1: weekday strip ---- */
	function renderWeek() {
		clear(el.days);
		var todayMs = toMs(cfg.today), maxMs = toMs(cfg.maxDate), first = state.weekStart;
		for (var i = 0; i < 5; i++) {
			var ms = first + i * DAY, s = toStr(ms), d = new Date(ms);
			var b = make('button', 'ae-book__day');
			b.type = 'button';
			b.setAttribute('data-date', s);
			b.setAttribute('aria-label', longDate(s));
			b.appendChild(make('small', '', WD_SHORT[d.getUTCDay()]));
			b.appendChild(make('strong', '', String(d.getUTCDate())));
			b.appendChild(make('em', '', MONTHS[d.getUTCMonth()].slice(0, 3)));
			b.setAttribute('aria-pressed', s === state.date ? 'true' : 'false');
			if (ms < todayMs || ms > maxMs) { b.disabled = true; }
			el.days.appendChild(b);
		}
		var a = new Date(first), z = new Date(first + 4 * DAY);
		var label = MONTHS[a.getUTCMonth()] + ' ' + a.getUTCFullYear();
		if (a.getUTCMonth() !== z.getUTCMonth()) {
			label = MONTHS[a.getUTCMonth()].slice(0, 3) + ' ' + a.getUTCDate() + ' to ' + MONTHS[z.getUTCMonth()].slice(0, 3) + ' ' + z.getUTCDate() + ', ' + z.getUTCFullYear();
		}
		el.month.textContent = label;
		el.prev.disabled = first <= mondayOf(cfg.today);
		el.next.disabled = first + 7 * DAY > maxMs;
	}

	el.prev.addEventListener('click', function () { state.weekStart -= 7 * DAY; renderWeek(); });
	el.next.addEventListener('click', function () { state.weekStart += 7 * DAY; renderWeek(); });
	el.days.addEventListener('click', function (e) {
		var b = e.target.closest ? e.target.closest('.ae-book__day') : null;
		if (!b || b.disabled) { return; }
		pickDay(b.getAttribute('data-date'));
	});

	/* ---- step 2: slots from the server ---- */
	function pickDay(date) {
		state.date = date; state.slot = null;
		el.stepDetails.hidden = true;
		hideError();
		renderWeek();
		el.timeNote.textContent = longDate(date) + '. All times are Eastern Time.';
		clear(el.slots);
		el.slotStatus.textContent = 'Loading times';
		reveal(el.stepTime);

		var req = ++state.req;
		var url = cfg.ajaxUrl + '?action=ae_booking_slots&date=' + encodeURIComponent(date);
		fetch(url, { credentials: 'same-origin', cache: 'no-store' })
			.then(function (r) { return r.json().catch(function () { return null; }); })
			.then(function (j) {
				if (req !== state.req) { return; }
				if (!j || !j.success) {
					el.slotStatus.textContent = (j && j.data && j.data.message) || 'We could not load times right now. Please try again, or use the direct link below.';
					return;
				}
				renderSlots(j.data.slots || []);
			})
			.catch(function () {
				if (req !== state.req) { return; }
				el.slotStatus.textContent = 'We could not load times right now. Please try again, or use the direct link below.';
			});
	}

	function renderSlots(list) {
		clear(el.slots);
		if (!list.length) {
			el.slotStatus.textContent = 'There are no open times on this day. Please pick another day.';
			return;
		}
		el.slotStatus.textContent = '';
		list.forEach(function (s) {
			var b = make('button', 'ae-book__slot', s.label);
			b.type = 'button';
			b.setAttribute('data-time', s.time);
			b.setAttribute('aria-pressed', 'false');
			el.slots.appendChild(b);
		});
	}

	el.slots.addEventListener('click', function (e) {
		var b = e.target.closest ? e.target.closest('.ae-book__slot') : null;
		if (!b) { return; }
		Array.prototype.forEach.call(el.slots.children, function (n) { n.setAttribute('aria-pressed', n === b ? 'true' : 'false'); });
		state.slot = { time: b.getAttribute('data-time'), label: b.textContent };
		el.chosen.textContent = longDate(state.date) + ' at ' + state.slot.label + ' Eastern Time';
		hideError();
		reveal(el.stepDetails);
		$('ae-book-first').focus({ preventScroll: true });
	});

	/* ---- step 3: submit ---- */
	function showError(msg) { el.error.textContent = msg; el.error.hidden = false; }
	function hideError() { el.error.hidden = true; el.error.textContent = ''; }

	el.form.addEventListener('submit', function (e) {
		e.preventDefault();
		if (state.busy || !state.date || !state.slot) { return; }
		var f = el.form.elements;
		var first = f.first_name.value.trim(), last = f.last_name.value.trim(), email = f.email.value.trim();
		if (!first) { showError('Please enter your first name.'); f.first_name.focus(); return; }
		if (!last) { showError('Please enter your last name.'); f.last_name.focus(); return; }
		if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showError('Please enter a valid email address.'); f.email.focus(); return; }
		hideError();

		state.busy = true;
		el.submit.disabled = true;
		var idle = el.submit.textContent;
		el.submit.textContent = 'Booking';

		var body = new FormData();
		body.append('action', 'ae_booking_create');
		body.append('date', state.date);
		body.append('time', state.slot.time);
		body.append('first_name', first);
		body.append('last_name', last);
		body.append('email', email);
		body.append('website_url', f.website_url.value);

		fetch(cfg.ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' })
			.then(function (r) { return r.json().catch(function () { return null; }); })
			.then(function (j) {
				if (j && j.success) { return confirmed(j.data); }
				var code = j && j.data && j.data.code;
				showError((j && j.data && j.data.message) || 'We could not complete your booking. Please try again, or use the direct link below.');
				if (code === 'slot_taken') { pickDay(state.date); }
			})
			.catch(function () {
				showError('We could not complete your booking. Please try again, or use the direct link below.');
			})
			.then(function () {
				state.busy = false;
				el.submit.disabled = false;
				el.submit.textContent = idle;
			});
	});

	function confirmed(data) {
		el.flow.hidden = true;
		el.when.textContent = data.when || (longDate(state.date) + ' at ' + state.slot.label + ' Eastern Time');
		el.doneMsg.textContent = data.message || 'You are booked. We sent a calendar invite to your email.';
		if (data.meet && data.meet.indexOf('https://meet.google.com/') === 0) {
			el.meet.href = data.meet;
			el.meetRow.hidden = false;
		} else {
			el.doneMsg.textContent += ' The Google Meet link is in that invite.';
		}
		el.done.hidden = false;
		el.done.focus();
		el.done.scrollIntoView({ behavior: reduced() ? 'auto' : 'smooth', block: 'start' });
	}

	/* ---- start ---- */
	state.weekStart = mondayOf(cfg.today);
	var todayDow = new Date(toMs(cfg.today)).getUTCDay();
	if (todayDow === 0 || todayDow === 6) { state.weekStart += 7 * DAY; } // weekend: start on next Monday
	renderWeek();
})();
