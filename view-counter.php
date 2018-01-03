<?php
/*
Plugin name: View counter
*/

add_action( 'wp', 'count_views' );
function count_views() {
	if ( is_single() ) {
		$post_id = get_the_ID();
		$list = get_post_meta( $post_id, '_count_views', true );

		if (empty($list)) {
			update_post_meta($post_id, '_count_views', 1);
		} else {
			$list = $list + 1;
			update_post_meta($post_id, '_count_views', $list);
		}

		
	}
}


add_filter( 'the_content', 'show_count_views', 5 );

function show_count_views( $content ) {
	if ( is_single() ) {
		$post_id = get_the_ID();
		$list = get_post_meta( $post_id, '_count_views', true );
		
		$return = $content;
		$return .= '<div class="ca_related_container">';
		$return .= '<h2>Page views</h2>';
		$return .= $list;
		$return .= '</div>';

		return $return;

	} else {
		return $content;
	}
}

add_action( 'rest_api_init', 'api_field', 10, 1 );

function api_field() {
	register_rest_field(
		'post',
		'pageviews',
		array(
			'get_callback' => 'count_view_get',
		)
	);
}

function count_view_get ($post) {
	$post_id = $post['id'];
	$count =  get_post_meta( $post_id, '_count_views', true );
	return (int) $count;
}