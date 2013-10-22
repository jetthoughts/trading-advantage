<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo THEME_URI; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <!-- jQuery UI -->
    <!--<link href="<?php echo THEME_URI; ?>plugins/jquery-ui/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />-->
    <!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="<?php echo THEME_URI; ?>plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/>
    <![endif]-->

    <!-- Theme -->
    <link href="<?php echo THEME_URI; ?>assets/css/main.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo THEME_URI; ?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo THEME_URI; ?>assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo THEME_URI; ?>assets/css/icons.css" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="<?php echo THEME_URI; ?>assets/css/fontawesome/font-awesome.min.css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="<?php echo THEME_URI; ?>assets/css/fontawesome/font-awesome-ie7.min.css">
    <![endif]-->

    <!--[if IE 8]>
    <link href="<?php echo THEME_URI; ?>assets/css/ie8.css" rel="stylesheet" type="text/css"/>
    <![endif]-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

    <!--=== JavaScript ===-->

    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/libs/jquery-1.10.2.min.js"></script>
    <script type="text/javascript"
            src="<?php echo THEME_URI; ?>plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

    <script type="text/javascript" src="<?php echo THEME_URI; ?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/libs/lodash.compat.min.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="<?php echo THEME_URI; ?>assets/js/libs/html5shiv.js"></script>
    <![endif]-->

    <!-- Smartphone Touch Events -->
    <script type="text/javascript"
            src="<?php echo THEME_URI; ?>plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/event.swipe/jquery.event.move.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/event.swipe/jquery.event.swipe.js"></script>

    <!-- General -->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/libs/breakpoints.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/respond/respond.min.js"></script>
    <!-- Polyfill for min/max-width CSS3 Media Queries (only for IE8) -->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/cookie/jquery.cookie.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script type="text/javascript"
            src="<?php echo THEME_URI; ?>plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

    <!-- Page specific plugins -->

    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/blockui/jquery.blockUI.min.js"></script>

    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/fullcalendar/fullcalendar.min.js"></script>

    <!-- Noty -->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/noty/layouts/top.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/noty/themes/default.js"></script>

    <!-- Forms -->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/select2/select2.min.js"></script>

    <!-- App -->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/app.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/plugins.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/plugins.form-components.js"></script>

    <script>
        jQuery(document).ready(function () {
            "use strict";

            App.init(); // Init layout and core plugins
            Plugins.init(); // Init all plugins
            FormComponents.init(); // Init all form-specific plugins
        });
    </script>


    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>js/html5.js"></script>
    <![endif]-->

    <?php wp_head(); ?>
</head>
<body <?php body_class('theme-dark'); ?>>

<header class="header navbar navbar-fixed-top" role="banner">
    <div class="container">

        <!-- Only visible on smartphones, menu toggle -->
        <ul class="nav navbar-nav">
            <li class="nav-toggle"><a href="javascript:void(0);" title=""><i class="icon-reorder"></i></a></li>
        </ul>

        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="<?php echo THEME_URI; ?>assets/img/logo.png" alt="logo"/>

        </a>
        <!-- /logo -->

        <!-- Sidebar Toggler -->
        <a href="#" class="toggle-sidebar bs-tooltip" data-placement="bottom" data-original-title="Toggle navigation">
            <i class="icon-reorder"></i>
        </a>
        <!-- /Sidebar Toggler -->

        <!-- Top Left Menu -->
        <ul class="nav navbar-nav navbar-left">
            <li>
                <a href="#">
                    Dashboard
                </a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    Trading Education Programs
                    <i class="icon-caret-down small"></i>
                </a>
                <ul class="dropdown-menu" id="course_menu">
                    <?php $args = array('taxonomy' => 'course_category');

                    $terms = get_terms('course_category', $args);

                    $count = count($terms);
                    if ($count > 0) {
                        foreach ($terms as $term) {
                            $term_list .= '<li><a href="' . get_term_link($term) . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '"><i class="icon-book"></i> ' . $term->name . '</a></li><li class="divider"></li>';
                        }
                        echo $term_list;
                    } ?>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    Live Trading Rooms
                    <i class="icon-caret-down small"></i>
                </a>
                <ul class="dropdown-menu">

                    <?php
                    $lt_args = array(   // Query arguments for live trading rooms query
                        'category_slug' => 'live'
                    );
                    $live = get_posts($lt_args);
                    $l_count = 0;
                    foreach ($live as $ltr) {
                        $ltr_id = $ltr->ID;
                        if ($l_count == 0) {
                            $ltr_str .= '<li><a href="' . post_permalink($ltr_id) . '"><i class="icon-desktop"></i>' . get_the_title($ltr_id) . '</a></li>';
                        } else {
                            $ltr_str .= '<li class="divider"></li><li><a href="' . post_permalink($ltr_id) . '"><i class="icon-desktop"></i>' . get_the_title($ltr_id) . '</a></li>';
                        }
                        $l_count++;
                    }
                    echo $ltr_str;
                    ?>

                </ul>
            </li>

        </ul>
        <!-- /Top Left Menu -->

        <!-- Top Right Menu -->
        <ul class="nav navbar-nav navbar-right">

            <?php if (ThemexUser::userActive()) { ?>
                <?php
                    $user_data = ThemexUser::$data['user'];
                    $current_user = $user_data->ID;
                ?>

                <!-- User Login Dropdown -->

                <li class="dropdown user">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo ThemexUser::getAvatar($avatar, $current_user, 20, $default, 'a') ?>

                        <span class="username"><?php echo ThemexUser::getFullName($current_user) ?></span>
                        <i class="icon-caret-down small"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo ThemexUser::$data['profile_page_url']; ?>">
                                <i class="icon-user"></i><?php _e('My Profile', 'academy'); ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo wp_logout_url(SITE_URL); ?>">
                                <i class="icon-key"></i><?php _e('Sign Out', 'academy'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } else { ?>
                <li>
                    <a href="/register">
                        <i class="icon-key"></i>&nbsp;&nbsp;<?php _e('Sign In', 'academy'); ?>
                    </a>
                </li>
            <?php } ?>
            <!-- /user login dropdown -->
        </ul>
        <!-- /Top Right Menu -->
    </div>
</header>
<!-- /header -->

<!-- id=container -->
<div id="container">

