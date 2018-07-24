//jQuery( "div.filter-dropdown" ).addClass( "active" );
//(function($){
    //$('.mini-filter').on('click', function(){
      //  $(".filter-dropdown").removeClass("active focus hide");
        //$(this).toggleClass("active");
    //});
//}(jQuery));

jQuery(document).ready(function(){
    jQuery('.filter-button').click(function(){
      if ( jQuery('#genesis-sidebar-primary').css('visibility') == 'hidden' )
        jQuery('#genesis-sidebar-primary').css('visibility','visible');
      else
        jQuery('#genesis-sidebar-primary').css('visibility','hidden');
    });

    jQuery( ".mini-filter-close" ).click(function() {
        jQuery('#genesis-sidebar-primary').css('visibility','hidden');
    });

    if (jQuery('.woocommerce-pagination')[0]) {
            jQuery('.load-more-button').show();
        } else if (jQuery('.woocommerce-pagination').css('display') == 'none'){
            jQuery('.load-more-button').hide();
        } else {
            jQuery('.load-more-button').hide();
    }

    jQuery( ".waikiki-search" ).click(function() {
        jQuery('.search-content, .searchwp-live-search-results').toggle();
        jQuery('input[type="search"]').get(0).focus();

    });

    jQuery(".search-content").keyup(function(event){
		if (event.keyCode == 27){
			// Close the modal/menu
			jQuery(".search-content, .searchwp-live-search-results").toggle();

	    //  Return focus to the element that invoked it
			jQuery('.site-container').focus();
		}
	});

    // Show/hide the main navigation
    jQuery('.nav-toggle').click(function() {
        if ( jQuery('.nav-primary').hasClass('activated'))
            jQuery('.nav-primary, .menu-toggle, .nav-toggle').removeClass('activated');
        else
            jQuery('.nav-toggle').addClass('activated');
    });

    jQuery('.menu-toggle').click(function() {
            jQuery('.nav-toggle').addClass('activated');
    });
  /*  //show cart
    jQuery('.mini-cart-icon').click(function(){
      jQuery('.cart-dropdown,.cart-overlay').removeClass('cart-hidden');
    })
    //Hide cart
    jQuery('.close-cart-icon,.cart-overlay').click(function(){
      jQuery('.cart-dropdown,.cart-overlay').addClass('cart-hidden');
    })*/
/*
    jQuery('.mini-cart-icon').on('click', function(){
       jQuery('.cart-dropdown, .cart-overlay').toggleClass('cart-active');
       //jQuery('.main-wrapper').toggleClass('active');
    });

    jQuery('.close-cart-icon, .cart-overlay').on('click', function(event){
        if(!jQuery(event.target).hasClass('nav-icon'))
        {
            jQuery('.offcanvas').removeClass('is-open');
            jQuery('.main-wrapper').removeClass('active');
        }
    }); */
});

//Test new toggle function for off canvas cart
jQuery(document).ready(function(){
// Create off-canvas menu.
var navOffCanvas = $('.cart-dropdown')
var buttonLaunch = $('.mini-cart-icon')
var buttonExit = $('.close-cart-icon')

buttonLaunch.on('click', function () {
  navOffCanvas.fadeIn('fast')
  navOffCanvas.find('.cell-off-canvas').animate({
    width: 'toggle'
  }, 600, 'easeOutExpo')

  return false
})

buttonExit.on('click', function () {
  navOffCanvas.find('.cell-off-canvas').animate({
    width: 'toggle'
  }, 600, 'easeOutExpo')
  navOffCanvas.fadeOut('fast')

  return false
})
});

//after facetwp loads/refreshes, do following
jQuery(document).ready(function(){
    jQuery(document).on('facetwp-refresh', function() {
        //infiniteScroll ();
    })
});


//Hide containing widget wrapper when no results are available from facetwp
jQuery(document).on('facetwp-loaded', function() {
    jQuery.each(FWP.settings.num_choices, function(key, val) {
        var $parent = jQuery('.facetwp-facet-' + key).closest('.widget');
        (0 === val) ? $parent.hide() : $parent.show();
    });
});

//Flickity Initialize featured frontpage
jQuery(document).ready(function () {
  jQuery('.flickity-featured').flickity({
    // options
    cellAlign: 'left',
    contain: true,
    cellSelector: '.carousel-cell',
    pageDots: false,

  });
})
