/**
 * AE Motion v2.3 — front-page motion layer.
 * Word-split hero, hairline draws, cursor-reactive hero glow,
 * magnetic CTAs. Fault-tolerant: the headline split restores the
 * original markup on any error. All pointer effects skipped for
 * reduced-motion and touch users.
 */
( function () {
  'use strict';

  var reduceMotion = window.matchMedia
    ? window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
    : false;
  var coarsePointer = window.matchMedia
    ? window.matchMedia( '(pointer: coarse)' ).matches
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
        void h1.offsetWidth;
        requestAnimationFrame( function () { h1.classList.add( 'aem-play' ); } );
        setTimeout( function () { h1.classList.add( 'aem-play' ); }, 500 );
      } catch ( err ) {
        h1.innerHTML = originalHTML;
      }
    }

    /* ---- 2. Hairline draws across sections ------------------ */
    if ( ! reduceMotion && 'IntersectionObserver' in window ) {
      var targets = root.querySelectorAll( '.ae-section, .ae-strip, .ae-cta-band' );
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
    }

    if ( reduceMotion || coarsePointer ) { return; }

    /* ---- 3. Cursor-reactive glow inside the hero ------------ */
    var hero = root.querySelector( '.ae-hero' );
    if ( hero ) {
      var glowQueued = false;
      hero.addEventListener( 'mousemove', function ( e ) {
        if ( glowQueued ) { return; }
        glowQueued = true;
        requestAnimationFrame( function () {
          var r = hero.getBoundingClientRect();
          hero.style.setProperty( '--aem-x',
            ( ( e.clientX - r.left ) / r.width * 100 ).toFixed( 1 ) + '%' );
          hero.style.setProperty( '--aem-y',
            ( ( e.clientY - r.top ) / r.height * 100 ).toFixed( 1 ) + '%' );
          glowQueued = false;
        } );
      } );
    }

    /* ---- 4. Magnetic CTAs ----------------------------------- */
    var buttons = root.querySelectorAll( '.ae-btn' );
    buttons.forEach( function ( btn ) {
      btn.addEventListener( 'mousemove', function ( e ) {
        var r = btn.getBoundingClientRect();
        var dx = ( e.clientX - ( r.left + r.width / 2 ) ) * 0.12;
        var dy = ( e.clientY - ( r.top + r.height / 2 ) ) * 0.22;
        btn.style.transition = 'transform .15s ease-out';
        btn.style.transform = 'translate(' + dx.toFixed( 1 ) + 'px,' + dy.toFixed( 1 ) + 'px)';
      } );
      btn.addEventListener( 'mouseleave', function () {
        btn.style.transition = 'transform .4s cubic-bezier(.19, 1, .22, 1)';
        btn.style.transform = '';
      } );
    } );
  } );
} )();
