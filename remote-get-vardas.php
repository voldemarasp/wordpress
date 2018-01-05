<?php
/*
Plugin name: get username by shortcode
*/


add_shortcode( 'vardas_is_profilio', 'vardas_is_profilio_funkcija' );

function vardas_is_profilio_funkcija( $args, $content ) {
	
	$current_user = wp_get_current_user();

    $first_name = $current_user->user_firstname;

    $last_name = $current_user->user_lastname;

    $url = 'https://api.aru.lt/json/names/v1/sauksmininkas/' . $first_name . ' ' . $last_name;

	$response = wp_remote_get( $url );

	$obj = json_decode( $response['body'] );

    return 'Labas, ' . $obj;
}