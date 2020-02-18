<?php
/* 
 * product add and edit template
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

$action = !empty( $_GET['action'] ) ? $_GET['action'] : 'add';
$product_id = !empty( $_GET['id'] ) ? $_GET['id'] : '';

if( ( $action === 'edit' || $action === 'delete' ) && empty( $product_id ) ) {
    redirect( 'products.php' );
}

if( $action === 'delete' ) {
    $stmt_delete = $mysqli->prepare( "DELETE FROM products WHERE ID = ?" );
    $stmt_delete->bind_param( 'i', $product_id );
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect( 'products.php' );
}

$title = '';
$excerpt = '';
$description = '';
$price = '';
$sale_price = '';
$sku = '';
$stocks = '';
$brand = '';
$category = '';
$status = '';
$date_added = '';

if( isset( $_POST['update'] ) ) {
    $title = esc_str( $_POST['title'] );
    $excerpt = esc_str( $_POST['excerpt'] );
    $description = esc_textarea( $_POST['description'] );
    $price = esc_str( $_POST['price'] );
    $sale_price = esc_str( $_POST['sale-price'] );
    $sku = esc_str( $_POST['sku'] );
    $stocks = empty( $_POST['stocks'] ) ? 0 : esc_str( $_POST['stocks'] );
    $brand = isset( $_POST['brand'] ) ? esc_str( $_POST['brand'] ) : '';
    $category = isset( $_POST['category'] ) ? esc_str( $_POST['category'] ) : '';
    $status = esc_str( $_POST['status'] );
    $slug = esc_slug( $title );
    
    if( $_POST['update'] === 'add' ) {
        $stmt_insert = $mysqli->prepare( "INSERT INTO products (slug, name, excerpt, description, category, brand, stocks, price, sale_price, sku, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" );
        $stmt_insert->bind_param( 'ssssiiissss', $slug, $title, $excerpt, $description, $category, $brand, $stocks, $price, $sale_price, $sku, $status );
        $stmt_insert->execute();
        $product_id = $mysqli->insert_id;
        $stmt_insert->close();
    }else{
        $stmt_update = $mysqli->prepare( "UPDATE products SET slug = ?, name = ?, excerpt = ?, description = ?, category = ?, brand = ?, stocks =?, price = ?, sale_price = ?, sku = ?, status = ? WHERE  ID = ?" );
        $stmt_update->bind_param( 'ssssiiissssi', $slug, $title, $excerpt, $description, $category, $brand, $stocks, $price, $sale_price, $sku, $status, $product_id );
        $stmt_update->execute();
        $stmt_update->close();
        set_site_notice( 'Product updated successfully.', 'success' );
    }
    
    // uploading image
    if( isset($_FILES['image']) && $_FILES['image']['size'] > 0 ) {
        upload_images( $_FILES['image']['tmp_name'], 'product', $product_id );
    }
    
    if( $_POST['update'] === 'add' ) {
        redirect( 'product_edit.php?action=edit&id=' . $product_id );
    }
}

if( $action === 'edit' ) {
    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
    $get_products_stmt->bind_param( 'i', $product_id );
    $get_products_stmt->execute();
    $get_products_result = $get_products_stmt->get_result();
    if( $get_products_result->num_rows > 0 ) {
        while( $row = $get_products_result->fetch_assoc() ) {
            $product_id = $row['ID'];
            $title = $row['name'];
            $excerpt = $row['excerpt'];
            $description = $row['description'];
            $price = $row['price'];
            $sale_price = $row['sale_price'];
            $sku = $row['sku'];
            $stocks = $row['stocks'];
            $category = $row['category'];
            $brand = $row['brand'];
            $status = $row['status'];
            $date_added = $row['date_added'];
        }
    }
}

$site_title = ucfirst( $action ) . ' Product';
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
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                    <strong>Product Details</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="title">Product Name</label>
                        <input type="text" value="<?php echo $title; ?>" name="title" class="form-control" id="title">
                    </div>
                    <div class="form-group">
                        <label for="excerpt">Short Description (excerpt)</label>
                        <textarea rows="3" name="excerpt" class="form-control" id="excerpt"><?php echo $excerpt; ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="price">Price</label>
                            <input type="text" value="<?php echo $price; ?>" name="price" class="form-control" id="price">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sale-price">Sale Price</label>
                            <input type="text" value="<?php echo $sale_price; ?>" name="sale-price" class="form-control" id="sale-price">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stocks">Stocks</label>
                            <input type="text" value="<?php echo $stocks; ?>" name="stocks" class="form-control" id="stocks">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sku">SKU</label>
                            <input type="text" value="<?php echo $sku; ?>" name="sku" class="form-control" id="sku">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Full Description</label>
                        <textarea rows="10" name="description" class="form-control" id="description"><?php echo $description; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>Publish</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="published" <?php selected( 'published', $status ); ?>>Publish</option>
                            <option value="draft" <?php selected( 'draft', $status ); ?>>Draft</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <?php
                    $btn_label = '';
                    $submit_type = '';
                    switch( $action ) {
                        case 'edit' :
                            $btn_label = 'Save Changes';
                            $submit_type = 'edit';
                            break;
                        case 'add' :
                        default :
                            $btn_label = 'Submit';
                            $submit_type = 'add';
                    } ?>
                    <button type="submit" class="btn btn-primary btn-md"><?php echo $btn_label; ?></button>
                    <input type="hidden" name="update" value="<?php echo $submit_type; ?>">
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <strong><label for="category">Category</label></strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <select id="category" class="form-control" name="category">
                            <?php
                            $get_category_stmt = $mysqli->prepare( "SELECT * FROM category ORDER BY name ASC" );
                            $get_category_stmt->execute();
                            $get_category_result = $get_category_stmt->get_result();
                            if( $get_category_result->num_rows > 0 ) {
                                while( $row = $get_category_result->fetch_assoc() ) {
                                    echo '<option value="' . $row['ID'] . '"';
                                    selected( $row['ID'], $category );
                                    echo '>' . $row['name'] . '</option>';
                                }
                            }else{
                                echo '<option value="">No category</option>';
                            } 
                            $get_category_stmt->close(); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <strong><label for="brand">Brand</label></strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <select id="brand" class="form-control" name="brand">
                            <option value="">Select Brand</option>
                            <?php
                            $get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand ORDER BY name ASC" );
                            $get_brand_stmt->execute();
                            $get_brand_result = $get_brand_stmt->get_result();
                            if( $get_brand_result->num_rows > 0 ) {
                                while( $row = $get_brand_result->fetch_assoc() ) {
                                    echo '<option value="' . $row['ID'] . '"';
                                    selected( $row['ID'], $brand );
                                    echo '>' . $row['name'] . '</option>';
                                }
                            }else{
                                echo '<option value="">No brand</option>';
                            }
                            $get_brand_stmt->close(); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <strong>Product Image</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="image-upload"><img class="image-preview" <?php
                        if( $action === 'edit' ) {
                            echo 'src="' . get_productimage( $product_id ) . '"';
                            } ?>></label>
                        <input type="file" name="image" id="image-upload">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include_once 'footer.php';