				<!-- Footer -->
					<footer class="wrapper style1 align-center">
						<div class="inner">
<!-- 							<ul class="icons">
								<li><a href="#" class="icon style2 fa-twitter"><span class="label">Twitter</span></a></li>
								<li><a href="#" class="icon style2 fa-facebook"><span class="label">Facebook</span></a></li>
								<li><a href="#" class="icon style2 fa-instagram"><span class="label">Instagram</span></a></li>
								<li><a href="#" class="icon style2 fa-linkedin"><span class="label">LinkedIn</span></a></li>
								<li><a href="#" class="icon style2 fa-envelope"><span class="label">Email</span></a></li>
							</ul> -->
<?php
$args = array(
  'theme_location'  => 'social-menu',
  'container'       => null,
  'container_class' => 'my-menu-class',
  'menu_class' => 'icons'
);
wp_nav_menu( $args );
?>
							<p><?php echo get_theme_mod('story_theme_copyright'); ?></p>
						</div>
					</footer>

			</div>

			<script src="<?php echo get_template_directory_uri(); ?>/assets/js/demo.js"></script>
<?php wp_footer(); ?>
	</body>
</html>