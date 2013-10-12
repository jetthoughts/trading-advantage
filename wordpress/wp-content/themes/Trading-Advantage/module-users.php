<?php if(ThemexCourse::hasMembers()) { ?>
<div class="widget">
	<div class="widget-title">
		<h4 class="nomargin left"><?php _e('Students', 'academy'); ?></h4>
	</div>
	<div class="widget-content clearfix">
		<div class="users-listing clearfix">
			<?php
			$counter=0;
			foreach(ThemexCourse::getRandomUsers() as $ID) {
				$user=get_userdata($ID);
				$counter++;
				?>
				<div class="user-image <?php echo $counter==3?'last':''; ?>">
					<div class="bordered-image">
						<a title="<?php echo ThemexUser::getFullName($user); ?>" href="<?php echo get_author_posts_url($ID); ?>"><?php echo get_avatar( $ID ); ?></a>
					</div>
				</div>
				<?php if($counter==3) { ?>
				<div class="clear"></div>
				<?php
				$counter=0;
				}
			}
			?>
		</div>			
	</div>
</div>
<?php } ?>