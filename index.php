<?php
/**
 * The main index
 * 
 * @package SJM
 * @author
 */
require_once 'functions.php';

$site_title = get_siteinfo( 'site-name' );
include_once 'header.php'; ?>

<main class="main">
    <section class="section-block">
        <div class="container">
            <div class="row">
                <?php
                $get_category_stmt = $mysqli->prepare( "SELECT * FROM category WHERE status='published' ORDER BY name ASC LIMIT 4" );
                $get_category_stmt->execute();
                $get_category_result = $get_category_stmt->get_result();
                if( $get_category_result->num_rows > 0 ) :
                    while( $row = $get_category_result->fetch_assoc() ) : ?>
                        <div class="col-md-3 col-sm-4">
                            <a class="cat-list" href="category.php?p=<?php echo $row['slug']; ?>">
                                <figure>
                                    <img src="<?php echo get_categoryimage( $row['ID'], 'category', 'medium' ); ?>" alt="<?php echo $row['name']; ?>">
                                </figure>
                                <h3><?php echo $row['name']; ?></h3>
                            </a>
                        </div>
                    <?php
                    endwhile;
                endif;
                $get_category_stmt->close(); ?>
            </div>
        </div>
    </section>
    <section class="section-block" id="home-latest-products">
        <div class="container">
            <div class="col-md-12">
                <header class="section-header">
                    <h3 class="section-title"><strong>LATEST PRODUCTS</strong></h3>
                </header>
                <div class="row">
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
        </div>
    </section>
    <section class="section-block" id="brand-logos">
        <div class="container">
            <header class="section-header">
                <h3 class="section-title small centered">Our Trusted Brands</h3>
            </header>
            <ul class="brand-icons centered">
                <?php
                $get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand WHERE status='published'" );
                $get_brand_stmt->execute();
                $get_brand_result = $get_brand_stmt->get_result();
                if( $get_brand_result->num_rows > 0 ) :
                    while( $row = $get_brand_result->fetch_assoc() ) : ?>
                        <li>
                            <a class="brand-icon" href="brand.php?p=<?php echo $row['slug']; ?>">
                                <figure>
                                    <img src="<?php echo get_categoryimage( $row['ID'], 'brand', 'small' ); ?>" alt="<?php echo $row['name']; ?>">
                                </figure>
                            </a>
                        </li>
                    <?php
                    endwhile;
                endif;
                $get_brand_stmt->close(); ?>
            </ul>
        </div>
    </section>
</main>

<?php include_once 'footer.php';