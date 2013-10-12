<?php if($attachments=ThemexCore::parseMeta($post->ID, 'lesson', 'attachments')) { ?>
<div class="widget sidebar-widget">
	<div class="widget-title"><h4 class="nomargin"><?php _e('Attachments', 'academy'); ?></h4></div>
	<div class="widget-content">
		<ul class="styled-list style-4">
			<?php foreach($attachments as $attachment) { ?>
			<li class="<?php echo $attachment['type']; ?>"><a href="<?php echo $attachment['url']; ?>" target="_blank"><?php echo $attachment['title']; ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php } ?>