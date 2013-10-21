<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="content">
    <div class="container">
<?php echo category_description(); ?>
    <div class="courses-listing clearfix">
        <div class="row">
            <?php
            if (have_posts()) {

                $counter = 0;
                while (have_posts()) {
                    the_post();
                    $counter++;
                    ?>
                    <div class="col-md-3">
                        <?php get_template_part('loop', 'course'); ?>
                    </div>
                    <?php if ($counter == 4) { ?>
                        <div class="clear"></div>
                        <?php
                        $counter = 0;
                    } ?>
                <?php
                }
            } else {
                _e('There is no lessons at this course were added', 'academy');
            }
            ?>
        </div>
    </div>
<?php ThemexInterface::renderPagination(); ?>
<?php get_footer(); ?>