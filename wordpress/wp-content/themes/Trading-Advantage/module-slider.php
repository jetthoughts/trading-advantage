<?php
//slider pause
$pause=ThemexCore::getOption('slider_pause',0);

//slider speed
$speed=ThemexCore::getOption('slider_speed',1000);

//slider effect
$effect=ThemexCore::getOption('slider_type','slide');

//query slides
$query=new WP_Query(array(
	'post_type' =>'slide',
	'showposts' => -1,
));

if($query->have_posts()) {
	if($effect=='fade') {
	?>
	<div class="row">
		<div class="boxed-slider themex-slider">			
			<ul>
				<?php 
				while($query->have_posts()) {
					$query->the_post();
					if($post->_slide_video!='') {
				?>
				<li>
					<div class="embedded-video">
					<?php echo themex_html($post->_slide_video); ?>
					</div>
				</li>				
				<?php } else if(has_post_thumbnail()) { ?>
				<li>
					<?php echo $post->_slide_link!=''?'<a href="'.$post->_slide_link.'">':''; ?>
					<?php the_post_thumbnail('full'); ?>
					<?php echo $post->_slide_link!=''?'</a>':''; ?>
					<div class="caption"><?php the_content(); ?></div>
				</li>		
				<?php	
					}
				}
				?>
			</ul>
			<?php if($query->post_count>1) { ?>
			<div class="arrow arrow-left"></div>
			<div class="arrow arrow-right"></div>
			<?php } ?>
			<input type="hidden" class="slider-pause" value="<?php echo $pause; ?>" />
			<input type="hidden" class="slider-speed" value="<?php echo $speed; ?>" />
			<input type="hidden" class="slider-effect" value="<?php echo $effect; ?>" />
		</div>	
	</div>
	<?php } else { ?>
	<div class="parallax-slider themex-slider">
		<?php if(ThemexCore::getOption('slider_parallax', 'false')!='true') { ?>
		<?php ThemexStyler::pageBackground(); ?>
		<?php } ?>		
		<ul>
			<?php while($query->have_posts()) { $query->the_post(); ?>
			<li><div class="row"><?php the_content(); ?></div></li>
			<?php } ?>
		</ul>
		<?php if($query->post_count>1) { ?>
		<div class="arrow arrow-left"></div>
		<div class="arrow arrow-right"></div>
		<?php } ?>
		<input type="hidden" class="slider-pause" value="<?php echo $pause; ?>" />
		<input type="hidden" class="slider-speed" value="<?php echo $speed; ?>" />
		<input type="hidden" class="slider-effect" value="<?php echo $effect; ?>" />
	</div>
	<?php } ?>
<?php } ?>
