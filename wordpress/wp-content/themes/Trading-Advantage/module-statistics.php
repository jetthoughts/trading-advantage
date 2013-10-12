<div id="themex-statistics" class="themex-statistics wrap">
	<div id="icon-edit" class="icon32"><br></div><h2><?php _e('Statistics', 'academy'); ?></h2>
	<div id="poststuff">		
		<div id="post-body" class="columns-2">
			<div id="post-body-content">
				<table class="widefat spaced">
					<?php if(ThemexCourse::$data['statistics']['user']['ID']) { ?>
					<thead>
						<tr>
							<th><?php _e('Lesson', 'academy'); ?></th>
							<th class="total_row"><?php _e('Course', 'academy'); ?></th>
							<th class="total_row"><?php _e('Mark', 'academy'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<?php foreach(ThemexCourse::$data['statistics']['lessons'] as $lesson) { ?>
						<tr>
							<td><?php echo get_the_title($lesson->ID); ?></td>
							<td class="total_row"><?php echo get_the_title($lesson->_lesson_course); ?></td>
							<td class="total_row"><?php echo ThemexCourse::getLessonMark($lesson->ID, ThemexCourse::$data['statistics']['user']['ID']); ?>&#37;</td>
						</tr>
						<?php } ?>
						<tr>
							<th><?php _e('Lesson', 'academy'); ?></th>
							<th class="total_row"><?php _e('Course', 'academy'); ?></th>
							<th class="total_row"><?php _e('Mark', 'academy'); ?></th>
						</tr>
					</tfoot>
					<?php } else { ?>
					<thead>
						<tr>
							<th><?php _e('Username', 'academy'); ?></th>
							<th class="total_row"><?php _e('Date Registered', 'academy'); ?></th>
							<th class="total_row"><?php _e('Active Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Completed Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Average Mark', 'academy'); ?></th>
						</tr>
					</thead>
					<tfoot>
						<?php foreach(ThemexCourse::$data['statistics']['users'] as $user) { ?>
						<tr>
							<td><?php echo $user->user_login; ?></td>
							<td class="total_row"><?php echo $user->user_registered; ?></td>
							<td class="total_row"><?php echo $user->user_active_courses; ?></td>
							<td class="total_row"><?php echo $user->user_completed_courses; ?></td>
							<td class="total_row"><?php echo $user->user_mark; ?>&#37;</td>
						</tr>
						<?php } ?>
						<tr>
							<th><?php _e('Username', 'academy'); ?></th>
							<th class="total_row"><?php _e('Date Registered', 'academy'); ?></th>
							<th class="total_row"><?php _e('Active Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Completed Courses', 'academy'); ?></th>
							<th class="total_row"><?php _e('Average Mark', 'academy'); ?></th>
						</tr>
					</tfoot>
					<?php } ?>					
				</table>
			</div>
			<div id="postbox-container-1" class="postbox-container">
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Filter','academy'); ?></h3>
					<div class="inside noborder">
						<form action="" method="GET">
							<p>
							<?php
							wp_dropdown_users(array(
								'class'=>'widefat submit-select',
								'show_option_all'=>__('All Students', 'academy'),
								'selected' => ThemexCourse::$data['statistics']['user']['ID'],
							)); 
							?>
							</p>
							<input type="hidden" name="post_type" value="course" />
							<input type="hidden" name="page" value="statistics" />
						</form>
					</div>
				</div>
				<div id="postimagediv" class="postbox">
					<h3 class="normal"><?php _e('Courses','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Total', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['total']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Completed', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['completed']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Per User', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['course']['average']; ?></span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				<div id="postimagediv" class="postbox ">
					<h3 class="normal"><?php _e('Students','academy'); ?></h3>
					<div class="inside noborder">
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Total', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['total']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Active', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['active']; ?></span>
							<div class="clear"></div>
						</div>
						<div class="misc-pub-section">
							<strong class="alignleft"><?php _e('Mark', 'academy'); ?></strong>
							<span class="alignright"><?php echo ThemexCourse::$data['statistics']['user']['mark']; ?>&#37;</span>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>		
	</div>	
</div>