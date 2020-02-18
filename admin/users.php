<?php
/** 
 * Admin products template
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

$site_title = 'Users';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Users</h2>
        </div>
        <?php
        $paginationCtrls = '';
        if( currentuser_is_admin() ) {
            $count_query = $mysqli->query( "SELECT COUNT(ID) FROM users" );
        }else{
            $count_query = $mysqli->query( "SELECT COUNT(ID) FROM users WHERE role = 1" );
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
        if( currentuser_is_admin() ) {
            $get_brand_stmt = $mysqli->prepare( "SELECT * FROM users ORDER BY ID ASC $limit" );
        }else{
            $get_brand_stmt = $mysqli->prepare( "SELECT * FROM users WHERE role = 1 ORDER BY ID ASC $limit" );
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
        <?php if( currentuser_is_admin() ) : ?>
        <div class="table-data__tool">
            <div class="table-data__tool-left">
                <input type="search" id="user-search" name="user-search" class="form-control" placeholder="Search user...">
            </div>
            <div class="table-data__tool-right">
                <a href="<?php echo site_url( '/admin/user.php?action=add' ); ?>" class="au-btn au-btn-icon au-btn--green au-btn--small">
                    <i class="zmdi zmdi-account-add"></i> add user</a>
            </div>
        </div>
        <?php endif; ?>
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th>name</th>
                        <th>email</th>
                        <th>date</th>
                        <th>role</th>
                        <th>active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="users-result">
                    <?php
                    if( $get_brand_result->num_rows > 0 ) :
                        while( $row = $get_brand_result->fetch_assoc() ) :
                            $uid = $row['ID'];
                            $role = 'Customer';
                            switch( $row['role'] ) {
                                case 3 :
                                    $role = 'Admin';
                                    break;
                                case 2 :
                                    $role = 'Staff';
                                    break;
                            } ?>
                            <tr class="tr-shadow">
                                <td><a href="profile.php?id=<?php echo $uid; ?>"><img class="image-small" src="<?php echo get_userimage( $uid, 'small' ); ?>" alt="<?php echo $row['username']; ?>"></a></td>
                                <td><a href="profile.php?id=<?php echo $uid; ?>"><?php echo $row['username']; ?></a></td>
                                <td><span class="block-email"><?php echo $row['email']; ?></span></td>
                                <td><?php echo $row['reg_date']; ?></td>
                                <td><?php echo $role; ?></td>
                                <td><?php 
                                switch( $row['active'] ) {
                                    case 0 :
                                    case '0' :
                                        echo 'Blocked';
                                        break;
                                    case 1 :
                                    case '1' :
                                        echo 'Active';
                                        break;
                                } ?></td>
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/user.php?action=edit&id=' . $uid ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <?php if( currentuser_is_admin() ) : ?>
                                        <?php if( esc_int( $row['active'] ) > 0 ) : ?>
                                        <a href="<?php echo site_url( '/admin/user.php?action=edit&block=1&id=' . $uid ); ?>" class="item js-confirm" data-confirm-text="Are you sure you want to block this user?" data-toggle="tooltip" data-placement="top" title="Block">
                                            <i class="zmdi zmdi-block"></i>
                                        <?php else: ?>
                                        <a href="<?php echo site_url( '/admin/user.php?action=edit&unblock=1&id=' . $uid ); ?>" class="item js-confirm" data-confirm-text="Are you sure you want to unblock this user?" data-toggle="tooltip" data-placement="top" title="Unblock">
                                            <i class="zmdi zmdi-refresh-alt"></i>
                                        <?php endif; ?>
                                        </a>
                                        <a href="<?php echo site_url( '/admin/user.php?action=delete&id=' . $uid ); ?>" class="item js-confirm" data-confirm-text="Are you sure you want to delete this user?" data-toggle="tooltip" data-placement="top" title="Delete">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                        <?php endif; ?>
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