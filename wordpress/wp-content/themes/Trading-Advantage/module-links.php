<div class="user-links">
<?php if(ThemexUser::$data['current_user']->twitter) { ?><a href="<?php echo ThemexUser::$data['current_user']->twitter; ?>" class="twitter" target="_blank" title="Twitter"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->facebook) { ?><a href="<?php echo ThemexUser::$data['current_user']->facebook; ?>" class="facebook" target="_blank" title="Facebook"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->google) { ?><a href="<?php echo ThemexUser::$data['current_user']->google; ?>" class="google" target="_blank" title="Google"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->tumblr) { ?><a href="<?php echo ThemexUser::$data['current_user']->tumblr; ?>" class="tumblr" target="_blank" title="Tumblr"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->linkedin) { ?><a href="<?php echo ThemexUser::$data['current_user']->linkedin; ?>" class="linkedin" target="_blank" title="LinkedIn"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->vimeo) { ?><a href="<?php echo ThemexUser::$data['current_user']->vimeo; ?>" class="vimeo" target="_blank" title="Vimeo"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->flickr) { ?><a href="<?php echo ThemexUser::$data['current_user']->flickr; ?>" class="flickr" target="_blank" title="Flickr"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->youtube) { ?><a href="<?php echo ThemexUser::$data['current_user']->youtube; ?>" class="youtube" target="_blank" title="YouTube"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->skype) { ?><a href="<?php echo ThemexUser::$data['current_user']->skype; ?>" class="skype" target="_blank" title="Skype"></a><?php } ?>
<?php if(ThemexUser::$data['current_user']->rss) { ?><a href="<?php echo ThemexUser::$data['current_user']->rss; ?>" class="rss" target="_blank" title="RSS"></a><?php } ?>
</div>