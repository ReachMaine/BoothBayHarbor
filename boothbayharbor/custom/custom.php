<?php /*  custom functions for Boothbay Harbor
*/
//function for shortcode ecc_categories
if (!function_exists('bhh_get_members')) {
	function bhh_get_members( $atts ) {
    $atts = shortcode_atts( array(
      'show_all' => true,
    ), $atts, 'bbh_memberlist' );


  } // end function bhh_get_members()
} // end function exists.

add_shortcode( 'bbh_memberlist', 'bhh_get_members' );

// function for shortcode bbh_categories
if (!function_exists('bbh_listing_categories_display')) {
	function bbh_listing_categories_display( $atts ) {
		$atts = shortcode_atts( array(
			'show_all' => true,
		), $atts, 'bbh_categories' );

	// List terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)

	$taxonomy     = 'listing_categories';
	$orderby      = 'name';
	$show_count   = false;
	$pad_counts   = false;
	$hierarchical = true;
	$title        = '';

	$args = array(
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'title_li'     => $title,
	  'echo'		 => 0,  // return, dont output
	);

 		$html_out = "";
		$html_out .= '<ul class="bbh_members_cats">';
	    $html_out .= wp_list_categories( $args );
		$html_out .= "</ul>";
		return $html_out;
	}
}
add_shortcode( 'bbh_categories', 'bbh_listing_categories_display' );

// function for shortcode bbh_categories
if (!function_exists('bbh_listing_categories_type')) {
	function bbh_listing_categories_type( $atts ) {
		$atts = shortcode_atts( array(
			'show_all' => true,
			'title' => '',
			'type' => 'stay',
		), $atts, 'bbh_listing_categories_type' );
  $type 			  = $atts['type'];
	$title        = $atts['title'];
	// List terms in a given taxonomy using wp_list_categories (also useful as a widget if using a PHP Code plugin)
	$taxonomy     = 'listing_categories';
	$orderby      = 'name';
	$show_count   = false;
	$pad_counts   = false;
	$hierarchical = true;



	$args = array(
		'type' => 			$type,
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
    'meta_query' => array(
					array(
							'key' => 'listing_category_listing_types',
							'value' => $type,
							'compare' => 'LIKE'
						)
			)
	);
	$categories = get_categories( $args );
	$html_out = "";
	if ($title) {
		$html_out .= '<h4 class="bbh_listcat_title">'.$title."</h3>";
	}
	$html_out .= "<!-- type is: ".$type."-->";
	if ($categories) {
			//echo "<pre>"; var_dump($categories); echo "</pre>";
			$html_out .= '<ul class="bbh_listcats">';
			foreach ( $categories as $cat ) {
			    $html_out .= '<li class="bbh_listcat">';
						$html_out .= '<a href="'.get_category_link($cat->term_id).'" >';
							$html_out .= $cat->name;
						$html_out .= '</a>';
					$html_out .= '</li>';
			}
			$html_out .= "</ul>";
		}
		return $html_out;

	} // end function
} // end if not exists
add_shortcode( 'bbh_type_categories', 'bbh_listing_categories_type' );
