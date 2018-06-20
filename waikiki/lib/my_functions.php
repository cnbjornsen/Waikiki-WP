<?php
/**
 * Studio Pro.
 *
 * This file adds custom functionality to the Studio Pro theme.
 *
 * @package      Studio Pro CPC addons
 * @link         https://seothemes.com/studio-pro
 * @author       cnbjornsen
 * @copyright    Copyright © 2018 Cnbjornsen
 * @license      GPL-2.0+
 */


/* Reposition primary nav */
//remove_action( 'genesis_after_title_area', 'genesis_do_nav' );
//add_action( 'genesis_site_title', 'genesis_do_nav' );

// Register Front Page 7 widget area.
genesis_register_sidebar( array(
	'id'          => 'front-page-7',
	'name'        => __( 'Front Page 7', 'studio-pro' ),
	'description' => __( 'Front page 7 widget area.', 'studio-pro' ),
) );

// Modify breadcrumb arguments.
add_filter( 'genesis_breadcrumb_args', 'sp_breadcrumb_args' );

function sp_breadcrumb_args( $args ) {
	$args['labels']['prefix'] = '';
return $args;
}

// Remove Product description heading
add_filter( 'woocommerce_product_description_heading', 'remove_product_description_heading' );

function remove_product_description_heading() {
 return '';
}

// Remove Additional information & review tabs
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    //unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;
}

// Remove ALL Woocommerce styling
/*add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );*/

// Remove each Woocommerce style one by one
add_filter( 'woocommerce_enqueue_styles', 'jk_dequeue_styles' );
function jk_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	//unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	//unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
	return $enqueue_styles;
}

 /**
 * Change price format from range to "From:"
 *
 * @param float $price
 * @param obj $product
 * @return str
 */
/*function iconic_variable_price_format( $price, $product ) {
 
    $prefix = sprintf('%s: ', __('From', 'iconic'));
 
    $min_price_regular = $product->get_variation_regular_price( 'min', true );
    $min_price_sale    = $product->get_variation_sale_price( 'min', true );
    $max_price = $product->get_variation_price( 'max', true );
    $min_price = $product->get_variation_price( 'min', true );
 
    $price = ( $min_price_sale == $min_price_regular ) ?
        wc_price( $min_price_regular ) :
        '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . '</ins>';
 
    return ( $min_price == $max_price ) ?
        $price :
        sprintf('%s%s', $prefix, $price);
 
}
 
add_filter( 'woocommerce_variable_sale_price_html', 'iconic_variable_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'iconic_variable_price_format', 10, 2 );

*/

// Add custom sizing table tab
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
function woo_new_product_tab( $tabs ) {
	
	// Adds the new tab
	
	$tabs['size_tab'] = array(
		'title' 	=> __( 'Størrelsesguide', 'woocommerce' ),
		'priority' 	=> 50,
		'callback' 	=> 'woo_new_product_tab_content'
	);

	return $tabs;

}
function woo_new_product_tab_content() {

	// The new tab content

	// check if the repeater field has rows of data
	if( have_rows('storrelsestabel') ):
	
	 	// loop through the rows of data
	    while ( have_rows('storrelsestabel') ) : the_row();
	
	        // display a sub field value
	        the_sub_field($sub_field_name, $format_value);
	
	    endwhile;
	
	else :
	
	    // no rows found
	
	endif;
	
  ?><table>
  <tbody>
    <tr>
      <th>Længde</th>
      <th>Nose bredde</th>
      <th>Bredde</th>
      <th>Tail bredde</th>
      <th>Tykkelse</th>
      <th>Volume (L)</th>
      <th>Vægt (KG)</th>
    </tr>
    <?php 
      if (get_field('storrelsestabel')) {
        $index = 1;
        while (has_sub_field('storrelsestabel')) {
          ?>
            <tr<?php 
                   if ($index % 2 != 0) {
                    ?> class="bg"<?php 
                  }
                ?> class="no-bg">
              <td><?php the_sub_field('laengde'); ?></td>
              <td><?php the_sub_field('nose_bredde'); ?></td>
              <td><?php the_sub_field('bredde'); ?></td>
              <td><?php the_sub_field('tail_bredde'); ?></td>
              <td><?php the_sub_field('tykkelse'); ?></td>
              <td><?php the_sub_field('volume'); ?> L</td>
              <td><?php the_sub_field('vaegt'); ?> KG</td>
            </tr>
          <?php 
          $index++;
        } // end while
      } // end if
    ?>
  </tbody>
</table><?php	
}

// Allow SVG
add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {

  global $wp_version;
  if ( $wp_version !== '4.7.1' ) {
     return $data;
  }

  $filetype = wp_check_filetype( $filename, $mimes );

  return [
      'ext'             => $filetype['ext'],
      'type'            => $filetype['type'],
      'proper_filename' => $data['proper_filename']
  ];

}, 10, 4 );

function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

function fix_svg() {
  echo '<style type="text/css">
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}
add_action( 'admin_head', 'fix_svg' );

// Remove Studio Pro Custom header on product pages
function remove_studio_custom_header() {
	if ( is_woocommerce()) {
	// Remove custom page header.
	//remove_action( 'studio_page_header', 'genesis_do_posts_page_heading' );
	//remove_action( 'studio_page_header', 'genesis_do_date_archive_title' );
	//remove_action( 'studio_page_header', 'genesis_do_taxonomy_title_description' );
	//remove_action( 'studio_page_header', 'genesis_do_author_title_description' );
	//remove_action( 'studio_page_header', 'genesis_do_cpt_archive_title_description' );
	//remove_action( 'genesis_before', 'studio_page_header_setup' );
	remove_action( 'genesis_before_content_sidebar_wrap', 'studio_page_header' );


	}
}
add_action('genesis_before', 'remove_studio_custom_header');

// Load custom CSS
add_action( 'wp_enqueue_scripts', 'custom_load_custom_style_sheet' );
function custom_load_custom_style_sheet() {
	//wp_enqueue_style( 'custom-stylesheet', CHILD_URL . '/lib/my_style.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() . '/assets/fonts/fontawesome-all.min.css');
	
}

// Enqueue Font Awesome.
add_action( 'wp_enqueue_scripts', 'custom_load_font_awesome' );
function custom_load_font_awesome() {
    wp_enqueue_script( 'font-awesome', get_stylesheet_directory_uri() . '/lib/js/fontawesome-all.min.js', array(), null );
}
// Enqueue Flickity JS.min & CSS.
add_action( 'wp_enqueue_scripts', 'custom_load_flickity' );
function custom_load_flickity() {
    wp_enqueue_script( 'flickity-js', get_stylesheet_directory_uri() . '/lib/js/flickity.pkgd.min.js', array(), null );
    wp_enqueue_style( 'flickity-css', get_stylesheet_directory_uri() . '/lib/css/flickity.min.css', array(), null );
}

// Enqueue Extras JS file.
add_action( 'wp_enqueue_scripts', 'custom_load_extras_js' );
function custom_load_extras_js() {
    wp_register_script('extras', get_stylesheet_directory_uri() . '/lib/js/extras.js', array(jquery));
    wp_enqueue_script ('extras');
}

add_filter( 'script_loader_tag', 'add_defer_attribute', 10, 2 );
/**
 * Filter the HTML script tag of `font-awesome` script to add `defer` attribute.
 *
 * @param string $tag    The <script> tag for the enqueued script.
 * @param string $handle The script's registered handle.
 *
 * @return   Filtered HTML script tag.
 */
function add_defer_attribute( $tag, $handle ) {
    if ( 'font-awesome' === $handle ) {
        $tag = str_replace( ' src', ' defer src', $tag );
    }

    return $tag;
}

if ( class_exists( 'WooCommerce' ) ) {
    
    //Add icon navigation cart and search
    add_action( 'genesis_after_title_area', 'waikiki_icon_nav' );
    function waikiki_icon_nav() {
	    echo '<section class="icon-nav" id="icon-navigation">';
	    
        echo '<div class="mini-cart"><div class="mini-cart-icon"><i class="fal fa-shopping-bag"></i></div><a class="cart-count">' . WC()->cart->cart_contents_count  . '</a>';
        echo '<div class="cart-dropdown">';
        woocommerce_mini_cart();
		echo '</div>';
		echo '</div>';
		
		echo '<div class="waikiki-search"><div class="search-icon"><i class="fal fa-search"></i></div>';
	    echo '<div class="search-content">';
	    get_search_form();
	    echo '</div>';
		echo '</div>';
		
		echo '</section>';     
    }
    
    // Add mini filter dropdown for mobile screens
    add_action( 'woocommerce_before_shop_loop', 'waikiki_add_filter' );
    function waikiki_add_filter() {
        echo '<section class="mini-filter"><div class="mini-filter-button"><a class="filter-button button" href="#" title="Set your filters">Filtrer <span><i class="far fa-filter"></i></span></a></div>';
        /*echo '<div class="filter-dropdown">';
        genesis_do_sidebar();
		echo '</div>';*/
		echo '</section>';     
    }
    
    // Enqueue Infinite-scroll JS file.
    add_action( 'wp_enqueue_scripts', 'custom_load_infinitescroll_js' );
    function custom_load_infinitescroll_js() {
    wp_register_script('infinitescroll', get_stylesheet_directory_uri() . '/lib/js/infinite-scroll.pkgd.min.js', array(jquery));
    wp_enqueue_script ('infinitescroll');
    }
    // Add load more button for Infinite-scroll
    add_action( 'woocommerce_after_shop_loop', 'waikiki_load_more' );
    function waikiki_load_more() {
    /*echo '<section class="load-more"><div class="load-more-content"><a class="fwp-load-more load-more-button button white" href="#" title="Load more">Vis flere produkter NY</a></div>';
    echo '</section>';*/
    echo '<section class="load-more"><div class="load-more-content"><a class="load-more-button button white" href="#" title="Load more">Vis flere produkter</a></div>';
    echo '</section>';
    echo '<div class="page-load-status">
  <div class="loader-ellips infinite-scroll-request">
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
  </div>
  <p class="infinite-scroll-last">Thats all folks!</p>
  <p class="infinite-scroll-error">Thats all folks!</p>
</div>';
}

}

/* Change currency symbol and position - WooCommerce */
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

function change_existing_currency_symbol( $currency_symbol, $currency ) {
     switch( $currency ) {
          case 'DKK': $currency_symbol = ',-'; break;
     }
     return $currency_symbol;
}

/* Add custom icon field to menus */
add_filter('wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2);

function my_wp_nav_menu_objects( $items, $args ) {
	
	// loop
	foreach( $items as &$item ) {
		
		// vars
		$svgicon = get_field('icon_path', $item);
		$faicon = get_field('faicon', $item);
		
		
		// append icon
		if( $faicon ) {
			
			$item->title .= '<i class="fa fa-'.$faicon.'"></i>';
			
		} elseif($svgicon) {
			
			$item->title .= '<img src="' . get_stylesheet_directory_uri() . '/lib/img/' . $svgicon .'" class="menu-icon"/>';
		}
		
	}
	
	// return
	return $items;
	
}

/* Force layout for WooCommerce Product categories*/
add_action( 'get_header', 'waikiki_force_layout' );
function waikiki_force_layout() {

	if ( is_shop() || is_product_category() ) {
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_sidebar_content' );
	} else if ( is_product() ) {
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
	}

}

/* Add FacetWP functionality to the Genesis framework - Here we add the required facet-wp class to the products loop */
function custom_facetwp_class( $atts ) {
    $atts['class'] .= ' facetwp-template';
    return $atts;
}
add_filter( 'woocommerce_attribute', 'custom_facetwp_class' );


/*************************
WEB REVENUE INFINITE SCROLLING
*************************/
/*
/* Function that will set infinite scrolling to be displayed in the page.
*/
function set_infinite_scrolling(){
    if( is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_shop() ) {//again, only when we have more than 1 post
    //add js script below
    ?>    
        <script type="text/javascript">            
            jQuery('.products').infiniteScroll({
              // options
              path: '.woocommerce-pagination a.next',
              append: '.product',
              history: false,
              scrollThreshold: false,
              button: '.load-more-button',
              hideNav: '.woocommerce-pagination',
              status: '.page-load-status',
            });
        </script>        
    <?
    }
}
/*    we need to add this action on page's footer.
    100 is a priority number that correpond a later execution.
*/
add_action( 'wp_footer', 'set_infinite_scrolling',100 );

function waikiki_close_nav(){
    echo '<button class="nav-toggle">';
        echo '<li></li><li></li><li></li>';
    echo '</button>';
}
add_action('genesis_before_menu-primary_wrap', 'waikiki_close_nav');