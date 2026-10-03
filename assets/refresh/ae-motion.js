/**
 * AE Motion — front-page motion layer.
 * Loaded after refresh.js with defer. Layers on top of the
 * existing reveal system; never removes or replaces it.
 * Word-split hero, hairline section draws, reduced-motion safe.
 */
( function () {
  'use strict';

  var docEl = document.documentElement;
  docEl.classList.add( 'aem-js' );

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
    if ( h1 ) {
      var i = 0;
      var frag = document.createDocumentFragment();
      var nodes = Array.prototype.slice.call( h1.childNodes );

      nodes.forEach( function ( node ) {
        var isEl = node.nodeType === 1;
        var text = ( node.textContent || '' ).trim();
        if ( ! text ) { return; }

        var extraClass = ( isEl && node.className ) ? ' ' + node.className.trim() : '';
        var words = isEl ? [ text ] : text.split( /\s+/ );

        words.forEach( function ( word ) {
          var mask = document.createElement( 'span' );
          mask.className = 'aem-mask';
          var w = document.createElement( 'span' );
          w.className = 'aem-w' + extraClass;
          if ( ! reduceMotion ) { w.style.transitionDelay = ( i * 70 ) + 'ms'; }
          i++;
          w.textContent = word;
          mask.appendChild( w );
          frag.appendChild( mask );
        } );
      } );

      h1.textContent = '';
      h1.appendChild( frag );
      h1.classList.add( 'aem-ready' );

      var kick = function () { h1.classList.add( 'aem-play' ); };
      var fired = false;
      var play = function () {
        if ( fired ) { return; }
        fired = true;
        requestAnimationFrame( kick );
      };

      if ( ! reduceMotion ) {
        if ( document.fonts && document.fonts.ready ) {
          document.fonts.ready.then( play );
        }
        setTimeout( play, 600 );
      } else {
        kick();
      }
    }

    if ( reduceMotion ) { return; }

    /* ---- 2. Hairline draws across sections ------------------ */
    var targets = root.querySelectorAll( '.ae-section, .ae-strip, .ae-cta-band' );
    if ( ! targets.length || ! ( 'IntersectionObserver' in window ) ) { return; }

    var observer = new IntersectionObserver( function ( entries ) {
      entries.forEach( function ( entry ) {
        if ( entry.isIntersecting ) {
          entry.target.classList.add( 'aem-in' );
          observer.unobserve( entry.target );
        }
      } );
    }, { threshold: 0, rootMargin: '0px 0px -10% 0px' } );

    targets.forEach( function ( section ) {
      var wrap = section.querySelector( ':scope > .ae-wrap' ) || section;
      var hr = document.createElement( 'div' );
      hr.className = 'aem-hr';
      hr.setAttribute( 'aria-hidden', 'true' );
      wrap.insertBefore( hr, wrap.firstChild );
      observer.observe( hr );
    } );
  } );
} )();
