<?php
/*
Plugin name: sausainiai
*/

add_action( 'wp_enqueue_scripts', 'cookies_enqueue_styles' );
function cookies_enqueue_styles() {
    wp_enqueue_style( 'cookies-style', plugin_dir_url( __FILE__ ) . 'cookies.css' );
    wp_enqueue_style( 'fa-style', plugin_dir_url( __FILE__ ) . 'fa/css/fontawesome-all.css' );
    // wp_deregister_script('jquery');
	// wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
    wp_enqueue_script( 'manojava', plugin_dir_url( __FILE__ ) .'/java.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'manojcookie', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js', array( 'jquery' ), null, true );
}

function sausainiai() {
if (!isset($_COOKIE['cookies'])) {
?>
<div class="sausainiai">
	<div class="wrapas-sausainiu">
	<div class="tekstas">Informuojame, kad šioje svetainėje naudojami slapukai (angl. cookies). Sutikdami, paspauskite mygtuką „Sutinku“ arba naršykite toliau. Savo duotą sutikimą bet kada galėsite atšaukti pakeisdami savo interneto naršyklės nustatymus ir ištrindami įrašytus slapukus.
	</div>
	<div class="iksas">
		<i class="fa fa-times" aria-hidden="true"></i>
	</div>
</div>
</div>
<?php
}
}
add_action( 'wp_footer', 'sausainiai' );