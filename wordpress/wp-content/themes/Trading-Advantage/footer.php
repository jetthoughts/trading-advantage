				</div>
			</div>
			<!-- /content -->
			<div class="footer-wrap">
				<footer class="site-footer">
					<div class="row">
						<div class="copyright left"><?php ThemexStyler::siteCopyright(); ?></div>
						<nav class="footer-navigation right">
							<?php wp_nav_menu( array( 'theme_location' => 'footer_menu' ) ); ?>
						</nav>
						<!-- /navigation -->				
					</div>			
				</footer>				
			</div>
			<!-- /footer -->			
		</div>
		<!-- /site wrap -->
	<?php wp_footer(); ?>
	</body>
</html>