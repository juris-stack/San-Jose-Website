<?php
/**
 * Site header template
 * 
 * @package SJM
 * @author 
 */
?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php echo $site_title; ?></title>
        <link href="assets/css/font-face.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="assets/vendor/font-awesome-5/css/fontawesome-all.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="assets/vendor/bootstrap-4.1/bootstrap.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
        <link href="assets/css/frontend.css?ver=<?php echo filemtime( 'assets/css/frontend.css' ); ?>" rel="stylesheet" media="all">
    </head>
    <body class="animsition page <?php echo str_replace( '.php', '', get_currenturl_filename() ); ?>">
        <div class="wrapper">
            <header id="site-header">
                <div id="top-header">
                    <div class="container clearfix">
                        <form id="header-search" action="search.php">
                            <input type="search" name="s" placeholder="Search product, brand, etc here...">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                        <ul class="right-nav clearfix">                            
                            <li>
                                <a href="cart.php"><i class="fa fa-shopping-basket"></i>Cart<?php
                                $cart_items = get_cart_items();
                                if( !empty( $cart_items ) && is_array( $cart_items ) && count( $cart_items ) > 0 ) {
                                    echo '<div id="cart-count">' . count( $cart_items ) . '</div>';
                                } ?></a>
                            </li>
                            <li><a href="checkout.php">Checkout</a></li>
                            <?php
                            if( user_is_loggedin() ) : ?>
                            <li class="has-sub-menu">
                                <a href="#">
                                    <img src="<?php echo get_userimage( $user_id ); ?>" alt="<?php echo get_currentuser( 'username' ); ?>"><?php echo ucfirst( get_currentuser( 'username' ) ); ?>
                                </a>
                                <ul>
                                    <li><a href="admin/index.php">Dashboard</a></li>
                                    <li><a href="admin/user.php">Account</a></li>
                                    <li><a href="login.php?action=logout&redirect=<?php echo get_currenturl(); ?>">Logout</a></li>
                                </ul>
                            </li>
                            <?php else : ?>
                            <li><a href="login.php">Login or Register</a></li>
                            <?php endif; ?>                           
                        </ul>
                    </div>
                </div>
                <nav id="site-nav">
                    <div class="container">
                        <div class="mobile-head">
                            <h2><a class="mobile-logo" href="index.php"><img src="assets/images/logo-small.png" alt="<?php echo get_siteinfo( 'site-name' ); ?>"></a></h2>
                            <a id="nav-toggle" href="#"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="menu main-menu" id="main-menu">
                            <li class="menu-item"><a href="index.php">Home</a></li>
                            <li class="menu-item"><a href="products.php">Shop</a></li>
                            <li class="menu-item has-sub-menu">
                                <a href="#">Category</a>
                                <ul class="sub-menu">
                                    <?php
                                    $get_category_stmt = $mysqli->prepare( "SELECT * FROM category WHERE status='published' ORDER BY name ASC" );
                                    $get_category_stmt->execute();
                                    $get_category_result = $get_category_stmt->get_result();
                                    if( $get_category_result->num_rows > 0 ) {
                                        while( $row = $get_category_result->fetch_assoc() ) {
                                            echo '<li><a href="category.php?p=' . $row['slug'] . '">' . $row['name'] . '</a></li>';
                                        }
                                    }
                                    $get_category_stmt->close(); ?>
                                </ul>
                            </li>
                            <li class="logo"><a href="index.php"><img src="assets/images/logo-small.png" alt="<?php echo get_siteinfo( 'site-name' ); ?>"></a></li>
                            <li class="menu-item has-sub-menu">
                                <a href="#">Brand</a>
                                <ul class="sub-menu">
                                    <?php
                                    $get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand WHERE status='published' ORDER BY name ASC" );
                                    $get_brand_stmt->execute();
                                    $get_brand_result = $get_brand_stmt->get_result();
                                    if( $get_brand_result->num_rows > 0 ) {
                                        while( $row = $get_brand_result->fetch_assoc() ) {
                                            echo '<li><a href="brand.php?p=' . $row['slug'] . '">' . $row['name'] . '</a></li>';
                                        }
                                    }
                                    $get_brand_stmt->close(); ?>
                                </ul>
                            </li>
                            <li class="menu-item"><a href="about.php">About</a></li>
                            <li class="menu-item"><a href="contact.php">Locate Us</a></li>
                        </ul>
                    </div>
                </nav>
            </header>
            
            <?php if( ! is_home() ) : ?>
            <div class="page-header">
                <div class="container">
                    <h2><?php echo $page_title; ?></h2>
                </div>
            </div>
            <?php else : ?>
            <div id="home-banner">
                <div>
                    <img src="assets/images/banner1.jpg" alt="Banner">
                </div>
            </div>
            <?php endif;
            
            // Show notices
            show_site_notice();