<?php
/*
Plugin name: embedas
*/

//embed gist
add_action( 'init', 'gist_register');

function gist_register() {
	wp_embed_register_handler( 'gist', '#https://gist.github.com/.+#i', 'gist_handler');
}

function gist_handler ( $url ) {
	return '<script src="' . $url[0] . '.js"></script>';
}

//embed jsfiddle
add_action( 'init', 'jsfiddle_register');

function jsfiddle_register() {
	wp_embed_register_handler( 'jsfiddle', '#https://jsfiddle.net/.+#i', 'jsfiddle_handler');
}

function jsfiddle_handler ( $url ) {
	return '<script async src="' . $url[0] . 'embed/"></script>';
}