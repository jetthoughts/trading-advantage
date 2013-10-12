<?php if(!ThemexCourse::isCompletedCourse(0) || is_single()){ ?>
<div class="course-progress">
	<span style="width:<?php echo ThemexCourse::$data['course']['progress']; ?>%;"></span>
</div>
<?php } ?>