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
    <!-- Charts -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/flot/excanvas.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/sparkline/jquery.sparkline.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/flot/jquery.flot.resize.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>plugins/flot/jquery.flot.growraf.min.js"></script>
    <script type="text/javascript"
            src="<?php echo THEME_URI; ?>plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

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
        $(document).ready(function () {
            "use strict";

            App.init(); // Init layout and core plugins
            Plugins.init(); // Init all plugins
            FormComponents.init(); // Init all form-specific plugins
        });
    </script>

    <!-- Demo JS -->
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/demo/pages_calendar.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/demo/charts/chart_filled_blue.js"></script>
    <script type="text/javascript" src="<?php echo THEME_URI; ?>assets/js/demo/charts/chart_simple.js"></script>

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
                <ul class="dropdown-menu">
                    <li><a href="Market-Profile-basics.html"><i class="icon-book"></i>
                            Market Profile Basics
                        </a></li>
                    <li class="divider"></li>

                    <li><a href="#"><i class="icon-book"></i>
                            Secrets of an Electronic Futures Trader
                        </a></li>
                    <li class="divider"></li>

                    <li><a href="#"><i class="icon-book"></i>
                            HVA Masters Manual Commodiities
                        </a></li>
                    <li class="divider"></li>

                    <li><a href="#"><i class="icon-book"></i>
                            Options Scholar
                        </a></li>
                    <li class="divider"></li>

                    <li><a href="#"><i class="icon-book"></i>
                            Beyond Buy And Hold
                        </a></li>
                    <li class="divider"></li>

                    <li><a href="#"><i class="icon-book"></i>
                            Currencies Fundamentals
                        </a></li>


                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    Live Trading Rooms
                    <i class="icon-caret-down small"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="icon-desktop"></i> Stocks </a></li>
                    <li class="divider"></li>

                    <li><a href="#"><i class="icon-desktop"></i> Forex</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-desktop"></i> Options</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-desktop"></i> Commodities</a></li>
                    <li class="divider"></li>
                    <li><a href="#"><i class="icon-desktop"></i> Day Trading</a></li>

                </ul>
            </li>

        </ul>
        <!-- /Top Left Menu -->

        <!-- Top Right Menu -->
        <ul class="nav navbar-nav navbar-right">

            <?php if (ThemexUser::userActive()) { ?>
                <?php $current_user = ThemexUser::$data['user']; ?>

                <!-- Messages -->
                <li class="dropdown hidden-xs hidden-sm">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-envelope"></i>
                        <span class="badge">3</span>
                    </a>
                    <ul class="dropdown-menu extended notification">
                        <li class="title">
                            <p>You have 3 new messages</p>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="photo"><img src="<?php echo THEME_URI; ?>assets/img/demo/avatar-1.jpg" alt=""/></span>
								<span class="subject">
									<span class="from">Dan O'Brien</span>
									<span class="time">Just Now</span>
								</span>
								<span class="text">
									Check out my new class about...
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="photo"><img src="<?php echo THEME_URI; ?>assets/img/demo/avatar-2.jpg" alt=""/></span>
								<span class="subject">
									<span class="from">Tino Boccarsi</span>
									<span class="time">45 mins</span>
								</span>
								<span class="text">
									My webinar about stocks is next week...
								</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="photo"><img src="<?php echo THEME_URI; ?>assets/img/demo/avatar-3.jpg" alt=""/></span>
								<span class="subject">
									<span class="from">Scott Bauer</span>
									<span class="time">6 hours</span>
								</span>
								<span class="text">
									Just posted my thoughts about today's market...
								</span>
                            </a>
                        </li>
                        <li class="footer">
                            <a href="javascript:void(0);">View all messages</a>
                        </li>
                    </ul>
                </li>

                <!-- User Login Dropdown -->

                <li class="dropdown user">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo ThemexUser::getAvatar($avatar,$current_user,20,$default,'a') ?>
                        <span class="username"><?php echo ThemexUser::getFullName($current_user) ?></span>
                        <i class="icon-caret-down small"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo ThemexUser::$data['profile_page_url']; ?>" >
                                <i class="icon-user"></i><?php _e('My Profile', 'academy'); ?>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo wp_logout_url(SITE_URL); ?>" >
                                <i class="icon-key"></i><?php _e('Sign Out', 'academy'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php } else { ?>
                <li >
                    <a href="/register" >
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

