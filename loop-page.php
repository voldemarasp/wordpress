<section>
	<header style="background-image: url(<?php echo get_template_directory_uri(); ?>/images/spotlight01.jpg); padding-top:50px; padding-bottom: 50px;">
		<h3 style="color:white;text-align: center;"><?php the_title(); ?></h3>
	</header>
	<div class="content">

		<dl>
			<dd style="margin-top: 50px;">
<?php the_content(); ?>
			</dd>
		</dl>

	</div>
</section>