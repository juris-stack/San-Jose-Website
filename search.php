<?php
/* 
 * Search template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

if( empty( $_GET['s'] ) ) {
    redirect( site_url() );
}

$s = esc_str( $_GET['s'] );

$site_title = 'Search Result &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Search result for "' . $s . '"';
include_once 'header.php'; ?>

<main class="main">
    <div class="container">
        <div class="col-md-12 content">
            <div class="row"> 
            <?php
                $search = "%$s%";
                $products_select_stmt = $mysqli->prepare( "SELECT * FROM products WHERE name LIKE ? AND status='published' AND stocks > 0 ORDER BY name ASC" );
                $products_select_stmt->bind_param( 's', $search );
                $products_select_stmt->execute();
                $products_select_result = $products_select_stmt->get_result();
                if( $products_select_result->num_rows > 0 ) : ?>
                       <?php while( $row = $products_select_result->fetch_assoc() ) : ?>
                       
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
                            </div>
                        <?php endwhile; ?>
                        <?php else : ?>
                        <div class="col-md-12">
                            <header class="section-header search">
                                <h3 class="section-title"><strong>We couldn't find a <span style="color: #a94442;"><?php echo $s;?></span> for sale.</strong></h3>
                            </header>                    
                            <div class="row line-top">
                                <div class="col-md-12">
                                    <p class="section-title-medium">You may also like</p>
                                </div>
                                <?php
                                $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE status='published' AND stocks > 0 ORDER BY ID DESC LIMIT 8" );
                                $get_products_stmt->execute();
                                $get_products_result = $get_products_stmt->get_result();
                                if( $get_products_result->num_rows > 0 ) :
                                    while( $row = $get_products_result->fetch_assoc() ) : ?>
                                        <div class="col-md-3 col-sm-4 product-list">
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
                                        </div>
                                    <?php
                                    endwhile;
                                endif;
                                $get_products_stmt->close(); ?>
                        </div>
                        </div>
                <?php endif; $products_select_stmt->close(); ?>
            </div>
        </div>
    </div>
</main>

<?php include_once 'footer.php';