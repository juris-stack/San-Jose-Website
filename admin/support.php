<?php
/** 
 * Admin support template
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

$site_title = 'Chat Support Messages';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo $site_title; ?></h2>
        </div>
        <?php
        $paginationCtrls = '';
        $count_query = $mysqli->query( "SELECT COUNT(ID) FROM support" );
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
        $get_chat_stmt = $mysqli->prepare( "SELECT * FROM support ORDER BY date_added DESC $limit" );
        $get_chat_stmt->execute();
        $get_chat_result = $get_chat_stmt->get_result();
        if( $get_chat_result->num_rows > 0 ) {

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
                <input type="search" id="message-search" name="message-search" class="form-control" placeholder="Search message...">
            </div>
        </div>
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2 support-table">
                <thead class="thead-light">
                    <tr>
                        <th>from name</th>
                        <th>from email</th>
                        <th>status</th>
                        <th>Time</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="messages-result">
                    <?php
                    if( $get_chat_result->num_rows > 0 ) :
                        while( $row = $get_chat_result->fetch_assoc() ) :
                            ?>
                            <tr class="tr-shadow <?php echo $row['status']; ?>">
                                <td><a href="<?php echo site_url( '/admin/chat.php?id=' . $row['ID'] ); ?>"><?php echo $row['from_name']; ?></a></td>
                                <td><a href="<?php echo site_url( '/admin/chat.php?id=' . $row['ID'] ); ?>"><?php echo $row['from_email']; ?></a></td>
                                <td><?php echo ucfirst( $row['status'] ); ?></td>
                                <td><?php echo time_ago( $row['date_added'] ); ?></td>
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/chat.php?id=' . $row['ID'] ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reply">
                                            <i class="zmdi zmdi-mail-reply"></i>
                                        </a>
                                        <a href="<?php echo site_url( '/admin/chat.php?action=delete&id=' . $row['ID'] ); ?>" class="item js-confirm" data-toggle="tooltip" data-placement="top" title="Delete">
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