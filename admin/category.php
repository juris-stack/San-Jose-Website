<?php
/* 
 * Category and brand admin template
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

$posttype = !empty( $_GET['type'] ) ? $_GET['type'] : 'category';

$site_title = ( $posttype === 'brand' ) ? 'Brands' : 'Categories';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo $site_title; ?></h2>
        </div>
        <?php
        $paginationCtrls = '';
        $count_query = $mysqli->query( "SELECT COUNT(ID) FROM $posttype" );
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
        $get_brand_stmt = $mysqli->prepare( "SELECT * FROM $posttype ORDER BY date_added DESC $limit" );
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
                <?php echo $paginationCtrls; ?>
            </div>
            <div class="table-data__tool-right">
                <a href="<?php echo site_url( '/admin/category_edit.php?type=' . $posttype ); ?>" class="au-btn au-btn-icon au-btn--green au-btn--small">
                    <i class="zmdi zmdi-plus"></i>add <?php echo ucfirst( $posttype ); ?></a>
            </div>
        </div>
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th>title</th>
                        <th>date posted</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if( $get_brand_result->num_rows > 0 ) :
                        while( $row = $get_brand_result->fetch_assoc() ) :
                            $cat_id = $row['ID']; ?>
                            <tr class="tr-shadow">
                                <th>
                                    <a href="<?php echo site_url( '/' . $posttype . '.php?p=' . $row['slug'] ); ?>">
                                        <img class="image-small" src="<?php echo get_categoryimage( $cat_id, $posttype, 'small' ); ?>" alt="<?php echo $row['name']; ?>">
                                    </a>
                                </th>
                                <td><a href="<?php echo site_url( '/' . $posttype . '.php?p=' . $row['slug'] ); ?>"><?php echo $row['name']; ?></a></td>
                                <td><?php echo $row['date_added']; ?></td>
                                <td><?php echo ucfirst( $row['status'] ); ?></td>
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/category_edit.php?id=' . $cat_id . '&action=edit&type=' . $posttype ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a href="<?php echo site_url( '/admin/category_edit.php?id=' . $cat_id . '&action=delete&type=' . $posttype ); ?>" class="item js-confirm" data-toggle="tooltip" data-placement="top" title="Delete">
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