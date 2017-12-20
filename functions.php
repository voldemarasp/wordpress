<?php

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );


function wpdocs_theme_name_scripts() {
    wp_enqueue_style( 'style-name', get_template_directory_uri() . '/assets/css/main.css' );
    wp_enqueue_style( 'style-mano', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'scrollex', get_template_directory_uri().'/assets/js/jquery.scrollex.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'scrolly', get_template_directory_uri().'/assets/js/jquery.scrolly.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'skel', get_template_directory_uri().'/assets/js/skel.min.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'util', get_template_directory_uri().'/assets/js/util.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'mainjs', get_template_directory_uri().'/assets/js/main.js', array( 'jquery' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );



//Taip reiketu daryt
//------------------------
// add_action( 'wp_enqueue_scripts', 'arunas_scripts' );
// function arunas_scripts() {
//   wp_register_style( 
//     'theme-style', 
//     get_template_directory_uri(). '/style.css' ) 
//   );
//   wp_enqueue_style( 'theme-style' );

//   wp_register_style( 
//     'theme-script',
//     get_template_directory_uri(). '/script.js' ) 
//     array( 'jquery' ),
//     '0.1.0',
//     true
//  );
//  wp_enqueue_style( 'theme-script' );
// }


// functions.php
// add_image_size( $name, $width, $height, $crop );
add_image_size( 'pic', 434, 434, true );
add_image_size( 'spot', 1440, 900, true );

//shortcode
add_shortcode( 'vardas', 'vardas_shortcode' );

function vardas_shortcode( $args, $content ) {
  return 'Petras';
}


add_shortcode( 'kitas', 'kitas_shortcode' );

function kitas_shortcode( $args, $content ) {
  $defaults = array(
    'title'       => __( 'Some Title', 'temos-katalogas' ),
    'description' => '',
  );
  $args = wp_parse_args( $args, $defaults );
  $result = "<strong>{$args['title']}</strong>";
  if ( $args['description'] ) {
    $result .= "- <em>{$args['description']}</em>";
  }
  if ( $content ) {
    $result .= '<br />' . do_shortcode( $content );
  }
  return $result;
}



//MENU SOCIAL

register_nav_menu( 'social-menu', __('Social Menu', 'html5up-story') );

add_filter( 'nav_menu_link_attributes', 'arunas_menu', 10, 2 );

function arunas_menu ( $attributes, $item ) {

$attributes['class'] = 'icon style2 ' . $item->attr_title;
return $attributes;

}

add_filter( 'nav_menu_item_title', 'arunas_title' );

function arunas_title ( $title ) {
	$title = '<span class="label">' . $title . '</span>';
	return $title;
}


//SIDEBAR

$args = array(
  'name' => 'Main Sidebar',
  'id' => 'sidebar-1',
  'description' => 'Main widget area',
  'before_widget' => '<article>',
  'after_widget'  => '</article>',
  'before_title'  => '<h3>',
  'after_title'   => '</h3>'
);
register_sidebar( $args );


//CUSTOM WIDGET

function arunas_load_widget() {
	include_once( 'includes/arunas_widget.php' );
    register_widget( 'Arunas_Widget' );
}
add_action( 'widgets_init', 'arunas_load_widget' );


//CUSTOMISER

add_action( 'customize_register', 'arunas_customize_register' );
function arunas_customize_register( $wp_customize ) {

  $wp_customize->add_section( 
  'story_theme', 
  array(
    'title' => __( 'Theme options' ),
    'description' => __( 'Edit your theme' ),
    'panel' => '', // Not typically needed.
    'priority' => 160,
    'capability' => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
  ) 
);


$wp_customize->add_setting( 
  'story_theme_copyright', 
  array(
    'type' => 'theme_mod', // or 'option'
    'capability' => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
    'default' => 'Default Copyright',
    'transport' => 'refresh', // or postMessage
    'sanitize_callback' => '',
    'sanitize_js_callback' => '', // Basically to_json.
  )
);

$wp_customize->add_setting( 
  'story_theme_color', 
  array(
    'type' => 'theme_mod', // or 'option'
    'capability' => 'edit_theme_options',
    'theme_supports' => '', // Rarely needed.
    'default' => 'Default Copyright',
    'transport' => 'refresh', // or postMessage
    'sanitize_callback' => '',
    'sanitize_js_callback' => '', // Basically to_json.
  )
);


$wp_customize->add_control( 
  'story_theme_copyright', 
  array(
    'type' => 'text',
    'priority' => 10, // Within the section.
    'section' => 'story_theme', // Required, core or custom.
    'label' => __( 'Copyright' ),
    'description' => __( 'Change copyright' ),
    'input_attrs' => array(
      'class' => 'my-custom-class-for-js',
      'style' => 'border: 1px solid #900',
      'placeholder' => __( 'All rights reserved.' ),
    ),
    'active_callback' => 'is_front_page',
  )
);

$wp_customize->selective_refresh->add_partial( 
  'story_theme_copyright', array(
    'selector' => 'footer p',
    'container_inclusive' => false,
    'render_callback' => 'render_copyright',
  )
);

function render_copyright() {
	return get_theme_mod( 'story_theme_copyright', false );
}

$wp_customize->add_control( 
  new WP_Customize_Color_Control( 
    $wp_customize, 
    'story_theme_color', 
     array(
      'label'   => __( 'Accent Color', 'story_theme' ),
      'section' => 'story_theme',
    ) 
  ) 
);

}