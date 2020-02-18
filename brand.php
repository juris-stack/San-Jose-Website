<?php
/* 
 * Brand single template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

if( empty( $_GET['p'] ) ) {
    redirect( 'index.php' );
}

$slug = $_GET['p'];

$brand_name = '';
$brand_id = '';
$get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand WHERE slug = ? LIMIT 1" );
$get_brand_stmt->bind_param( 's', $slug );
$get_brand_stmt->execute();
$get_brand_result = $get_brand_stmt->get_result();
if( $get_brand_result->num_rows > 0 ) {
    while( $row = $get_brand_result->fetch_assoc() ) {
        $brand_name = $row['name'];
        $brand_id = $row['ID'];
    }
}
$get_brand_stmt->close();

$site_title = $brand_name . ' &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Products for ' . $brand_name;
include_once 'header.php'; ?>

<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-9 content">
                <div class="row">
                    <?php
                    $paginationCtrls = '';
                    $count_query = $mysqli->query( "SELECT COUNT(ID) FROM products WHERE brand=$brand_id AND status='published' AND stocks > 0" );
                    $count_row = $count_query->fetch_row();
                    $count_rows = $count_row[0];
                    $per_page = 12;
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
                    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE brand=$brand_id AND status='published' AND stocks > 0 ORDER BY name ASC $limit" );
                    $get_products_stmt->execute();
                    $get_products_result = $get_products_stmt->get_result();
                    if( $get_products_result->num_rows > 0 ) :
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
                        while( $row = $get_products_result->fetch_assoc() ) : ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 product-list">
                                <figure>
                                    <a href="product.php?p=<?php echo $row['slug']; ?>">
                                        <img src="<?php echo get_productimage( $row['ID'], 'medium' ); ?>" alt="<?php echo $row['name']; ?>">
                                    </a>
                                </figure>
                                <h3><a href="product.php?p=<?php echo $row['slug']; ?>"><?php echo $row['name']; ?></a></h3>
                                <div class="product-meta">
                                    <span class="price">Price: &#8369; <?php
                                    if( (int) $row['sale_price'] > 0 ) {
                                        echo '<span class="not">' . $row['price'] . '</span>';
                                    }else{
                                        echo $row['price'];
                                    }
                                    if( (int) $row['sale_price'] > 0 ) {
                                        echo ' ' . $row['sale_price'];
                                    } ?>
                                    </span>
                                </div>
                               <!--  <form method="POST" action="">
                                    <input type="submit" class="btn btn-primary" value="Add to Cart">
                                    <input type="hidden" value="<?php echo $row['ID']; ?>" name="product-id">
                                    <input type="hidden" value="1" name="add-to-cart">
                                </form> -->
                            </div>
                        <?php
                        endwhile;
                    endif;
                    $get_products_stmt->close(); ?>
                </div>
                <?php echo $paginationCtrls; ?>
            </div>
            <?php include_once 'sidebar.php'; ?>
        </div>
    </div>
</main>

<?php include_once 'footer.php';