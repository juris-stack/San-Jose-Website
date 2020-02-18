<?php
/* 
 * Report Template
 * 
 * @package SJM
 * @author
 */

// include the admin functions
require_once 'functions.php';

/** Block unauthorized users */
if( currentuser_is_customer() ) {
    die( 'You are unauthorized to access this part of our website!' );
}

$site_title = 'Reports';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Online Orders Sales Reports</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="today-tab" data-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="true">Today &mdash; <?php echo date( 'M j, Y' ); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="yesterday-tab" data-toggle="tab" href="#yesterday" role="tab" aria-controls="yesterday" aria-selected="false">Yesterday</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="week-tab" data-toggle="tab" href="#week" role="tab" aria-controls="week" aria-selected="false">Week</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="month-tab" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="false">Month</a>
            </li>
        </ul>
        <div class="tab-content pl-3 p-1" id="myTabContent">
            <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
                <?php
                $today_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '3' ) {
                    $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online Order' AND DATE(date_added) = CURDATE() ORDER BY date_added DESC" );
                }else if( $role == '2' ){
                    $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online Order' AND DATE(date_added) = CURDATE() AND managed_by = '$managed_by' ORDER BY date_added DESC" );
                }
                $today_earnings_stmt->execute();
                $today_earnings_result = $today_earnings_stmt->get_result();
                $today_count = $today_earnings_result->num_rows;
                $todays = [];
                if( $today_count > 0 ) {
                    while( $row = $today_earnings_result->fetch_assoc() ) {
                        $today_earning += $row['amount'];
                        $todays[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">Today's Sale &mdash; &#8369; <?php echo number_format( $today_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $today_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=today' ); ?>" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $today_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order_#</th>
                                    <th>Products</th>
                                    <th>Customer</th>
                                    <th>Managed_by</th>
                                    <th>amount</th>
                                    <th>date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $todays as $today ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $today['ID'] ); ?>"><?php echo $today['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $today['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '">' . $name . '</a> X ' . $qty . ' @ ' . $price . ' = ' . $total . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php if( $today['user_ID'] ) {
                                            echo '<a href="profile.php?id=' . $today['user_ID'] . '">' . get_userby( 'username', $today['user_ID'] ) . '</a>';
                                        } ?></td>
                                        <td><?php echo $today['managed_by']; ?></td>
                                        <td>&#8369;<?php echo number_format($today['amount'], 2); ?></td>
                                        <td><?php echo $today['date_added']; ?></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $today_earnings_stmt->close(); ?>
            </div>
            <div class="tab-pane fade" id="yesterday" role="tabpanel" aria-labelledby="yesterday-tab">
                <?php
                $yesterday_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '3' ) {
                    $yesterday_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online order' AND DATE(date_added) = CURDATE() - INTERVAL 1 DAY ORDER BY date_added DESC" );
                }else if( $role == '2' ){
                    $yesterday_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online order' AND DATE(date_added) = CURDATE() - INTERVAL 1 DAY AND managed_by = '$managed_by' ORDER BY date_added DESC" );
                }
                $yesterday_earnings_stmt->execute();
                $yesterday_earnings_result = $yesterday_earnings_stmt->get_result();
                $yesterday_count = $yesterday_earnings_result->num_rows;
                $yesterdays = [];
                if( $yesterday_count > 0 ) {
                    while( $row = $yesterday_earnings_result->fetch_assoc() ) {
                        $yesterday_earning += $row['amount'];
                        $yesterdays[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">Yesterday's Sale &mdash; &#8369; <?php echo number_format( $yesterday_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $yesterday_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=yesterday' ); ?>" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $yesterday_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order_#</th>
                                    <th>Products</th>
                                    <th>Customer</th>
                                    <th>Managed_by</th>
                                    <th>amount</th>
                                    <th>date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $yesterdays as $yesterday ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $yesterday['ID'] ); ?>"><?php echo $yesterday['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $yesterday['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '">' . $name . '</a> X ' . $qty . ' @ ' . $price . ' &mdash; ' . $total . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php if( $yesterday['user_ID'] ) {
                                            echo '<a href="profile.php?id=' . $yesterday['user_ID'] . '">' . get_userby( 'username', $yesterday['user_ID'] ) . '</a>';
                                        } ?></td>
                                        <td><?php echo $yesterday['managed_by']; ?></td>
                                        <td>&#8369;<?php echo number_format($yesterday['amount'], 2); ?></td>
                                        <td><?php echo $yesterday['date_added']; ?></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $yesterday_earnings_stmt->close(); ?>
            </div>
            <div class="tab-pane fade" id="week" role="tabpanel" aria-labelledby="week-tab">
                <?php
                $week_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '3' ) {
                    $week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online order' AND YEARWEEK(date_added) = YEARWEEK(CURDATE()) ORDER BY date_added DESC" );
                }else if( $role == '2' ){
                    $week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online order' AND YEARWEEK(date_added) = YEARWEEK(CURDATE()) AND managed_by = '$managed_by' ORDER BY date_added DESC" );
                }
                $week_earnings_stmt->execute();
                $week_earnings_result = $week_earnings_stmt->get_result();
                $week_count = $week_earnings_result->num_rows;
                $weeks = [];
                if( $week_count > 0 ) {
                    while( $row = $week_earnings_result->fetch_assoc() ) {
                        $week_earning += $row['amount'];
                        $weeks[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">This week's Sale &mdash; &#8369; <?php echo number_format( $week_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $week_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=week' ); ?>" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $week_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order_#</th>
                                    <th>Products</th>
                                    <th>Customer</th>
                                    <th>Managed_by</th>
                                    <th>amount</th>
                                    <th>date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $weeks as $week ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $week['ID'] ); ?>"><?php echo $week['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $week['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '">' . $name . '</a> X ' . $qty . ' @ ' . $price . ' &mdash; ' . $total . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php if( $week['user_ID'] ) {
                                            echo '<a href="profile.php?id=' . $week['user_ID'] . '">' . get_userby( 'username', $week['user_ID'] ) . '</a>';
                                        } ?></td>
                                        <td><?php echo $week['managed_by']; ?></td>
                                        <td>&#8369;<?php echo number_format($week['amount'], 2); ?></td>
                                        <td><?php echo $week['date_added']; ?></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $week_earnings_stmt->close(); ?>
            </div>
            <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                <?php
                $month_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '3' ) {
                    $month_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online order' AND MONTH(date_added) = MONTH(CURDATE()) ORDER BY date_added DESC" );
                }else if( $role == '2' ){
                    $month_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Online order' AND MONTH(date_added) = MONTH(CURDATE()) AND managed_by = '$managed_by' ORDER BY date_added DESC" );
                }
                $month_earnings_stmt->execute();
                $month_earnings_result = $month_earnings_stmt->get_result();
                $month_count = $month_earnings_result->num_rows;
                $months = [];
                if( $month_count > 0 ) {
                    while( $row = $month_earnings_result->fetch_assoc() ) {
                        $month_earning += $row['amount'];
                        $months[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">This month's Sale &mdash; &#8369; <?php echo number_format( $month_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $month_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=month' ); ?>" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $month_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order_#</th>
                                    <th>Products</th>
                                    <th>Customer</th>
                                    <th>Managed_by</th>
                                    <th>amount</th>
                                    <th>date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $months as $month ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $month['ID'] ); ?>"><?php echo $month['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $month['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '">' . $name . '</a> X ' . $qty . ' @ ' . $price . ' = ' . $total . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php if( $month['user_ID'] ) {
                                            echo '<a href="profile.php?id=' . $month['user_ID'] . '">' . get_userby( 'username', $month['user_ID'] ) . '</a>';
                                        } ?></td>
                                        <td><?php echo $month['managed_by']; ?></td>
                                        <td>&#8369;<?php echo number_format($month['amount'], 2); ?></td>
                                        <td><?php echo $month['date_added']; ?></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $month_earnings_stmt->close(); ?>
            </div>
        </div>

    </div>
</div>

<?php include_once 'footer.php';