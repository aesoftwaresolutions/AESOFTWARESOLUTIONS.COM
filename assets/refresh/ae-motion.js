/**
 * AE Motion v2 — front-page motion layer.
 * Loaded after refresh.js with defer. Layers on top of the
 * existing reveal system; never removes or replaces it.
 * Word-split hero, hairline section draws.
 * Fault-tolerant: any error during the split restores the
 * original headline HTML before the user could notice.
 */
( function () {
  'use strict';

  var reduceMotion = window.matchMedia
    ? window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
    : false;

  function ready( fn ) {
    if ( document.readyState !== 'loading' ) { fn(); }
    else { document.addEventListener( 'DOMContentLoaded', fn ); }
  }

  ready( function () {
    var root = document.querySelector( '.ae-refresh' );
    if ( ! root ) { return; }

    /* ---- 1. Hero headline: masked word reveal -------------- */
    var h1 = root.querySelector( '.ae-hero h1' );
    if ( h1 && ! reduceMotion ) {
      var originalHTML = h1.innerHTML;
      try {
        var i = 0;
        var frag = document.createDocumentFragment();
        var nodes = Array.prototype.slice.call( h1.childNodes );

        nodes.forEach( function ( node ) {
          var text = ( node.textContent || '' ).trim();
          if ( ! text ) { return; }
          var extraClass = ( node.nodeType === 1 && node.className )
            ? ' ' + node.className.trim()
            : '';
          var words = node.nodeType === 1 ? [ text ] : text.split( /\s+/ );

          words.forEach( function ( word ) {
            var mask = document.createElement( 'span' );
            mask.className = 'aem-mask';
            var w = document.createElement( 'span' );
            w.className = ( 'aem-w' + extraClass ).trim();
            w.style.transitionDelay = ( i * 70 ) + 'ms';
            i++;
            w.textContent = word;
            mask.appendChild( w );
            frag.appendChild( mask );
          } );
        } );

        if ( ! i ) { throw new Error( 'no words found' ); }

        h1.textContent = '';
        h1.appendChild( frag );
        void h1.offsetWidth; // force layout so transitions arm
        requestAnimationFrame( function () { h1.classList.add( 'aem-play' ); } );
        setTimeout( function () { h1.classList.add( 'aem-play' ); }, 500 ); // safety
      } catch ( err ) {
        h1.innerHTML = originalHTML; // never leave the headline mangled
      }
    }

    if ( reduceMotion || ! ( 'IntersectionObserver' in window ) ) { return; }

    /* ---- 2. Hairline draws across sections ------------------ */
    var targets = root.querySelectorAll( '.ae-section, .ae-strip, .ae-cta-band' );
    if ( ! targets.length ) { return; }

    var observer = new IntersectionObserver( function ( entries ) {
      entries.forEach( function ( entry ) {
        if ( entry.isIntersecting ) {
          entry.target.classList.add( 'aem-in' );
          observer.unobserve( entry.target );
        }
      } );
    }, { threshold: 0, rootMargin: '0px 0px -10% 0px' } );

    targets.forEach( function ( section ) {
      try {
        var wrap = section.querySelector( ':scope > .ae-wrap' ) || section;
        var hr = document.createElement( 'div' );
        hr.className = 'aem-hr';
        hr.setAttribute( 'aria-hidden', 'true' );
        wrap.insertBefore( hr, wrap.firstChild );
        observer.observe( hr );
      } catch ( err ) { /* skip that section silently */ }
    } );
  } );
} )();
