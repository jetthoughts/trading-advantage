<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="content">
    <div class="container">

    <div class="course_content">

        <?php $pid = get_post(); // Getting current course ID ?>

        <?php print_r(wp_get_post_terms($post->ID,'course_category')); ?>

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
    <?php
        endwhile;
        endif;
    ?>

    </div>

<div class="course-content clearfix">
    <h1><?php _e('Lessons', 'academy'); ?></h1>
<!--    --><?php //if(ThemexCourse::isMember()) { ?>
<!--    --><?php //get_template_part('module', 'progress'); ?>
<!--    --><?php //} ?>

    <div class="lessons-listing">
        <?php
        $cat = wp_get_post_terms($pid->ID, 'course_category'); // Getting course categories
        $cat_arr = array(); // Here would be written course categories IDs
        $co = 0;
        foreach ($cat as $cati) {
            array_push($cat_arr,$cati->term_id);
        }

        print_r($cat_arr);
        wp_reset_postdata();
        wp_reset_query();

        $args = array( // Arguments for course lessons query
            'tax_query' => array(
                array(
                    'taxonomy' => 'course_category',
                    'terms' => $cat_arr
                )
            ),
            'post_type' => 'lesson',
            'posts_per_page' => -1
        );
        $lessons = get_posts($args); // Query course lessons

        // Output course lessons
        foreach ($lessons as $post) :
            setup_postdata($post);
            echo '<h2><a href="';
            echo the_permalink();
            echo '">';
            echo the_title();
            echo '</a></h2>';
        print_r($post->ID);
        endforeach;
        wp_reset_postdata();
        ?>
    </div>
	<div class="course-questions fivecol column last">
		<?php if($questions=ThemexCourse::getQuestions()) { ?>
		<h1><?php _e('Questions', 'academy'); ?></h1>
		<ul class="styled-list style-2 bordered">
			<?php foreach($questions as $question) {?>
			<li><a href="<?php echo get_comment_link($question->comment_ID); ?>"><?php echo get_comment_meta($question->comment_ID, 'title', true); ?></a></li>
			<?php } ?>
		</ul>
		<?php } ?>
	</div>
</div>
<!-- /course content -->
<!--<div class="popup hidden">-->
<!--	<h2 class="popup-text">-->
<!--	--><?php //if(ThemexCourse::isSubscriber()) { ?>
<!--	--><?php //_e('Take the course to view this content', 'academy').'.'; ?><!--.-->
<!--	--><?php //} else { ?>
<!--	--><?php //_e('Subscribe to view this content', 'academy').'.'; ?><!--.-->
<!--	--><?php //} ?>
<!--	</h2>-->
<!--</div>-->
<!-- /popup -->


<?php get_template_part('module', 'related'); ?>
<?php //print_r(ThemexCourse::$data); ?>
<?php get_footer(); ?>