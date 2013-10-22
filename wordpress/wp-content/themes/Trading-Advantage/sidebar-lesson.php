<div id="sidebar" class="sidebar-fixed">
    <div id="sidebar-content">
        <!-- Search Input -->
        <form class="sidebar-search">
            <div class="input-box">
                <button type="submit" class="submit">
                    <i class="icon-search"></i>
                </button>
						<span>
                <?php get_search_form(); ?>
						</span>
            </div>
        </form>


        <!--=== Navigation ===-->
        <ul id="nav">
            <?php
            $pid = get_post();
            $cat = wp_get_post_terms($pid->ID, 'course_category'); // Getting course categories
            $cat_arr = array(); // Here would be written course categories IDs
            $co = 0;
            foreach ($cat as $cati) {
                array_push($cat_arr,$cati->term_id);
            }

//            print_r($cat_arr);
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
            $lessons_num = count($lessons);
            wp_reset_postdata();

            $course_args = array( // Arguments for course lessons query
                'tax_query' => array(
                    array(
                        'taxonomy' => 'course_category',
                        'terms' => $cat_arr
                    )
                ),
                'post_type' => 'course',
                'posts_per_page' => 1
            );


            $course = get_posts($course_args);
//            print_r(count($lessons));
            // Output course lessons
            ?>
            <li class="current">
                <a href="javascript:void(0);">
                    <?php echo $course[0]->post_title; ?>
                    <span class="label label-info pull-right"><?php echo $lessons_num; ?></span>
                </a>
                <ul class="sub-menu">
                <?php
                foreach ($lessons as $post) :
                    setup_postdata($post);
                    echo '<li><a href="';
                    echo the_permalink();
                    echo '"><i class="icon-angle-right"></i>';
                    echo the_title();
                    echo '</a></li>';
                endforeach;

                ?>
                </ul>
            </li>
            <li>
                <a href="javascript:void(0);">
                    <i class="icon-bookmark"></i>
                    My Bookmarks
                    <span class="label label-info pull-right">3</span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="ui_general.html">
                            <i class="icon-angle-right"></i>
                            High Volume Area
                        </a>
                    </li>
                    <li>
                        <a href="ui_buttons.html">
                            <i class="icon-angle-right"></i>
                            The Punchline Trade
                        </a>
                    </li>
                    <li>
                        <a href="ui_grid.html">
                            <i class="icon-angle-right"></i>
                            Harmonics
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

        <!-- /Navigation -->


        <!--        <div class="sidebar-widget align-center">-->
        <!--            --><?php //ThemexWidgetiser::renderData(); ?>
        <!--        </div>-->

    </div>
    <div id="divider" class="resizeable"></div>
</div>