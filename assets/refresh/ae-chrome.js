/**
 * AE Chrome v3.0 — site-wide chrome behavior.
 * 1. Compresses the sticky header after 40px of scroll.
 * 2. Renders a live local-time row in the footer (ET).
 * No visual hooks reused from existing JS; degrades invisibly.
 */
( function () {
  'use strict';

  function ready( fn ) {
    if ( document.readyState !== 'loading' ) { fn(); }
    else { document.addEventListener( 'DOMContentLoaded', fn ); }
  }

  ready( function () {
    /* ---- 1. Sticky header compression ----------------------- */
    var header = document.querySelector( 'header.site-header' );
    if ( header ) {
      var onScroll = function () {
        header.classList.toggle( 'aec--scrolled', window.scrollY > 40 );
      };
      window.addEventListener( 'scroll', onScroll, { passive: true } );
      onScroll();
    }

    /* ---- 2. Footer local-time clock --------------------------- */
    var bottom = document.querySelector( '.footer-bottom' );
    if ( bottom ) {
      var clock = document.createElement( 'div' );
      clock.className = 'footer-meta';
      clock.id = 'ae-clock';
      bottom.appendChild( clock );

      var fmt;
      try {
        fmt = new Intl.DateTimeFormat( 'en-US', {
          timeZone: 'America/New_York',
          hour: 'numeric',
          minute: '2-digit',
          second: '2-digit',
          hour12: true
        } );
      } catch ( e ) { fmt = null; }

      if ( fmt ) {
        var tick = function () {
          clock.textContent =
            fmt.format( new Date() ).toUpperCase() + ' ET';
        };
        tick();
        setInterval( tick, 1000 );
      } else {
        clock.textContent = 'EASTERN TIME';
      }
    }
  } );
} )();
