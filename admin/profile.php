<?php
/** 
 * Admin profile template
 * 
 * @package SJM
 * @author 
 */

// include the admin functions
require_once 'functions.php';

global $user_id;

$uid = ! empty( $_GET['id'] ) ? esc_int( $_GET['id'] ) : $user_id;

$site_title = 'Profile';
if( empty( $_GET['id'] ) ) {
    $site_title = ucfirst( get_userby( 'username', $uid ) );
}
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo $site_title; ?></h2>
        </div>
    </div>
</div>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong><?php if( empty( $_GET['id'] ) ) {
                        echo 'My Orders';
                    }else{
                        echo 'Orders';
                    } ?></strong>
                </div>
                <div class="card-body card-block">
                    <div class="table-responsive table-responsive-data2">
                        <table class="table table-data2">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>amount</th>
                                    <th>status</th>
                                    <th>date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE user_ID = ? ORDER BY date_added DESC" );
                                $orders_stmt->bind_param( 'i', $uid );
                                $orders_stmt->execute();
                                $orders_result = $orders_stmt->get_result(); 
                                if( $orders_result->num_rows > 0 ) :
                                    while( $row = $orders_result->fetch_assoc() ) : ?>
                                        <tr class="tr-shadow">
                                            <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ); ?>"><?php echo $row['ID']; ?></a></td>
                                            <td>&#8369; <?php echo $row['amount']; ?></td>
                                            <td><?php echo ucfirst( $row['status'] ); ?></td>
                                            <td><?php echo $row['date_added']; ?></td>
                                            <?php if( esc_int( $row['user_ID'] ) === $user_id && $row['status'] !== 'cancelled' && $row['status'] !== 'completed' ) : ?>
                                            <td>
                                                <div class="table-data-feature">
                                                    <a href="<?php echo site_url( '/admin/order.php?action=cancel&id=' . $row['ID'] ); ?>" class="item js-confirm" data-confirm-text="Are you sure you want to cancel your order?" data-toggle="tooltip" data-placement="top" title="Cancel">
                                                        <i class="zmdi zmdi-block-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php 
                                    endwhile;
                                endif; 
                                $orders_stmt->close(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
                        
<?php include_once 'footer.php';