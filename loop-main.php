<section class="banner style1 orient-left content-align-left image-position-right fullscreen onload-image-fade-in onload-content-fade-right">
	<div class="content">
		<h1 style="color: <?php echo get_theme_mod('story_theme_color'); ?>;"><?php the_title(); ?></h1>
		<p><?php echo get_post_meta( get_the_id(), 'kaina', true ); ?></p>
		<p class="major"><?php the_excerpt(); ?></p>
		<ul class="actions vertical">
			<li><a href="<?php the_permalink(); ?>" class="button big wide smooth-scroll-middle"><?php _e('Get Started', 'html5up-story') ?></a></li>
		</ul>
	</div>
	<div class="image">
		<?php the_post_thumbnail(); ?>
	</div>
</section>