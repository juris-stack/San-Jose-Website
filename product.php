<?php
/* 
 * Product single template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

if( empty( $_GET['p'] ) ) {
    redirect( 'index.php' );
}

$slug = $_GET['p'];

$product_name = '';
$product_id = '';
$excerpt = '';
$description = '';
$price = '';
$sale_price = '';
$stocks = 0;
$sku = '';
$category = '';
$brand = '';
$get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE slug = ? LIMIT 1" );
$get_product_stmt->bind_param( 's', $slug );
$get_product_stmt->execute();
$get_product_result = $get_product_stmt->get_result();
if( $get_product_result->num_rows > 0 ) {
    while( $row = $get_product_result->fetch_assoc() ) {
        $product_name = $row['name'];
        $product_id = $row['ID'];
        $excerpt = $row['excerpt'];
        $description = $row['description'];
        $price = $row['price'];
        $sale_price = $row['sale_price'];
        $stocks = $row['stocks'];
        $sku = $row['sku'];
        $category = $row['category'];
        $brand = $row['brand'];
    }
}
$get_product_stmt->close();

$site_title = $product_name . ' &mdash; ' . get_siteinfo( 'site-name' );
$page_title = $product_name;
include_once 'header.php'; ?>

<main class="main">
    <div class="container">
        <div class="row">
            <div class="col-md-9 content product-bg">
                <div class="row">
                    <div class="col-md-6 product-image">
                        <figure>
                            <img src="<?php echo get_productimage( $product_id ); ?>" alt="<?php echo $product_name; ?>">
                        </figure>
                    </div>
                    <div class="col-md-6 product-details">
                        <p class="description"><?php echo $excerpt; ?></p>
                        <p class="price">&#8369; <?php
                            if( (int) $sale_price > 0 ) {
                                echo '<span class="not">' . $price . '</span>';
                            }else{
                                echo '<span>' . $price . '</span>';
                            }
                            if( (int) $sale_price > 0 ) {
                                echo ' <span>' . $sale_price . '</span>';
                            } ?>
                            </span>
                        </p>
                        <?php if( $stocks > 0 ) : ?>
                        <p class="in-stock"><i class="fa fa-check-circle"></i> <?php echo $stocks; ?> in stock</p>
                        
                        <form class="form-inline" method="POST" action="">
                            <div class="form-group">
                                <select class="form-control" name="quantity">
                                    <?php 
                                    for( $i = 1; $i <= $stocks; $i++ ) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    } ?>
                                </select>
                                <button type="submit" class="btn btn-primary add-to-cart"><i class="fa fa-shopping-basket"></i> Add to Cart</button>
                            </div>
                            <input type="hidden" value="<?php echo $product_id; ?>" name="product-id">
                            <input type="hidden" value="1" name="add-to-cart">
                        </form>
                        
                        
                        <?php else : ?>
                        <p class="out-of-stock">Out of stock</p>
                        <?php endif; ?>
                        <?php
                        $cat_links = [];
                        $get_category_stmt = $mysqli->prepare( "SELECT * FROM category WHERE ID = ? LIMIT 1" );
                        $get_category_stmt->bind_param( 'i', $category );
                        $get_category_stmt->execute();
                        $get_category_result = $get_category_stmt->get_result();
                        if( $get_category_result->num_rows > 0 ) {
                            echo '<p class="category">Category: ';
                            while( $row = $get_category_result->fetch_assoc() ) {
                                $cat_links[] = '<a href="category.php?p=' . $row['slug'] . '">' . $row['name'] . '</a>';
                            }
                            echo implode( ', ', $cat_links );
                            //echo '</p>';
                        }
                        $get_category_stmt->close();
                        
                        $brand_links = [];
                        $get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand WHERE ID = ? LIMIT 1" );
                        $get_brand_stmt->bind_param( 'i', $brand );
                        $get_brand_stmt->execute();
                        $get_brand_result = $get_brand_stmt->get_result();
                        if( $get_brand_result->num_rows > 0 ) {
                            //echo '<p class="category">Brand: ';
                            echo '<br>Brand: ';
                            while( $row = $get_brand_result->fetch_assoc() ) {
                                $brand_links[] = '<a href="brand.php?p=' . $row['slug'] . '">' . $row['name'] . '</a>';
                            }
                            echo implode( ', ', $brand_links );
                            echo '</p>';
                        }
                        $get_brand_stmt->close(); ?>
                    </div>
                    <div class="col-md-12 product-description">
                        <h3>Product Description</h3>
                        <p><?php echo nl2br( $description ); ?></p>
                    </div>
                </div>
            </div>
            <?php include_once 'sidebar.php'; ?>
        </div>
    </div>
</main>

<?php include_once 'footer.php';