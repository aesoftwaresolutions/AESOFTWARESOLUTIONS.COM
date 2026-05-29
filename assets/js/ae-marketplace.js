/**
 * ae-marketplace.js
 * Frontend for the private Facebook Marketplace scraper tool.
 * Reads window.ae_marketplace_settings.marketplace_url for the REST endpoint.
 */
( function () {
	'use strict';

	const form     = document.getElementById( 'marketplace-form' );
	const submit   = document.getElementById( 'marketplace-submit' );
	const loader   = document.getElementById( 'marketplace-loader' );
	const errorBox = document.getElementById( 'marketplace-error' );
	const meta     = document.getElementById( 'marketplace-meta' );
	const countEl  = document.getElementById( 'marketplace-count' );
	const queryEl  = document.getElementById( 'marketplace-query-echo' );
	const results  = document.getElementById( 'marketplace-results' );

	if ( ! form ) return;

	const settings = window.ae_marketplace_settings || {};
	const endpoint = settings.marketplace_url || '';
	const nonce    = ( document.getElementById( 'ae_marketplace_nonce' ) || {} ).value || '';

	// ── State helpers ────────────────────────────────────────────────────────

	function setLoading( on ) {
		submit.disabled = on;
		loader.hidden   = ! on;
		if ( on ) {
			errorBox.hidden = true;
			meta.hidden     = true;
			results.innerHTML = '';
		}
	}

	function showError( msg ) {
		errorBox.textContent = msg;
		errorBox.hidden      = false;
	}

	// ── Card renderer ────────────────────────────────────────────────────────

	function renderCard( item ) {
		const card = document.createElement( 'a' );
		card.className = 'mp-card';
		card.href      = item.link || '#';
		card.target    = '_blank';
		card.rel       = 'noopener noreferrer';

		const img = document.createElement( 'div' );
		img.className = 'mp-card__img';
		if ( item.image ) {
			const picture = document.createElement( 'img' );
			picture.src   = item.image;
			picture.alt   = item.title || item.name || '';
			picture.loading = 'lazy';
			picture.onerror = () => { img.classList.add( 'mp-card__img--broken' ); };
			img.appendChild( picture );
		} else {
			img.classList.add( 'mp-card__img--empty' );
		}

		const body  = document.createElement( 'div' );
		body.className = 'mp-card__body';

		const price = document.createElement( 'div' );
		price.className = 'mp-card__price';
		price.textContent = item.price || '—';

		const title = document.createElement( 'div' );
		title.className = 'mp-card__title';
		title.textContent = item.title || item.name || 'Listing';

		const loc = document.createElement( 'div' );
		loc.className = 'mp-card__location';
		loc.textContent = item.location || '';

		body.appendChild( price );
		body.appendChild( title );
		if ( item.location ) body.appendChild( loc );

		card.appendChild( img );
		card.appendChild( body );

		return card;
	}

	function renderResults( items ) {
		results.innerHTML = '';

		if ( ! items || items.length === 0 ) {
			const empty = document.createElement( 'p' );
			empty.className = 'marketplace-empty';
			empty.textContent = 'No listings found. Try a different keyword, city, or price range.';
			results.appendChild( empty );
			return;
		}

		items.forEach( item => results.appendChild( renderCard( item ) ) );
	}

	// ── Form submit ──────────────────────────────────────────────────────────

	form.addEventListener( 'submit', async function ( e ) {
		e.preventDefault();

		const city     = document.getElementById( 'marketplace-city' ).value.trim();
		const query    = document.getElementById( 'marketplace-query' ).value.trim();
		const maxPrice = document.getElementById( 'marketplace-price' ).value.trim();

		if ( ! query ) {
			showError( 'Please enter a search keyword.' );
			return;
		}

		if ( ! endpoint ) {
			showError( 'Marketplace service URL is not configured. Check AE Settings on the server.' );
			return;
		}

		setLoading( true );

		try {
			const body = { city, query };
			if ( maxPrice ) body.max_price = parseInt( maxPrice, 10 );

			const res = await fetch( endpoint, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce':   nonce,
				},
				body: JSON.stringify( body ),
			} );

			const data = await res.json();

			if ( ! res.ok ) {
				throw new Error( data.message || `Server returned ${res.status}` );
			}

			const listings = data.listings || [];

			countEl.textContent  = listings.length + ' listing' + ( listings.length !== 1 ? 's' : '' );
			queryEl.textContent  = `"${query}" in ${city}`;
			meta.hidden          = false;

			renderResults( listings );

		} catch ( err ) {
			showError( 'Error: ' + ( err.message || 'Unknown error. Check the scraper service on the VPS.' ) );
		} finally {
			setLoading( false );
		}
	} );

} )();
