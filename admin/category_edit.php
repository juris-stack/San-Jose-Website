<?php
/** 
 * Brand add and edit template
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
$category_id = !empty( $_GET['id'] ) ? $_GET['id'] : '';
$posttype = !empty( $_GET['type'] ) ? $_GET['type'] : 'category';

if( ( $action === 'edit' || $action === 'delete' ) && empty( $category_id ) ) {
    redirect( 'category.php' );
}

if( $action === 'delete' ) {
    $stmt_delete = $mysqli->prepare( "DELETE FROM $posttype WHERE ID = ?" );
    $stmt_delete->bind_param( 'i', $category_id );
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect( 'category.php?type=' . $posttype );
}

$title = '';
$description = '';
$status = '';
if( isset( $_POST['update'] ) ) {
    $title = esc_str( $_POST['title'] );
    $description = esc_textarea( $_POST['description'] );
    $status = esc_str( $_POST['status'] );
    $slug = esc_slug( $title );
    
    if( $_POST['update'] === 'add' ) {
        $stmt_insert = $mysqli->prepare( "INSERT INTO $posttype (slug, name, description, status) VALUES (?, ?, ?, ?)" );
        $stmt_insert->bind_param( 'ssss', $slug, $title, $description, $status );
        $stmt_insert->execute();
        $category_id = $mysqli->insert_id;
        $stmt_insert->close();
    }else{
        $stmt_update = $mysqli->prepare( "UPDATE $posttype SET slug = ?, name = ?, description = ?, status = ? WHERE  ID = ?" );
        $stmt_update->bind_param( 'ssssi', $slug, $title, $description, $status, $category_id );
        $stmt_update->execute();
        $stmt_update->close();
        set_site_notice( ucfirst( $posttype ) . ' updated successfully.', 'success' );
    }
    
    // uploading image
    if( isset($_FILES['image']) && $_FILES['image']['size'] > 0 ) {
        upload_images( $_FILES['image']['tmp_name'], $posttype, $category_id );
    }
    
    if( $_POST['update'] === 'add' ) {
        header( 'location: category_edit.php?action=edit&type=' . $posttype . '&id=' . $category_id );
    }
}
if( $action === 'edit' ) {
    $get_brand_stmt = $mysqli->prepare( "SELECT * FROM $posttype WHERE ID = ? LIMIT 1" );
    $get_brand_stmt->bind_param( 'i', $category_id );
    $get_brand_stmt->execute();
    $get_brand_result = $get_brand_stmt->get_result();
    if( $get_brand_result->num_rows > 0 ) {
        while( $row = $get_brand_result->fetch_assoc() ) {
            $title = $row['name'];
            $description = $row['description'];
            $status = $row['status'];
        }
    }
}

$site_title = ucfirst( $action ) . ' ' . ucfirst( $posttype );
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
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="title">Brand Name</label>
                        <input type="text" value="<?php echo $title; ?>" name="title" class="form-control" id="title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea rows="6" name="description" class="form-control" id="description"><?php echo $description; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
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
                    <strong><?php echo ucfirst( $posttype ); ?> Image</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="image-upload"><img class="image-preview" <?php
                        if( $action === 'edit' ) {
                            echo 'src="' . get_categoryimage( $category_id, $posttype ) . '"';
                            } ?>></label>
                        <input type="file" name="image" id="image-upload">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php include_once 'footer.php';