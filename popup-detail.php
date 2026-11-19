<?php
/**
 * Glass detail popup, printed once in footer.php on every page.
 *
 * A generic glass card popup for topic rows and FAQ answers. The trigger is any
 * button with data-ae-detail. It names the hidden content block with
 * data-ae-detail-target (the slug, matching a div id of ae-detail-<slug>), and
 * carries the card title in data-ae-detail-title and an optional eyebrow in
 * data-ae-detail-eyebrow. assets/js/ae-detail-popup.js clones the hidden block
 * into the card body and opens it. The hidden blocks stay in the page markup so
 * the content is still in the HTML for search engines and for readers without
 * JavaScript (the noscript style below shows them inline).
 *
 * Same shell, close behaviour and page blur as the contact popup (popup-contact.php).
 * Desktop: centred frosted card. Phones: iOS bottom sheet. Closes on the close
 * button, Escape, a tap outside the card, and a downward drag on the grabber.
 *
 * @package AE_Softwares
 */
?>
<div class="ae-pop ae-detail-pop" id="ae-detail-pop" hidden>
	<div class="ae-pop__card" role="dialog" aria-modal="true" aria-labelledby="ae-detail-title" tabindex="-1">
		<span class="ae-sheet__grabber ae-pop__grabber" aria-hidden="true"></span>
		<button type="button" class="ae-pop__close" data-ae-detail-close aria-label="Close">
			<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
		</button>

		<div class="ae-pop__body" id="ae-detail-body">
			<p class="ae-pop__eyebrow" id="ae-detail-eyebrow"></p>
			<h2 class="ae-pop__title" id="ae-detail-title"></h2>
			<div class="ae-detail__content" id="ae-detail-content"></div>
		</div>
	</div>
</div>
<noscript><style>.ae-detail__source{display:block !important}</style></noscript>
