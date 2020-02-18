<?php
/** 
 * Admin orders template
 * 
 * @package SJM
 * @author 
 */

// include the admin functions
require_once 'functions.php'; 

$site_title = 'Orders';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Orders</h2>
        </div>
        <?php
        $paginationCtrls = '';
        if( currentuser_is_customer() ) {
            $count_query = $mysqli->query( "SELECT COUNT(ID) FROM orders WHERE user_ID = $user_id" );
        }else{
            $count_query = $mysqli->query( "SELECT COUNT(ID) FROM orders" );
        }
        $count_row = $count_query->fetch_row();
        $count_rows = $count_row[0];
        $per_page = 10;
        $last = ceil( $count_rows/$per_page );
        if( $last < 1 ){
            $last = 1;
        }
        $pagenum = 1;
        if( isset( $_GET['page'] ) ){
            $pagenum = (int) $_GET['page'];
        }
        if ( $pagenum < 1 ) { 
            $pagenum = 1; 
        } elseif ( $pagenum > $last) { 
            $pagenum = $last; 
        }
        $limit = 'LIMIT ' .( $pagenum - 1 ) * $per_page .',' .$per_page;
        if( currentuser_is_customer() ) {
            $get_brand_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE user_ID = $user_id ORDER BY date_added DESC $limit" );
        }else{
            $get_brand_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type != 'walk-in' ORDER BY date_added DESC $limit" );
        }
        $get_brand_stmt->execute();
        $get_brand_result = $get_brand_stmt->get_result();
        if( $get_brand_result->num_rows > 0 ) {

            if($last != 1){
                $paginationCtrls .= '<nav class="pagination-controls" aria-label="..."><ul class="pagination">';
                if ($pagenum > 1) {
                    $previous = $pagenum - 1;
                    $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'. add_query_arg( 'page', $previous, get_currenturl() ) .'">Previous</a></li>';
                    // Render clickable number links that should appear on the left of the target page number
                    for($i = $pagenum-4; $i < $pagenum; $i++){
                        if($i > 0){
                            $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'.add_query_arg( 'page', $i, get_currenturl() ).'">'.$i.'</a></li>';
                        }
                    }
                }
                // Render the target page number, but without it being a link
                $paginationCtrls .= '<li class="page-item active"><span class="page-link">'.$pagenum.'</span></li>';
                // Render clickable number links that should appear on the right of the target page number
                for($i = $pagenum+1; $i <= $last; $i++){
                    $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'.add_query_arg( 'page', $i, get_currenturl() ).'">'.$i.'</a></li>';
                    if($i >= $pagenum+4){
                        break;
                    }
                }
                    // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                if ($pagenum != $last) {
                    $next = $pagenum + 1;
                    $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'.add_query_arg( 'page', $next, get_currenturl() ).'">Next</a></li>';
                }
                $paginationCtrls .= '</ul></nav>';
            }
        } ?>
        <div class="table-data__tool">
            <div class="table-data__tool-left">
                <input type="search" id="order-search" name="order-search" class="form-control" placeholder="Search order...">
            </div>
            <div class="table-data__tool-right">
                <select class="order-status-select form-control">
                    <option value="">Choose Status...</option>
                    <option value="pending">Pending</option>
                    <option value="processed">Processed</option>
                    <option value="completed">Complete</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2">
                <thead class="thead-light">
                    <tr>
                        <th>Order #</th>
                        <th>customer</th>
                        <th>amount</th>
                        <th>status</th>
                        <th>date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="orders-result">
                    <?php
                    if( $get_brand_result->num_rows > 0 ) :
                        while( $row = $get_brand_result->fetch_assoc() ) : ?>
                            <tr class="tr-shadow">
                                <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ); ?>"><?php echo $row['ID']; ?></a></td>
                                <td><?php if( $row['user_ID'] ) {
                                    echo '<a href="profile.php?id=' . $row['user_ID'] . '">' . get_userby( 'username', $row['user_ID'] ) . '</a>';
                                } ?></td>
                                <td>&#8369; <?php echo $row['amount']; ?></td>
                                <td><?php echo ucfirst( $row['status'] ); ?></td>
                                <td><?php echo $row['date_added']; ?></td>
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url( '/admin/order.php?action=delete&id=' . $row['ID'] ); ?>" class="item js-confirm" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="spacer"></tr>
                        <?php 
                        endwhile;
                        echo '<tr class="spacer"></tr>';
                    endif; ?>
                </tbody>
            </table>
            <?php echo $paginationCtrls; ?>
        </div>
    </div>
</div>
                        
<?php include_once 'footer.php';