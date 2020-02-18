<?php
/** 
 * Admin header template
 * 
 * @package SJM
 * @author 
 */

if( ! user_is_loggedin() ) {
    header( 'location: ../login.php' );
    exit;
}

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo $site_title; ?> &mdash; <?php echo get_siteinfo( 'site-name' ); ?></title>
        <link href="../assets/css/font-face.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/font-awesome-5/css/fontawesome-all.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/mdi-font/css/material-design-iconic-font.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/bootstrap-4.1/bootstrap.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/animsition/animsition.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/wow/animate.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/css-hamburgers/hamburgers.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/slick/slick.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/select2/select2.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/vendor/perfect-scrollbar/perfect-scrollbar.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="../assets/css/admin.css?ver=<?php echo filemtime( '../assets/css/admin.css' ); ?>" rel="stylesheet" media="all">
    </head>
    <body class="admin animsition">
        <div class="page-wrapper">
            <!-- HEADER MOBILE-->
            <header class="header-mobile d-block d-lg-none">
                <div class="header-mobile__bar">
                    <div class="container-fluid">
                        <div class="header-mobile-inner">
                            <a class="logo" href="index.php">
                                    <img src="../assets/images/logo.png" alt="San Jose Motors" />
                            </a>
                            <button class="hamburger hamburger--slider" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <nav class="navbar-mobile">
                    <div class="container-fluid">
                        <ul class="navbar-mobile__list list-unstyled">
                            <?php include 'navigation.php'; ?>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- END HEADER MOBILE-->