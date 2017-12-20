<section class="spotlight style1 orient-right content-align-left image-position-center onscroll-image-fade-in" id="first">
	<div class="content">
		<h1><?php the_title(); ?></h1>
		<p><?php the_excerpt(); ?></p>
		<ul class="actions vertical">
			<li><a href="#" class="button"><?php _e('Get Started', 'html5up-story') ?></a></li>
		</ul>
	</div>
	<div class="image">
		<?php the_post_thumbnail(); ?>
	</div>
</section>