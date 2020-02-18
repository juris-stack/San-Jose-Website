<?php
/* 
 * user add and edit template
 * 
 * @package SJM
 * @author 
 */

// include the admin functions
require_once 'functions.php';

$action = !empty( $_GET['action'] ) ? $_GET['action'] : '';
$uid = !empty( $_GET['id'] ) ? esc_int( $_GET['id'] ) : $user_id;

if( ( $action === 'add' || $action === 'delete' ) && ! currentuser_is_admin() ) {
    die( 'You are unauthorized to access this part of our website!' );
}

if( $action === 'delete' ) {
    $stmt_delete = $mysqli->prepare( "DELETE FROM users WHERE ID = ?" );
    $stmt_delete->bind_param( 'i', $uid );
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect( 'users.php' );
}

$username = '';
$email = '';
$password = '';
$role = '';
$insert_id = '';
$active = '1';

if( empty( $action ) || $action === 'edit' ) {
    $user_stmt = $mysqli->prepare( "SELECT * FROM users WHERE ID = ? LIMIT 1" );
    $user_stmt->bind_param( 'i', $uid );
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    if( $user_result->num_rows > 0 ) {
        while( $row = $user_result->fetch_assoc() ) {
            $username = $row['username'];
            $email = $row['email'];
            $role = $row['role'];
            $active = $row['active'];
        }
    }
    $user_stmt->close();
}

if( isset( $_GET['block'] ) ) {
    $active = '0';
    update_userinfo( 'active', $active, $uid );
    set_site_notice( 'User was blocked. You can change active status anytime.', 'success' );
}

if( isset( $_GET['unblock'] ) ) {
    $active = '1';
    update_userinfo( 'active', $active, $uid );
    set_site_notice( 'User was unblocked successfully.', 'success' );
}

if( isset( $_POST['add-user'] ) ) {
    $username = esc_str( $_POST['username'] );
    $email = esc_email( $_POST['email'] );
    
    // Lets check if username exists
    $check_user_sql = $mysqli->prepare( "SELECT * FROM users WHERE username = ? LIMIT 1" );
    $check_user_sql->bind_param( 's', $username );
    $check_user_sql->execute();
    $check_user_result = $check_user_sql->get_result();
    if( $check_user_result->num_rows > 0 ) {
        set_site_notice( 'Username already exists.', 'error' );
    }
    
    // Check if email already exists
    $check_email_sql = $mysqli->prepare( "SELECT * FROM users WHERE email = ? LIMIT 1" );
    $check_email_sql->bind_param( 's', $email );
    $check_email_sql->execute();
    $check_email_result = $check_email_sql->get_result();
    if( $check_email_result->num_rows > 0 ) {
        set_site_notice( 'Email address already registered.', 'error' );
    }
    
    // If user does not exist, we will insert into the database
    if( $check_user_result->num_rows <= 0 && $check_email_result->num_rows <= 0 ) {
        $password = esc_raw( $_POST['password'] );
        $md5 = md5( $password );
        $role = esc_int( $_POST['role'] );
        $active = '1';
        
        $insert_user = $mysqli->prepare( "INSERT INTO users (username, email, password, role, active) VALUES (?, ?, ?, ?, ?)" );
        $insert_user->bind_param( 'sssis', $username, $email, $md5, $role, $active );
        $insert_user->execute();
        $insert_id = $mysqli->insert_id;
        $insert_user->close();
        header( 'location: user.php?action=edit&id=' . $insert_id );
        exit;
        
    }
    $check_user_sql->close();
    $check_email_sql->close();
}

if( isset( $_POST['update-personal-info'] ) ) {
    /** Update email address */
    $new_email = esc_email( $_POST['email'] );
    $stmt_select = $mysqli->prepare( "SELECT * FROM users WHERE email = ?" );
    $stmt_select->bind_param( 's', $new_email );
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if( $email !== $new_email ) {
       if( $result->num_rows > 0 ) {
            set_site_notice( 'Email address already exists. Please choose something else', 'error' );

        }else{
            $email = $new_email;
            update_userinfo( 'email', $new_email, $uid );
            set_site_notice( 'Email updated successfully.', 'success' );
        } 
    }
    
    /** Update password */
    $password1 = esc_raw( $_POST['password'] );
    $password2 = esc_raw( $_POST['password2'] );
    if( ! empty( $password1 ) && ! empty( $password2 ) ) {
        if( $password1 === $password2 ) {
            update_userinfo( 'password', md5( $password1 ), $uid );
            set_site_notice( 'Password updated successfully.', 'success' );
        }else{
            set_site_notice( 'Passwords did not matched.', 'error' );
        }
    }
    
    /** Update role */
    if( isset( $_POST['role'] ) && $role !== esc_int( $_POST['role'] ) ) {
        $role = esc_int( $_POST['role'] );
        update_userinfo( 'role', $role, $uid );
        set_site_notice( 'Role updated successfully.', 'success' );
    }
    
    /** Update status */
    if( isset( $_POST['active'] ) && $active !== esc_str( $_POST['active'] ) ) {
        $active = esc_str( $_POST['active'] );
        update_userinfo( 'active', $active, $uid );
        set_site_notice( 'Status updated successfully.', 'success' );
    }
    
    // uploading image
    if( isset($_FILES['image']) && $_FILES['image']['size'] > 0 ) {
        upload_images( $_FILES['image']['tmp_name'], 'user', $uid );
    }
}

if( isset( $_POST['update-usermeta'] ) ) {
    // Update biling information
    update_usermeta( 'billing-firstname', $_POST['billing-firstname'], $uid );
    update_usermeta( 'billing-lastname', $_POST['billing-lastname'], $uid );
    update_usermeta( 'billing-telephone', $_POST['billing-telephone'], $uid );
    update_usermeta( 'billing-address', $_POST['billing-address'], $uid );
    update_usermeta( 'billing-city', $_POST['billing-city'], $uid );
    update_usermeta( 'billing-state', $_POST['billing-state'], $uid );
    update_usermeta( 'billing-zip', $_POST['billing-zip'], $uid );

    // Update shipping information
    update_usermeta( 'shipping-firstname', $_POST['shipping-firstname'], $uid );
    update_usermeta( 'shipping-lastname', $_POST['shipping-lastname'], $uid );
    update_usermeta( 'shipping-telephone', $_POST['shipping-telephone'], $uid );
    update_usermeta( 'shipping-address', $_POST['shipping-address'], $uid );
    update_usermeta( 'shipping-city', $_POST['shipping-city'], $uid );
    update_usermeta( 'shipping-state', $_POST['shipping-state'], $uid );
    update_usermeta( 'shipping-zip', $_POST['shipping-zip'], $uid );
    
    set_site_notice( 'Billing / Shipping information updated successfully.', 'success' );
}

$site_title = 'Add User';
switch ( $action ) {
    case 'edit' :
    case 'add' :
        $site_title = ucfirst( $action ) . ' User';
        break;
    default :
        $site_title = 'User Account';
}
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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>Personal Information</strong>
                </div>
                <div class="card-body card-block">
                    <?php if( $action !== 'add' ) : ?>
                    <div class="form-group">
                        <label for="image-upload"><img class="image-small" <?php echo 'src="' . get_userimage( $uid, 'small' ) . '"'; ?>></label>
                        <input type="file" name="image" id="image-upload">
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="username" class=" form-control-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>"<?php
                        if( $action === 'edit' || empty( $action ) ) {
                            echo ' disabled=""';
                        }
                        ?>>
                        <?php if( $action !== 'add' ) : ?>
                        <small class="help-block form-text">Username cannot be changed</small>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email" class=" form-control-label">Email Address</label>
                        <input name="email" type="email" id="email" class="form-control" value="<?php echo $email; ?>">
                    </div>
                    <?php if( ( currentuser_is_admin() && $action === 'edit' && $uid !== get_currentuser( 'ID' ) ) || ( currentuser_is_admin() && $action === 'add' ) ) : ?>
                    <div class="form-group">
                        <label for="role" class=" form-control-label">User Role</label>
                        <select name="role" id="role" class="form-control">
                            <?php 
                            $roles_arr = array(
                                1 => 'Customer',
                                2 => 'Staff',
                                3 => 'Administrator');
                            foreach ( $roles_arr as $user_role => $role_desc ) {
                                echo '<option value="' . $user_role . '" ' . selected( $user_role, $role, false ) . '>' . $role_desc . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="active" class=" form-control-label">Status</label>
                        <select name="active" id="active" class="form-control">
                            <?php 
                            $active_arr = array(
                                '1' => 'Active',
                                '0' => 'Inactive'
                            );
                            foreach ( $active_arr as $active_key => $active_desc ) {
                                echo '<option value="' . $active_key . '" ' . selected( $active_key, esc_int( $active ), false ) . '>' . $active_desc . '</option>';
                            } ?>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="password" class=" form-control-label">Password</label>
                        <?php if( $action === 'add' ) : ?>
                        <input name="password" type="text" id="password" class="form-control" value="<?php echo $password; ?>">
                        <?php else : ?>
                        <input name="password" type="password" id="password" class="form-control" value="<?php echo $password; ?>">
                        <?php endif; ?>
                        <?php if( $action !== 'add' ) : ?>
                        <small class="help-block form-text">Leave password field empty to keep current password</small>
                        <?php endif; ?>
                    </div>
                    <?php if( $action === 'edit' || empty( $action ) ) : ?>
                    <div class="form-group">
                        <label for="password2" class=" form-control-label">Confirm Password</label>
                        <input name="password2" type="password" id="password2" class="form-control">
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <?php if( $action === 'add' ) : ?>
                    <button type="submit" class="btn btn-primary btn-md">Add User</button>
                    <input type="hidden" name="add-user" value="1">
                    <?php elseif( $action === 'edit' || empty( $action ) ) : ?>
                    <button type="submit" class="btn btn-primary btn-md">Save Changes</button>
                    <input type="hidden" name="update-personal-info" value="1">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
if( empty( $action ) || $action === 'edit' ) :
$current_usermetas = array(
    'billing-firstname' => '',
    'billing-lastname' => '',
    'billing-telephone' => '',
    'billing-address' => '',
    'billing-city' => '',
    'billing-state' => '',
    'billing-zip' => '',
    'shipping-firstname' => '',
    'shipping-lastname' => '',
    'shipping-telephone' => '',
    'shipping-address' => '',
    'shipping-city' => '',
    'shipping-state' => '',
    'shipping-zip' => ''
);
$get_usermetas = $mysqli->prepare( "SELECT * FROM user_meta WHERE user_ID = ?" );
$get_usermetas->bind_param( 'i', $uid );
$get_usermetas->execute();
$get_usermetas_result = $get_usermetas->get_result();
if( $get_usermetas_result->num_rows > 0 ) {
    while( $row = $get_usermetas_result->fetch_assoc() ) {
        $current_usermetas[$row['meta_key']] = $row['meta_value'];
    }
} ?>
<form method="POST" action="">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>Billing Information</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col">
                                <label for="billing-firstname">First Name</label>
                                <input type="text" value="<?php echo $current_usermetas['billing-firstname']; ?>" class="form-control" id="billing-firstname" name="billing-firstname">
                            </div>
                            <div class="col">
                                <label for="billing-lastname">Last Name</label>
                                <input type="text" value="<?php echo $current_usermetas['billing-lastname']; ?>" class="form-control" id="billing-lastname" name="billing-lastname">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="billing-telephone">Telephone or Mobile Number</label>
                        <input type="text" value="<?php echo $current_usermetas['billing-telephone']; ?>" name="billing-telephone" class="form-control" id="billing-telephone" required="" pattern="[0-9]{11}" title="PLEASE PUT 11-DIGIT NUMBER WITH COUNTRY CODE" onKeyDown="if(this.value.length==11 && event.keyCode!=8) return false;">
                    </div>
                    <div class="form-group">
                        <label for="billing-address">House no., Purok, Sitio, Barangay</label>
                        <input type="text" value="<?php echo $current_usermetas['billing-address']; ?>" name="billing-address" class="form-control" id="billing-address">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="billing-city">City or Municipality</label>
                            <input type="text" value="<?php echo $current_usermetas['billing-city']; ?>" name="billing-city" class="form-control" id="billing-city">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="billing-state">State or Province</label>
                            <input type="text" value="<?php echo $current_usermetas['billing-state']; ?>" name="billing-state" class="form-control" id="billing-state">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="billing-zip">Zip Code</label>
                            <input name="billing-zip" value="<?php echo $current_usermetas['billing-zip']; ?>" type="text" class="form-control" id="billing-zip">
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <strong>Shipping Information</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col">
                                <label for="shipping-firstname">First Name</label>
                                <input type="text" value="<?php echo $current_usermetas['shipping-firstname']; ?>" class="form-control" id="shipping-firstname" name="shipping-firstname">
                            </div>
                            <div class="col">
                                <label for="shipping-lastname">Last Name</label>
                                <input type="text" value="<?php echo $current_usermetas['shipping-lastname']; ?>" class="form-control" id="shipping-lastname" name="shipping-lastname">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shipping-telephone">Telephone or Mobile Number</label>
                        <input type="text" value="<?php echo $current_usermetas['shipping-telephone']; ?>" name="shipping-telephone" class="form-control" id="shipping-telephone" required="" pattern="[0-9]{11}" title="PLEASE PUT 11-DIGIT NUMBER WITH COUNTRY CODE" onKeyDown="if(this.value.length==11 && event.keyCode!=8) return false;">
                    </div>
                    <div class="form-group">
                        <label for="shipping-address">House no., Purok, Sitio, Barangay</label>
                        <input type="text" value="<?php echo $current_usermetas['shipping-address']; ?>" name="shipping-address" class="form-control" id="shipping-address">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="shipping-city">City or Municipality</label>
                            <input type="text" value="<?php echo $current_usermetas['shipping-city']; ?>" name="shipping-city" class="form-control" id="shipping-city">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="shipping-state">State or Province</label>
                            <input type="text" value="<?php echo $current_usermetas['shipping-state']; ?>" name="shipping-state" class="form-control" id="shipping-state">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="shipping-zip">Zip Code</label>
                            <input name="shipping-zip" value="<?php echo $current_usermetas['shipping-zip']; ?>" type="text" class="form-control" id="shipping-zip">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-md">Save Changes</button>
                    <input type="hidden" name="update-usermeta" value="1">
                </div>
            </div>
        </div>
    </div>
</form>
<?php endif; ?>

<?php include_once 'footer.php';