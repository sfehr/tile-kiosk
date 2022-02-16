/**
 * File: tk-scripts.js.
 * Author: Sebastian Fehr
 * Desc: JavaScript for tile-kiosk site
 * 
 * 
 * 
 * 
 * 
 * document.ready()			 			 | Callback on initial load
 * document.ajaxSuccess()				 | Callback on ajax success
 * window.resize()				         | Fires on window resize
 * WP Shopify Hooks						 | Modifications for the WP Shopify Plugin 
 * tk_get_theme_text_domain()			 | get theme text domain
 * sf_loadmore()						 | Ajax call to load more posts on scroll
 * tk_ajaxLoader()						 | Ajax Callback 
 * tk_open_modal()				 		 | Handles the interaction of the modal
 * tk_header_interaction()				 | Handles interaction of the header elements
 * tk_showcase_slider()			 		 | Image slider for showcase images and movies
 * tk_showcase_interaction()			 | Handles the interaction for the showcase	
 * sf_auto_scroll() 					 | Lets a scroll container automatically scroll
 * tk_marquee()							 | Handles the marquee effect by using the jquery.plugin
 * tk_scroll_to()						 | Scrolls to anchor element in DOM
 * tk_shuffle_array() 					 | Shuffles the keys of an array
 * tk_filter_interaction() 				 | Handles interaction of the filter
 * tk_after_rendering()					 | Handles product interaction after it has been rendered 
 * tk_map_svg()							 | Maps SVG images over showcasce image
 * tk_intersection_observer()			 | Checks if an element is in viewport
 *
 *
 *  
 *   
 */


// GLOBALS
var tk_canBeLoaded = true;
var tk_intervall = false;
var tk_filterOptions = {
	'data'  : '',
	'event' : '',
};
var tk_is_coarse = matchMedia( '(pointer:coarse)' ).matches;
var tk_vw = Math.max( document.documentElement.clientWidth || 0, window.innerWidth || 0 );
var tk_vh = Math.max( document.documentElement.clientHeight || 0, window.innerHeight || 0 );
var { __, _x, _n, sprintf } = wp.i18n; // for internationalization
var tk_timer = null



/* ON READY
 *
 * Fires on initial page load
 *
 */ 
jQuery( document ).ready(

//	tk_open_modal(),
//	sf_loadmore(),
	tk_header_interaction(),
//	tk_showcase_slider(),
	tk_showcase_interaction(),
	tk_filter_interaction(),
	tk_map_svg(),
	tk_intersection_observer(),
	
);



/* ON AJAX SUCCESS
 *
 * Fires after ajax success
 *
 */
jQuery( document ).ajaxSuccess( function() {

	tk_open_modal(),
	sf_loadmore(),
	tk_fade_in_followup(),
	tk_scroll_to( '#tk-tiles' )
	
});



/* ON RESIZE
 *
 * Fires on window resize
 *
 */
jQuery( window ).on( 'resize', function() {
	
	// VARS
	var products = jQuery( '.type-wps_products' );
	tk_vw = Math.max( document.documentElement.clientWidth || 0, window.innerWidth || 0 ); // update units
	tk_vh = Math.max( document.documentElement.clientHeight || 0, window.innerHeight || 0 ); // update units
	
	// Resize the bg image of the product
	products.each( function(){
		jQuery( this ).height( jQuery( this ).width() );
	});
	
	tk_horizontal_scroll();
	
});



/* JS HOOKS
 *
 * Hook Settings
 *
 */
// WP SHOPIFY HOOKS
/*
wp.hooks.addFilter( 'default.cart.title', 'shopwp', function( defaultTitle ) {
	return __( 'Cart', tk_get_theme_text_domain() );
})
wp.hooks.addFilter( 'cart.lineItem.remove.text', 'shopwp', function ( defaultText ) {
	return tk_i18n.wps_cart_remove;
});
wp.hooks.addFilter( 'cart.subtotal.text', 'shopwp', function ( defaultText ) {
  return tk_i18n.wps_cart_subtotal;
});
wp.hooks.addFilter( 'default.cart.checkout.text', 'shopwp', function ( defaultCheckoutText ) {
  return tk_i18n.wps_cart_checkout;
});

// RENDER PRODUCTS
wp.hooks.addAction( 'after.payload.update', 'shopwp', function( itemsState ) {
	tk_after_rendering( itemsState.payloadSettings.postId );
});

// PRODUCT ADD TO CART
wp.hooks.addAction( 'after.cart.ready', 'shopwp', function ( cartState ) {
	wp.hooks.addAction( 'after.product.addToCart', 'shopwp', function ( lineItems, variant ) {
		jQuery( 'body' ).find( '.modal' ).removeClass( 'modal' );
	});
});

// CURRENCY
wp.hooks.addFilter( 'misc.pricing.defaultCurrencyCode', 'shopwp', function ( defaultCode ) {
  return 'JPY';
});

// LEFT IN STOCK
wp.hooks.addFilter( 'misc.inventory.leftInStock.total', 'shopwp', function (leftInStockTotal) {
  return 1;
});

// MAX QUANTITY

wp.hooks.addAction( 'after.cart.ready', 'shopwp', function ( cartState ) {
  wp.hooks.addFilter( 'cart.lineItems.maxQuantity', 'shopwp', function (
    maxQuantity,
    cartState,
    lineItem
  ) {
    return 1;
  });
});
*/



/* TK TEXTDOMAIN
 *
 * get theme text domain
 *
 */
function tk_get_theme_text_domain() {
	$textdomain = 'tile_kiosk';
	return $textdomain;
}



/* LOAD MORE
 *
 * Ajax call to load more posts on scroll
 *
 */ 
function sf_loadmore(){
	
	// VARS
	var bottomOffset = tk_vh + 200; // vh is 0 and 200 the actual offset
	
	// EVENT
	jQuery( document ).on( 'scroll', function(){
		
		// CHECK SCROLL POSITION
		if( ( jQuery( document ).scrollTop() > ( jQuery( document ).height() - bottomOffset ) ) && ( tk_canBeLoaded == true ) && ( ! tk_filterOptions.data.length ) && ( sf_ajax_loader_params.current_page < sf_ajax_loader_params.max_page ) ){
			// make ajax call
			tk_filterOptions.event = 'scrollInteraction';
			tk_ajaxLoader();
		}
	});	
}



// AJAX CALLBACK
function tk_ajaxLoader() {	
	
	// VARS
	var url = sf_ajax_loader_params.ajaxurl; // passed over from wp_localize_script()
	var content_container = ( '' != sf_ajax_loader_params.contentContainer ) ? sf_ajax_loader_params.contentContainer : jQuery( 'body article' ).parent();
	var filter_options = ( tk_filterOptions.data.length ) ? tk_filterOptions.data : false;
	var page = ( tk_filterOptions.event == 'scrollInteraction' ) ? sf_ajax_loader_params.current_page : 0;
	var $loaderState = jQuery( '<div>', {
		'class': 'tk-loader-state'
	});
	$loaderState.text( 'Loading tiles ...' );
		
	var data = {
		action         : 'sf_ajax_loader',
		tk_filter      : filter_options,
		ajaxRequest    : 'yes',
		nonce          : sf_ajax_loader_params.nonce,
		query   	   : sf_ajax_loader_params.posts,
		page   	       : page
	};
		
	// AJAX call is made 
	var ajaxLoaderRequest = jQuery.ajax({			
			
		url        : url, // domain/wp-admin/admin-ajax.php
		type       : 'POST',
		data       : data,
		beforeSend : function(){
			tk_canBeLoaded = false;
			jQuery( content_container ).append( $loaderState );
		},			
	})
			
	// on success
	.done( function( response ) { // response from the PHP action
		
		if( response ){
		
			if( 'filterInteraction' == tk_filterOptions.event ){
				jQuery( content_container ).html( response[ 'data' ] ); // filtered response: replace content of container
				sf_ajax_loader_params.current_page = 1;
			}
			else{
				jQuery( content_container ).append( response[ 'data' ] ); // non-filtered response: add elements additionally to container
				sf_ajax_loader_params.current_page++; // update coutner (post is loaded by scroll)
			}
			// update wp shopify render API 
			wp.hooks.doAction( 'shopwp.render' );
			// re-open for further ajax calls
			tk_canBeLoaded = true;
		}
	})
            
	// something went wrong  
	.fail( function() {
		jQuery( content_container ).html( '<h2>Something went wrong.</h2><br>' );
	})
        
	// after all this time?
	.always( function() {
		jQuery( $loaderState ).remove();
	});
}



/* OPEN MODAL
 *
 * Handles the interaction of the modal
 *
 */
function tk_open_modal(){
	
	// VARS
	var products = jQuery( '.type-wps_products' );
	
	products.each( function(){
		
		var product = jQuery( this );

		// EVENT
		product.off().on( 'click', function( e ){ // on/off to avoid multiple event listeners
			
			e.preventDefault();
			this.blur(); // Manually remove focus from clicked link.
			
			if( e.target.className === 'tk-product' ){
				product.removeClass( 'modal' );
			}
			else{
				product.addClass( 'modal' );
				tk_modal_events( product );				
			}
		});		
	});
	
	// add keyboard event to modal
	function tk_modal_events( ele ){

		if( jQuery( ele ).hasClass( 'modal' ) ){
			
			// KEY
			// CLICK
			jQuery( 'body' ).on( 'keydown click', jQuery( ele ), function( e ){

				if( e.keyCode === 27 || jQuery( e.target ).hasClass( 'tk-modal-close' ) ){
					jQuery( ele ).removeClass( 'modal' );
				}
			});
		}
	}
}



/* HEADER INTERACTION
 *
 * Handles interaction of the header elements
 *
 */
function tk_header_interaction(){
	
	// VARS
	var siteHeader = jQuery( '#masthead' );
	var marquee = jQuery( '.text-float-wrapper' );
	var logoLink = jQuery( '#tk-logo-link' );
	
	// EVENTS
	// Logo + Showcase Image
	jQuery( 'body' ).on( 'click', '#tk-logo-link, #tk-text-float, #tk-showcase', function( e ){
		
		e.preventDefault();
		this.blur(); // Manually remove focus from clicked link.
		
		if( jQuery( this ).is( '#tk-logo-link' ) ){
			siteHeader.removeClass( 'view-filter' );
			siteHeader.removeClass( 'view-info' );
			jQuery( 'body' ).removeClass( 'view-info' );			
			tk_scroll_to( 'body' );
		}
		else{
			tk_scroll_to( '#tk-tiles' );
		}
	});
	
	// Navigation
	jQuery( 'body' ).on( 'click', '#site-navigation .menu-item', function( e ){
		
		e.preventDefault();
		jQuery( this ).blur(); // Manually remove focus from clicked link.
		var linkTitle = jQuery( this ).children( 'a' ).attr( 'title' )
		
		if( 'TK-Filter' == linkTitle ){
			// add class
			siteHeader.toggleClass( 'view-filter' );
			// remove class
			siteHeader.removeClass( 'view-info' );
			jQuery( 'body' ).removeClass( 'view-info' );
		}
		if( 'TK-Info' == linkTitle ){
			// add class
			siteHeader.toggleClass( 'view-info' );
			jQuery( 'body' ).toggleClass( 'view-info' );
			// remove class
			siteHeader.removeClass( 'view-filter' );
		}		
		
	});
	
	
	// Mobile Menu
	jQuery( 'body' ).on( 'click', '.menu-toggle', function( e ){
		jQuery( 'body' ).toggleClass( 'mobile-menu-toggled' );
		siteHeader.removeClass( 'view-filter' );
		siteHeader.removeClass( 'view-info' );
		jQuery( 'body' ).removeClass( 'view-info' );
	});
	
	
	// SCROLL POSITION
	jQuery( document ).on( 'scroll', function(){
		
		var distance = jQuery( '#tk-showcase' ).height();
		
		if( jQuery( this ).scrollTop() > distance ){
			jQuery( 'body' ).addClass( 'detached' )
		}
		else{
			jQuery( 'body' ).removeClass( 'detached' )
		}
	});
	
	// INIT 
	tk_marquee( marquee ); // init marquee
	
}



/* MARQUEE
 *
 * Handles the marquee effect by using the jquery.plugin
 *
 */
function tk_marquee( ele ){

	var element = jQuery( ele );
	var gap = element.width();
	var svg = element.find( 'svg' );
	svg.css( 'transform', 'translateX(' + gap + 'px)' );
  
	var mq = element.marquee({
		duration: 8000, //duration in milliseconds of the marquee
//		speed: 5, //speed is measured in pixels per second.
    	gap: gap, 
    	delayBeforeStart: 0, //time in milliseconds before the marquee will start animating
		direction: 'left', //'left' or 'right'
		duplicated: true, //true or false - should the marquee be duplicated to show an effect of continues flow
		pauseOnHover: true,
	})
	.bind( 'beforeStarting', function() {
		// 
	})
	.bind( 'finished', function() {
		mq.marquee( 'pause' );
	});
}



/* SCROLL TO
 *
 * Scrolls to anchor element in DOM
 *
 */
function tk_scroll_to( dest ){
	
	var dest = jQuery( dest );
	var offsetTop = 0;
		
	jQuery( 'html, body' ).animate({ 
		scrollTop: dest.offset().top + 2 + offsetTop 
	}, 'smooth' ); 	
}



/* SHOWCASE SLIDER
 *
 * Image slider for showcase images and movies
 *
 *
function tk_showcase_slider(){
	
	var container = jQuery( '#tk-showcase .itm-wrapper' );
	var shuffledContainer = tk_shuffle_array( container.children( '.entry-media' ) ); // create random order
	container.html( shuffledContainer ); // exchange to random order
	
	// call slick
	container.slick({
		dots      	   : false,
		arrows         : false,
		infinite  	   : true,
		autoplaySpeed  : 3500,
		speed     	   : 1000,
		fade      	   : true,
		cssEase   	   : 'linear',
		autoplay       : true,
		zIndex	       : 1,
		pauseOnHover   : false,
		pauseOnFocus   : false,
		slidesToShow   : 1,
		slidesToScroll : 1,
//		rows           : ( tk_is_coarse && ( tk_vw < 600 ) && ( tk_vh > tk_vw ) ) ? 2 : 1, // show 2 slides for mobile in portrait
	});
}
*/



/* SHOWCASE
 *
 * Handles the interaction for the showcase
 *
 */
function tk_showcase_interaction(){
	
//	var root = document.documentElement;
	var showcase = document.querySelector( '#tk-showcase' );
	var container = jQuery( '#tk-showcase .itm-wrapper' );
	var shuffledContainer = tk_shuffle_array( container.children( '.entry-media' ) ); // create random order
	
	// call shuffle
	container.html( shuffledContainer ); // exchange to random order
	// call auto scroll
//	sf_auto_scroll( showcase, 1, true );
	// call horizontal scroll
	tk_horizontal_scroll();
	
}



/* AUTO SCROLL
 *
 * Lets a scroll container automatically scroll
 * @param	(HtmlObject) objectElement | html container element to scroll
 * @param   (int) scroll               | direction
 * @param   (bol) horizontal           | axis direction to scroll
 *
 */
function sf_auto_scroll( objectElement, scroll, horizontal ){
	
	// VARS
	var timer = setInterval( scroll_move, 30 );
	var timerTouch;

	
	// EVENTS
	objectElement.addEventListener( 'touchstart', function(){
		clearInterval( timer );
		clearInterval( timerTouch );
	});
	objectElement.addEventListener( 'touchend', function(){
		clearInterval( timerTouch );
		timerTouch = setInterval( function(){
			timer = setInterval( scroll_move , 30 );
			clearInterval( timerTouch );
		}, 1500 );
	});
	
	
	function scroll_move(){
		
		// SCROLL
		if( 0 !== scroll ){
			// position before scrolling
			var before = ( true === horizontal ) ? objectElement.scrollLeft : objectElement.scrollTop; 
			
			// check the axis direction to scroll
			if( horizontal ){
				objectElement.scrollBy( scroll, 0 );
			}
			else{
				objectElement.scrollBy( 0, scroll );				
			}
			// position after scrolling
			var after = ( true === horizontal ) ? objectElement.scrollLeft : objectElement.scrollTop; 
			
			// REVERSE DIRECTION 
			if( before === after ){ // end has been reached
				scroll = -1;
				clearInterval( timer );
				timer = setInterval( scroll_move , 30 );
				
			}
			if( before === 0 ){ // start has been reached
				scroll = 1;
				clearInterval( timer );
				timer = setInterval( scroll_move , 30 );
			}				
		}
	}	
}



/* SHUFFLE ARRAY
 *
 * Shuffles the keys of an array
 * @param	array
 * @return 	array (with shuffled keys) 
 *
 */
function tk_shuffle_array( array ) {
	
	var currentIndex = array.length, temporaryValue, randomIndex;
	
	while ( 0 !== currentIndex ) {
		randomIndex = Math.floor( Math.random() * currentIndex );
		currentIndex -= 1;
		temporaryValue = array[ currentIndex ];
		array[ currentIndex ] = array[ randomIndex ];
		array[ randomIndex ] = temporaryValue;
	}
	
	return array;
}



/* HORIZONTAL SCROLL
 *
 * Lets a scroll container scroll horizontally
 *
 */
function tk_horizontal_scroll(){
	
	// CONDITIONAL
	if( tk_is_coarse ){
		
		var showcase = document.querySelector( '#tk-showcase' );
		//sf_auto_scroll( showcase, 1, true );
		return; // early escape if mobile device is detected
	}
	
	// UNITS
	var vw = window.innerWidth;
	var vh = window.innerHeight;
	var container = jQuery( '#tk-showcase .section-wrapper' );
	var wrapper = jQuery( container ).find( '.itm-wrapper' );
	var children = wrapper.find( '.entry-media' ).not( '.itm-svg-map' );
	var numChildren = children.length;
	var	style = window.getComputedStyle( children[ 0 ] );
	var	gap = style.getPropertyValue( 'margin-right' ).replace( 'px', '' );	
	var gapTotal = ( numChildren - 1 ) * gap;
	var containerHeight = ( ( numChildren * vw + gapTotal ) - vw + vh );
	
	// adjust height of the sticky container to have enough scroll space
//	jQuery( container ).height( containerHeight );
	jQuery( '#tk-showcase' ).height( containerHeight );
		
	jQuery( document ).on( 'scroll', function( event ){ // depending on the position property the event should listen to document
		
		var scrollTop = jQuery( document ).scrollTop();
		
		if( scrollTop < ( containerHeight - vh ) ){
			
			// scroll left
//			jQuery( container ).scrollLeft( scrollTop );
			
			// translateX
			jQuery( wrapper ).css({
				'transform': 'translateX(-' + scrollTop + 'px)',
			});
			
		}
		else{
			jQuery( wrapper ).css({
				'transform': 'translateX(-' + ( containerHeight - vh ) + 'px)',
			});			
		}
	});
}



/* FILTER INTERACTION
 *
 * Handles interaction of the filter
 *
 */
function tk_filter_interaction(){
	
	jQuery( 'body' ).on( 'change reset', '#tk-filter-form', function( e ){
		
		tk_filterOptions.data = jQuery( this ).serializeArray();
		tk_filterOptions.event = 'filterInteraction';
		
		// clear all values when reset was clicked
		if( e.type === 'reset' ){
			tk_filterOptions.data = '';
		}
		
		// make ajax call
		tk_ajaxLoader();
		
	});
}



/* AFTER RENDERING
 *
 * Handles product interaction after it has been rendered
 * @param	int   post id 
 *
 */
function tk_after_rendering( post_id ){
	
	var post = jQuery( '#post-' + post_id );
	
	if( post.attr( 'data-tk-bg' ) ){
		return; // early exit
	}
	
	// Adds the background image to the container for visual stability when ajax calls are made
	var img = post.find( '.wps-product-image' );
	var img_src = img.attr( 'src' );
	if( img_src ){
		post.css({
			'background-image': 'url(' + img_src + ')',
		});
		post.height( post.width() );
		post.attr( 'data-tk-bg', 'true' );
	}
	
	// add css class for fade in effect
	img.on( 'load', function(){
		jQuery( this ).parents( 'article' ).addClass( 'fade-in' );
	});
	
}



/* FADE IN FOLLOW UP
 *
 * Checks wether all fade-in classes are set 
 *
 */
function tk_fade_in_followup(){
	
	// check if intervall is set already
	if( ! tk_intervall ){
		tk_intervall = setInterval( int_callback, 2000 ); // set intervall
	}
	
	// intervall
	function int_callback() {
		var remainings = jQuery( '.type-wps_products' ).not( '.fade-in' );
		
		if( remainings.length ){
			
			remainings.each( function(){
				var img = jQuery( this ).find( 'img.wps-product-image' );
				jQuery( this ).addClass( 'fade-in' );
			});
		}
		else{
			clearInterval( tk_intervall );
			tk_intervall = false;
		}
	}
}



/* MAP SVG IMAGES
 *
 * Combines the SVG maps with the related image
 *
 */
function tk_map_svg(){
	
	const svgAll = document.querySelectorAll( '.itm-svg-map' );
	
	svgAll.forEach( (svg) => {
		const name = svg.dataset.name;
		const target = document.querySelector( '.itm-img[data-name="' + name + '"]' );
		
		if( target ){
			svg.style.display = 'flex';
			target.appendChild( svg );
		}
		else{
			svg.remove(); // remove item if corresponding image is not found
		}
	});
	
}



/* INTERSECTION OBSERVER
 *
 * Checks if an element is in viewport
 *
 */
function tk_intersection_observer(){
	
	const numSteps = 20.0;

	let boxElementAll;

	// Set things up
	window.addEventListener(　'load', (　event　) => {
		boxElementAll = document.querySelectorAll( '.itm-svg-map .tk-svg-tag' );
		createObserver();
	}, false );
	
	function createObserver() {
		
		let observer;
		let options = {
			root: null,
//			rootMargin: '-100px -100px -100px -100px',
			rootMargin: '0px',
			threshold: buildThresholdList()
	  };

		observer = new IntersectionObserver( handleIntersect, options );
		boxElementAll.forEach( boxElement => observer.observe( boxElement ) );
	}
	
	function buildThresholdList() {
		let thresholds = [];
		let numSteps = 100;

		for ( let i = 1.0; i <= numSteps; i++) {
			let ratio = i/numSteps;
			thresholds.push( ratio );
		}

		thresholds.push( 0 );
		return thresholds;
	}
	
	function handleIntersect( entries, observer ) {
		
		entries.forEach( ( entry ) => {
			
			if ( entry.isIntersecting ){
				
				let n = entry.intersectionRatio;
				entry.target.style.opacity = n;
				entry.target.style.transform = 'scale( ' + n + ' )';
			}
		});
	}	
}