<?php get_header(); ?>

				<!-- Banner -->
<?php

$count = 0;

if ( have_posts() ) : while ( have_posts() ) : the_post();

if ( 0 === $count ) {
	get_template_part('loop', 'main');
} elseif ( 0 === $count % 2 ) {
	get_template_part('loop', 'three');
} else {
	get_template_part('loop', 'two');
}
++$count;

endwhile; endif; 

get_sidebar();

get_footer(); 
