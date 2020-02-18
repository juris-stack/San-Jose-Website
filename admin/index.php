<?php
/**
 * Admin main index
 * 
 * @package SJM
 * @author 
 */

// include the admin functions
require_once 'functions.php';

if( currentuser_is_customer() ) {
    include_once 'profile.php';
    exit;
}

$site_title = 'Dashboard';
include_once 'header.php';
include_once 'sidebar.php'; ?>

            
<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Overview</h2>
        </div>
    </div>
</div>
<div class="row m-t-25">
    <?php
    $count_user_query = $mysqli->query( "SELECT COUNT(ID) FROM users" );
    $count_user_row = $count_user_query->fetch_row();
    $count_users = $count_user_row[0]; ?>
    <div class="col-sm-6 col-lg-4">
        <div class="overview-item overview-item--c1">
            <div class="overview__inner">
                <div class="overview-box clearfix">
                    <div class="icon">
                        <i class="zmdi zmdi-account-o"></i>
                    </div>
                    <div class="text">
                        <h2><?php echo $count_users; ?></h2>
                        <span>members</span>
                    </div>
                </div>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
    <?php
    $week_earning = 0;
    $week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE (status = 'processed' OR status = 'completed') AND YEARWEEK(date_added) = YEARWEEK(CURDATE())" );
    $week_earnings_stmt->execute();
    $week_earnings_result = $week_earnings_stmt->get_result();
    if( $week_earnings_result->num_rows > 0 ) {
        while( $row = $week_earnings_result->fetch_assoc() ) {
            $week_earning += $row['amount'];
        }
    }
    $week_earnings_stmt->close(); ?>
    <div class="col-sm-6 col-lg-4">
        <div class="overview-item overview-item--c3">
            <div class="overview__inner">
                <div class="overview-box clearfix">
                    <div class="icon">
                        <i class="zmdi zmdi-calendar-note"></i>
                    </div>
                    <div class="text">
                        <h2>&#8369; <?php echo number_format( $week_earning, 2 ); ?></h2>
                        <span>earnings this week</span>
                    </div>
                </div>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
    <?php
    $total_earning = 0;
    $total_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status = 'processed' OR status = 'completed'" );
    $total_earnings_stmt->execute();
    $total_earnings_result = $total_earnings_stmt->get_result();
    if( $total_earnings_result->num_rows > 0 ) {
        while( $row = $total_earnings_result->fetch_assoc() ) {
            $total_earning += $row['amount'];
        }
    }
    $total_earnings_stmt->close(); ?>
    <div class="col-sm-6 col-lg-4">
        <div class="overview-item overview-item--c4">
            <div class="overview__inner">
                <div class="overview-box clearfix">
                    <div class="icon">
                        <i class="zmdi zmdi-money-box"></i>
                    </div>
                    <div class="text">
                        <h2>&#8369; <?php echo number_format( $total_earning, 2 ); ?></h2>
                        <span>total earnings</span>
                    </div>
                </div>
                <p>&nbsp;</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h2 class="title-1 m-b-25">Low In Stock</h2>
        <div class="table-responsive table--no-card m-b-40">
            <table class="table table-borderless table-striped table-earning">
                <thead>
                    <tr>
                        <th></th>
                        <th>product</th>
                        <th>price</th>
                        <th>quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE stocks < 2 ORDER BY name ASC" );
                    $get_products_stmt->execute();
                    $get_products_result = $get_products_stmt->get_result();
                    if( $get_products_result->num_rows > 0 ) :
                        while( $row = $get_products_result->fetch_assoc() ) : ?>
                            <tr>
                                <td>
                                    <a href="<?php echo site_url( '/product.php?id=' . $row['ID'] ); ?>">
                                        <img class="image-small" src="<?php echo get_productimage( $row['ID'], 'small' ); ?>" alt="<?php echo $row['name']; ?>">
                                    </a>
                                </td>
                                <td><a href="<?php echo site_url( '/product.php?id=' . $row['ID'] ); ?>"><?php echo $row['name']; ?></a></td>
                                <td>&#8369; <?php echo number_format( $row['price'], 2 ); ?></td>
                                <td><?php echo $row['stocks']; ?></td>
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/product_edit.php?id=' . $row['ID'] . '&action=edit' ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php 
                        endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

                        
<?php include_once 'footer.php';