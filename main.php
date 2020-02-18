<?php
/**
 * Main functions
 * 
 * @package SJM
 * @author 
 */

// start the session first thing first
session_start();

date_default_timezone_set( 'Asia/Manila' ); 

// Initialize global variables used sitewide
$page_title = '';
$site_title = '';
$notices = [];
$userinfo = [];
$current_userinfo = [];
$usermeta = [];
$current_usermeta = [];
$user_id = 0;
$verion = '1.01a';
$siteinfo = [];

// Include the config file
require_once 'config.php';

$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
if( $mysqli->connect_errno ) {
    die( 'Error connecting to database!' );
}

function user_is_loggedin() {
    if( ! empty( $_SESSION['uid'] ) || ! empty( $_COOKIE['uid'] ) ) {
        return true;
    }
    return false;
}

if( user_is_loggedin() ) {
    if( ! empty( $_COOKIE['uid'] ) && empty( $_SESSION['uid'] ) ) {
        $_SESSION['uid'] = $_COOKIE['uid'];
    }
    $user_id = $_SESSION['uid'];
}

function set_site_notice( $message, $code = 'success' ) {
    global $notices;
    
    if( ! empty( $message ) ) {
        $notices[] = array(
            'message' => $message,
            'code' => $code
        );
    }
}

function show_site_notice() {
    global $notices;
    
    if( is_array( $notices ) && count( $notices ) > 0 ) {
        echo '<div id="notice-group">';
        foreach( $notices as $notice ) {
            echo '<div class="site-notice ' . $notice['code'] . '">';
            echo '<p>' . $notice['message'] . '</p>';
            echo '</div>';
        }
        echo '</div>';
    }
}

function get_currentuser( $field = 'ID' ) {
    global $current_userinfo, $mysqli, $user_id;
    
    if( empty( $current_userinfo ) ) {
        $current_user_smtp = $mysqli->prepare( "SELECT * FROM users WHERE ID = ? LIMIT 1" );
        $current_user_smtp->bind_param( 'i', $user_id );
        $current_user_smtp->execute();
        $current_user_result = $current_user_smtp->get_result();
        if( $current_user_result->num_rows > 0 ) {
            while( $row = $current_user_result->fetch_assoc() ) {
                $current_userinfo['ID'] = $user_id;
                $current_userinfo['username'] = $row['username'];
                $current_userinfo['email'] = $row['email'];
                $current_userinfo['role'] = $row['role'];
                $current_userinfo['active'] = $row['active'];
                $current_userinfo['status'] = $row['status'];
                $current_userinfo['reg_date'] = $row['reg_date'];
                $current_userinfo['auth'] = $row['auth'];
            }
        }
        $current_user_smtp->close();
    }
    return isset( $current_userinfo[$field] ) ? $current_userinfo[$field] : '';
}

function update_usermeta( $key, $value, $uid ) {
    global $mysqli;
    
    $esc_value = $mysqli->real_escape_string( $value );
    $stmt_select = $mysqli->prepare( "SELECT * FROM user_meta WHERE meta_key = ? AND user_ID = ?" );
    $stmt_select->bind_param( 'si', $key, $uid );
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if( $result->num_rows > 0 ) {
        $stmt_update = $mysqli->prepare( "UPDATE user_meta SET meta_value = ? WHERE meta_key = ? AND user_ID = ?" );
        $stmt_update->bind_param( 'ssi', $esc_value, $key, $uid );
        $stmt_update->execute();
        $stmt_update->close();
    }else{
        $stmt_insert = $mysqli->prepare( "INSERT INTO user_meta (meta_key, meta_value, user_ID) VALUES (?, ?, ?)" );
        $stmt_insert->bind_param( 'ssi', $key, $esc_value, $uid );
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    $stmt_select->close();
    return true;
}

function update_currentusermeta( $key, $value ) {
    global $user_id, $current_usermeta;
    
    update_usermeta( $key, $value, $user_id );
    if( !empty( $current_usermeta ) ) {
        $current_usermeta[$key] = esc_str( $value );
    }
}

function get_currentusermeta( $key ) {
    global $current_usermeta, $mysqli, $user_id;
    
    if( empty( $current_usermeta ) ) {
        $current_user_smtp = $mysqli->prepare( "SELECT * FROM user_meta WHERE user_ID = ?" );
        $current_user_smtp->bind_param( 'i', $user_id );
        $current_user_smtp->execute();
        $current_user_result = $current_user_smtp->get_result();
        if( $current_user_result->num_rows > 0 ) {
            while( $row = $current_user_result->fetch_assoc() ) {
                $current_usermeta[$row['meta_key']] = $row['meta_value'];
            }
        }
        $current_user_smtp->close();
    }
    
    return isset( $current_usermeta[$key] ) ? $current_usermeta[$key] : '';
}

function update_userinfo( $field, $value, $uid ) {
    global $mysqli;
    
    $esc_value = $mysqli->real_escape_string( $value );
    $stmt = $mysqli->prepare( "UPDATE users SET $field = ? WHERE ID = ?" );
    $stmt->bind_param( 'si', $esc_value, $uid );
    $stmt->execute();
    $stmt->close();
    return true;
}

function update_currentuserinfo( $field, $value ) {
    global $user_id, $current_userinfo;
    
    update_userinfo( $field, $value, $user_id );
    if( !empty( $current_userinfo ) ) {
        $current_userinfo[$field] = esc_str( $value );
    }
}

function get_userby( $field, $uid ) {
    global $mysqli;
    
    $select_stmt = $mysqli->prepare( "SELECT * FROM users WHERE ID = ?" );
    $select_stmt->bind_param( 'i', $uid );
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            return $row[$field];
        }
    }
    $select_stmt->close();
    return false;
}

function get_siteinfo( $key ) {
    global $siteinfo, $mysqli;
    
    if( empty( $siteinfo ) ) {
        $siteinfo_stmt = $mysqli->prepare( "SELECT * FROM options" );
        $siteinfo_stmt->execute();
        $siteinfo_result = $siteinfo_stmt->get_result();
        if( $siteinfo_result->num_rows > 0 ) {
            while( $row = $siteinfo_result->fetch_assoc() ) {
                $siteinfo[$row['option_key']] = $row['option_value'];
            }
        }
    }
    return isset( $siteinfo[$key] ) ? $siteinfo[$key] : '';
}

function update_siteinfo( $key, $value ) {
    global $mysqli, $siteinfo;
    
    $esc_value = $mysqli->real_escape_string( $value );
    $stmt_select = $mysqli->prepare( "SELECT * FROM options WHERE option_key = ?" );
    $stmt_select->bind_param( 's', $key );
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if( $result->num_rows > 0 ) {
        $stmt_update = $mysqli->prepare( "UPDATE options SET option_value = ? WHERE option_key = ?" );
        $stmt_update->bind_param( 'ss', $esc_value, $key );
        $stmt_update->execute();
        $stmt_update->close();
    }else{
        $stmt_insert = $mysqli->prepare( "INSERT INTO options (option_key, option_value) VALUES (?, ?)" );
        $stmt_insert->bind_param( 'ss', $key, $esc_value );
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    if( !empty( $siteinfo ) ) {
        $siteinfo[$key] = $esc_value;
    }
    $stmt_select->close();
    return true;
}

function site_url( $path = '' ) {
    return get_siteinfo( 'site-url' ) . $path;
}

function site_dir( $path = '' ) {
    return dirname( __FILE__ ) . $path;
}

function esc_raw( $str ) {
    global $mysqli;
    return ! empty( $str ) ? $mysqli->real_escape_string( $str ) : '';
}

function esc_str( $str ) {
    global $mysqli;
    return ! empty( $str ) ? strip_tags( $mysqli->real_escape_string( $str ) ) : '';
}

function esc_email( $str ) {
    return filter_var( trim( $str ), FILTER_SANITIZE_EMAIL );
}

function esc_textarea( $str ) {
    global $mysqli;
    return ! empty( $str ) ? stripcslashes( $mysqli->real_escape_string( $str ) ) : '';
}

function esc_html( $str ) {
    global $mysqli;
    return ! empty( $str ) ? htmlentities( $mysqli->real_escape_string( $str ) ) : '';
}

function esc_url( $url ) {
    return urlencode( $url );
}

function esc_int( $str ) {
    return (int) $str;
}

function esc_float( $str ) {
    return floatval( $str );
}

function esc_slug( $str, $limit=64 ){
    $replacements = array(
      'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 
      'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 
      'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 
      'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 
      'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 
      'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 
      'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', '&'=>'and'
    );

    $str = strtr( $str, $replacements ); // Replace accented/special characters
    $str = preg_replace( '/\s+/', '-', trim( $str ) ); // Trim and remove spaces
    $str = str_replace( '_', '-', $str ); // Underscores to dashes
    $str = preg_replace( '/[^a-z0-9-]/i', '', strtolower( $str ) ); // Only alpha-numeric and dashes are permitted
    $str = preg_replace( '/-+/', '-', $str ); // Prevent 2+ dashes from appearing together

    // Limit the number of characters
    if( intval( $limit ) > 0 ){
        $str = substr( $str, 0, intval( $limit ) );
    }

    // Don't end in a dash
    if( substr( $str, -1, 1 ) === '-' ){
        $str = substr( $str, 0, -1 );
    }

    return $str;
}

function get_currenturl() {
    return ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function get_currenturl_filename() {
    return basename( $_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING'] );
}

function checked( $match, $val, $echo = true ) {
    if( ! $echo ) {
        return ( $match === $val ) ? 'checked' : '';
    }
    echo ( $match === $val ) ? 'checked' : '';
}

function selected( $match, $val, $echo = true ) {
    if( ! $echo ) {
        return ( $match === $val ) ? 'selected' : '';
    }
    echo ( $match === $val ) ? 'selected' : '';
}

function add_query_arg( $arg, $val, $url ) {
    $exp_url = explode( '?', $url );
    if( count( $exp_url ) > 1 ) {
        return $url . "&$arg=$val";
    }
    return $url . "?$arg=$val";
}

function resize_crop_image( $max_width, $max_height, $source_file, $dst_dir, $quality = 80 ){
    $imgsize = getimagesize( $source_file );
    $width = $imgsize[0];
    $height = $imgsize[1];
    $mime = $imgsize['mime'];

    switch($mime){
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 80;
            break;

        default:
            return false;
            break;
    }

    $dst_img = imagecreatetruecolor( $max_width, $max_height );
    $src_img = $image_create( $source_file );

    $width_new = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
    if( $width_new > $width ){
        //cut point by height
        $h_point = (($height - $height_new) / 2);
        //copy image
        imagecopyresampled( $dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new );
    }else{
        //cut point by width
        $w_point = ( ( $width - $width_new ) / 2 );
        imagecopyresampled( $dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height );
    }

    $image($dst_img, $dst_dir, $quality);

    if( $dst_img ) imagedestroy( $dst_img );
    if( $src_img ) imagedestroy( $src_img );
}

function upload_images( $source_file, $dir, $uid ) {
    resize_crop_image( 500, 500, $source_file, site_dir( '/uploads/' . $dir . '/' . $uid . '.jpg' ) );
    resize_crop_image( 300, 300, $source_file, site_dir( '/uploads/' . $dir . '/' . $uid . '-medium.jpg' ) );
    resize_crop_image( 150, 150, $source_file, site_dir( '/uploads/' . $dir . '/' . $uid . '-thumbnail.jpg' ) );
    resize_crop_image( 60, 60, $source_file, site_dir( '/uploads/' . $dir . '/' . $uid . '-small.jpg' ) );
}

function get_userimage( $uid, $size = '' ) {
    $user_image_url = site_url( '/uploads/user/' . $uid );
    $user_image_path = site_dir( '/uploads/user/' . $uid . '.jpg' );
    if( file_exists( $user_image_path ) ) {
        $user_image_url .= empty( $size ) ? '' : '-' . $size;
        $user_image_url .= '.jpg';
        return $user_image_url . '?mt=' . filemtime( $user_image_path );
    }
    return site_url( '/assets/images/person.png' );
}

function get_productimage( $product_id, $size = '' ) {
    $product_image_url = site_url( '/uploads/product/' . $product_id );
    $product_image_path = site_dir( '/uploads/product/' . $product_id . '.jpg' );
    if( file_exists( $product_image_path ) ) {
        $product_image_url .= empty( $size ) ? '' : '-' . $size;
        $product_image_url .= '.jpg';
        return $product_image_url . '?mt=' . filemtime( $product_image_path );
    }
    return site_url( '/assets/images/placeholder.png' );
}

function get_categoryimage( $brand_id, $posttype = 'category', $size = '' ) {
    $brand_image_url = site_url( '/uploads/' . $posttype . '/' . $brand_id );
    $brand_image_path = site_dir( '/uploads/' . $posttype . '/' . $brand_id . '.jpg' );
    if( file_exists( $brand_image_path ) ) {
        $brand_image_url .= empty( $size ) ? '' : '-' . $size;
        $brand_image_url .= '.jpg';
        return $brand_image_url . '?mt=' . filemtime( $brand_image_path );
    }
    return site_url( '/assets/images/placeholder.png' );
}

function redirect( $url ) {
    header( 'location: ' . $url );
    exit;
}

function update_product( $key, $value, $id ) {
    global $mysqli;
    
    $stmt_update = $mysqli->prepare( "UPDATE products SET $key = ? WHERE ID = ?" );
    $stmt_update->bind_param( 'si', $value, $id );
    $stmt_update->execute();
    $stmt_update->close();
}

function get_productby( $key, $id ) {
    global $mysqli;
    
    $stmt_select = $mysqli->prepare( "SELECT $key FROM products WHERE ID = ? LIMIT 1" );
    $stmt_select->bind_param( 'i', $id );
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if( $result->num_rows > 0 ) {
        while( $row = $result->fetch_assoc() ) {
            return $row[$key];
        }
    }
    return false;
}

function get_product( $id ) {
    global $mysqli;
    
    $stmt_select = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
    $stmt_select->bind_param( 'i', $id );
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if( $result->num_rows > 0 ) {
        return $result->fetch_assoc();
    }
    return false;
}

function time_ago( $datetime, $full = false ) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function currentuser_is_admin() {
    $role = esc_int( get_currentuser( 'role' ) );
    if( $role === 3 ) {
        return true;
    }
    return false;
}

function currentuser_is_staff() {
    $role = esc_int( get_currentuser( 'role' ) );
    if( $role === 2 ) {
        return true;
    }
}

function currentuser_is_customer() {
    $role = esc_int( get_currentuser( 'role' ) );
    if( $role === 1 ) {
        return true;
    }
}

function send_mail( $to, $subject, $mssg, $from = '' ) {
    if( empty( $from ) ) {
        $from = get_siteinfo( 'company-email' );
    }
    $message = '';
    $message .= $mssg;
    $headers = "From: $from" . "\r\n";
    mail( $to, $subject, $message, $headers );
}

function push_notification( $id, $type = 'order', $mssg ) {
    global $mysqli;
    
    $stmt_insert = $mysqli->prepare( "INSERT INTO notifications (message, type, rel_ID) VALUES (?, ?, ?)" );
    $stmt_insert->bind_param( 'ssi', $mssg, $type, $id );
    $stmt_insert->execute();
    $stmt_insert->close();
}
