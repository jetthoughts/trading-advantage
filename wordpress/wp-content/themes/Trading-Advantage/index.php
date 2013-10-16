<?php
get_header();

$date_format = get_option('date_format'); ?>

<?php get_sidebar(); ?>

<div id="content">
<div class="container">
    <div class="featured-content">
        <?php
        ThemexStyler::pageBackground();
        if (is_front_page() && !ThemexUser::isLoginPage()) {
            get_template_part('module', 'slider');
        } else {
            ?>
            <div class="row">
                <?php
                if (get_post_type() == 'course' && is_single()) {
                    get_template_part('module', 'course');
                } else {
                    ?>
                    <div class="page-title">
                        <h1 class="nomargin"><?php ThemexStyler::pageTitle(); ?></h1>
                    </div>
                    <!-- /page title -->
                <?php } ?>
            </div>
        <?php } ?>
    </div>
<!-- Breadcrumbs line -->
<div class="crumbs">
    <ul id="breadcrumbs" class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">Dashboard</a>
        </li>


    </ul>


</div>
<!-- /Breadcrumbs line -->

<!--=== Page Header ===-->
<div class="page-header">
    <div class="page-title">
        <h3>Hello Larry!</h3>
        <span>Welcome Back.</span>
    </div>

    <!-- Page Stats -->
    <ul class="page-stats">
        <li>

        </li>
        <li>
            <div class="summary">
                <span>Your Course</span>

                <h3>Stock Program</h3>
            </div>

        </li>
    </ul>
    <!-- /Page Stats -->
</div>
<!-- /Page Header -->

<!--=== Page Content ===-->
<!--=== Statboxes ===-->
<div class="row row-bg"> <!-- .row-bg -->


    <div class="col-sm-12 col-md-6 hidden-xs">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual green">
                    <i class="icon-calendar"></i>
                </div>
                <h2>Special Webinar Event - Oct 20th</h2>

                <p>Your invited to a special webinar event. Learn about something on a date that is coming up soon
                   and you will become the best trader in world! Larry is going to walk you through his framing
                   signal and other signals that helped him make $1.9 million. This is a one time event and space is
                   limited so please reserve your seat soon. </p>
                <button class="btn btn-success">Register Now!</button>
                <a class="more" href="javascript:void(0);">Read More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div>
        <!-- /.smallstat -->
    </div>
                         <!-- /.col-md-3 -->

    <div class="col-sm-12 col-md-6 hidden-xs">
        <div class="statbox widget box box-shadow">
            <div class="widget-content">
                <div class="visual green">
                    <i class="icon-dollar"></i>
                </div>
                <h2>Upgrade to a Lifetime Membership</h2>

                <p>YNow through the end of the month we are offering a special 10% discount to existing students who
                   upgrade to a lifetime membership. When you become a lifetime member of Trading Advantage you get
                   access to all the live trading rooms and education programs for the rest of your life!</p>
                <button class="btn btn-success">Upgrade Now!</button>
                <a class="more" href="javascript:void(0);">Read More <i class="pull-right icon-angle-right"></i></a>
            </div>
        </div>
        <!-- /.smallstat -->
    </div>
                         <!-- /.col-md-3 -->
</div>
<!-- /.row -->
<!-- /Statboxes -->


<div class="row">
<!--=== Calendar ===-->
<div class="col-md-6">
    <div class="widget">
        <div class="widget-header">
            <h4><i class="icon-calendar"></i> Calendar</h4>
        </div>
        <div class="widget-content">
            <div id="calendar"></div>
        </div>
    </div>
    <!-- /.widget box -->
</div>
<!-- /.col-md-6 -->
<!-- /Calendar -->

<!--=== Feeds (with Tabs) ===-->
<div class="col-md-6">
<div class="widget">
<div class="widget-header">
    <h4><i class="icon-reorder"></i> News Feeds</h4>

    <div class="toolbar no-padding">
        <div class="btn-group">
            <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
            <span class="btn btn-xs widget-refresh"><i class="icon-refresh"></i></span>
        </div>
    </div>
</div>
<div class="widget-content">
<div class="tabbable tabbable-custom">
<ul class="nav nav-tabs">
    <li class="active"><a href="#tab_feed_1" data-toggle="tab">Stocks</a></li>
    <li><a href="#tab_feed_2" data-toggle="tab">Options</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="tab_feed_1">
    <div class="scroller" data-height="290px" data-always-visible="1" data-rail-visible="0">
        <ul class="feeds clearfix">
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">1 min ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">5 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">10 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">20 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">30 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">40 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">50 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">1 hour ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">1.5 hours ago</div>
                </div>
                <!-- /.col2 -->
            </li>


        </ul>
        <!-- /.feeds -->
    </div>
    <!-- /.scroller -->
</div>
<!-- /#tab_feed_1 -->

<div class="tab-pane" id="tab_feed_2">
    <div class="scroller" data-height="290px" data-always-visible="1" data-rail-visible="0">
        <ul class="feeds clearfix">
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">1 min ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">5 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">10 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">20 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">30 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">40 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">50 mins ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story pulled from feeed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">1 hour ago</div>
                </div>
                <!-- /.col2 -->
            </li>
            <li>
                <div class="col1">
                    <div class="content">
                        <div class="content-col1">
                            <div class="label label-success"><i class="icon-plus"></i></div>
                        </div>
                        <div class="content-col2">
                            <div class="desc">News story from feed.</div>
                        </div>
                    </div>
                </div>
                <!-- /.col1 -->
                <div class="col2">
                    <div class="date">1.5 hours ago</div>
                </div>
                <!-- /.col2 -->
            </li>
        </ul>
        <!-- /.feeds -->
    </div>
    <!-- /.scroller -->
</div>
<!-- /#tab_feed_1 -->
</div>
<!-- /.tab-content -->
</div>
<!-- /.tabbable tabbable-custom-->
</div>
<!-- /.widget-content -->
</div>
<!-- /.widget .box -->
</div>
<!-- /.col-md-6 -->
<!-- /Feeds (with Tabs) -->
</div>
<!-- /.row -->
<!-- /Page Content -->
</div>
<!-- /.container -->


<div class="fullwidth-block">

    <?php echo category_description(); ?>
    <div class="posts-listing">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                get_template_part('loop', 'post');
            }
        } else {
            ?>
            <h3><?php _e('No posts found. Try a different search?', 'academy'); ?></h3>
            <p><?php _e('Sorry, no posts matched your search. Try again with some different keywords.', 'academy'); ?></p>
        <?php } ?>
    </div>
    <?php ThemexInterface::renderPagination(); ?>
</div>


<?php get_footer(); ?>