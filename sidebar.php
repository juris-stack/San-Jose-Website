<?php
/** 
 * Sidebar template
 * 
 * @package SJM
 * @author 
 */
?>
<div class="col-md-3 widgets">
    <div class="widget">
        <h3 class="widget-title">Categories</h3>
            <?php
            $get_category_stmt = $mysqli->prepare( "SELECT * FROM category WHERE status='published' ORDER BY name ASC" );
            $get_category_stmt->execute();
            $get_category_result = $get_category_stmt->get_result();
            if( $get_category_result->num_rows > 0 ) {
                while( $row = $get_category_result->fetch_assoc() ) {
                    echo '<li class="widget-list"><a href="category.php?p=' . $row['slug'] . '">' . $row['name'] . '</a></li>';
                }
            }
            $get_category_stmt->close(); ?>
    </div>
    <div class="widget">
        <h3 class="widget-title">Brands</h3>
            <?php
            $get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand WHERE status='published' ORDER BY name ASC" );
            $get_brand_stmt->execute();
            $get_brand_result = $get_brand_stmt->get_result();
            if( $get_brand_result->num_rows > 0 ) {
                while( $row = $get_brand_result->fetch_assoc() ) {
                    echo '<li class="widget-list"><a href="brand.php?p=' . $row['slug'] . '">' . $row['name'] . '</a></li>';
                }
            }
            $get_brand_stmt->close(); ?>
    </div>
</div>