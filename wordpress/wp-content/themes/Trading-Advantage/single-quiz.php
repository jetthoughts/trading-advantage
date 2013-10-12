<?php get_header(); ?>
<?php the_post(); ?>
<div class="eightcol column">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
	<?php if($questions=ThemexCore::parseMeta($post->ID, 'quiz', 'questions')) { ?>
		<div class="quiz-listing">
			<div class="message">
			<?php ThemexCourse::renderMessages(); ?>
			</div>
			<form id="quiz_form" action="<?php the_permalink(); ?>" method="POST">				
				<?php foreach($questions as $question_key=>$question) { ?>
				<div class="quiz-question">
					<div class="question-title">
						<div class="question-number"><?php echo $question_key+1; ?></div>
						<h4 class="nomargin"><?php echo strip_tags(themex_stripslashes($question['question'])); ?></h4>
					</div>				
					<ul>
						<?php
						$question['answers']=themex_shuffle($question['answers']);
						foreach($question['answers'] as $answer_key=>$answer) {
							if(!empty($answer)) {
							?>
							<li class="<?php echo ThemexCourse::checkQuestion($question, 'answer_'.$question_key.'_'.$answer_key); ?>">
								<input type="checkbox" id="answer_<?php echo $question_key.'_'.$answer_key; ?>" name="answer_<?php echo $question_key.'_'.$answer_key; ?>" value="<?php echo $answer; ?>" <?php if(isset($_POST['answer_'.$question_key.'_'.$answer_key])) { ?>checked="checked"<?php } ?> />
								<label for="answer_<?php echo $question_key.'_'.$answer_key; ?>"><?php echo strip_tags(themex_stripslashes($answer)); ?></label>
							</li>
							<?php 
							}
						} 
						?>
					</ul>
				</div>
				<?php } ?>
				<input type="hidden" name="course_action" value="pass" />
				<?php if($lessons=ThemexCore::getRelatedItems($post->ID, 'lesson', 'quiz')) { ?>
				<input type="hidden" name="lesson_id" value="<?php echo $lessons[0]->ID; ?>" />
				<input type="hidden" name="course_id" value="<?php echo $lessons[0]->_lesson_course; ?>" />
				<?php } ?>
			</form>
		</div>
	<?php } ?>
</div>
<aside class="sidebar fourcol column last">
<?php 
if($lessons) {
	$post=$lessons[0];
	get_template_part('sidebar', 'lesson');
	wp_reset_query();
}
?>
</aside>
<?php get_footer(); ?>