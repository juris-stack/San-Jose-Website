<?php
/**
 * The functions
 * 
 * @package SJM
 * @author
 */

// Include the main functions
require_once 'main.php';

$cart_items = [];

/**
 * Lets get the cart items from cookie if ever the session expires
 * This will keep the cart items when the user leaves and come back soon
 */
if( ! user_is_loggedin() && ! empty( $_COOKIE['cart-items'] ) && empty( $_SESSION['cart-items'] ) ) {
    $_SESSION['cart-items'] = $_COOKIE['cart-items'];
}

function is_home() {
    $fname = get_currenturl_filename();
    $exp = explode( '.php', $fname );
    if( ( count( $exp ) > 1 && get_currenturl_filename() === 'index.php' ) || count( $exp ) <= 1 ) {
        return true;
    }
    return false;
}

function update_cart_item( $product, $value ) {
    $new_cart_items = get_cart_items();
    $new_cart_items[$product] = $value;
    update_cart_items( $new_cart_items );
}

function update_cart_items( $new_items ) {
    global $cart_items;
    
    $serialize_items = serialize( $new_items );
    $_SESSION['cart-items'] = $serialize_items;
    setcookie( 'cart-items', $serialize_items, time() +2592000, '/' );
    $cart_items = $new_items;
    if( user_is_loggedin() ) {
        update_currentusermeta( 'cart-items', $serialize_items );
    }
}

function get_cart_items() {
    global $cart_items;
    
    if( empty( $cart_items ) ) {
        if( user_is_loggedin() && ! empty( get_currentusermeta( 'cart-items' ) ) ) {
            return unserialize( get_currentusermeta( 'cart-items' ) );
        }elseif( isset( $_SESSION['cart-items'] ) && ! empty( $_SESSION['cart-items'] ) ) {
            return unserialize( $_SESSION['cart-items'] );
        }elseif( isset( $_COOKIE['cart-items'] ) && ! empty( $_COOKIE['cart-items'] ) ) {
            return unserialize( $_COOKIE['cart-items'] );
        }else{
            return [];
        }
    }
    return $cart_items;
}

function remove_cart_items() {
    global $cart_items;
    
    $cart_items = [];
    update_currentusermeta( 'cart-items', serialize( $cart_items ) );
    if( isset( $_SESSION['cart-items'] ) ) {
        unset( $_SESSION['cart-items'] );
    }
    if( isset( $_COOKIE['cart-items'] ) ) {
        setcookie( 'cart-items', serialize( $cart_items ), time() +2592000, '/' );
    }
}

function ajax_start_chat() {
    global $mysqli;
    
    $time = time();
    $text = esc_str( $_POST['text'] );
    $name = esc_str( $_POST['name'] );
    $email = esc_email( $_POST['email'] );
    $name_slug = esc_slug( $name );
    $session = "$name_slug-$time";
    $file = "uploads/chat/$name_slug-$time.html";
     
    $fp = fopen( $file, 'a' );
    $message = '<div class="chat-message-line system-message"><span class="message-date">(' . date( 'g:i A' ) . ')</span> <span class="message-user">System</span>: Please standby, one of our agent will reply to you shortly. Please dont close the browser or move to other page.</div>';
    $message .= '<div class="chat-message-line user-message"><span class="message-date">(' . date( 'g:i A' ) . ')</span> <span class="message-user">' . ucfirst( $name ) . '</span>: <span class="message">' . stripslashes( $text ) . '</span></div>';
    fwrite( $fp, $message );
    fclose( $fp );
    
    $stmt_insert = $mysqli->prepare( "INSERT INTO support (session, from_name, from_email) VALUES (?, ?, ?)" );
    $stmt_insert->bind_param( 'sss', $session, $name, $email );
    $stmt_insert->execute();
    $stmt_insert->close();
    
    echo json_encode( $file );
    die();
}

function ajax_chat_send_message() {
    $text = esc_str( $_POST['text'] );
    $name = esc_str( $_POST['name'] );
    $file = $_POST['file'];
    $fp = fopen( $file, 'a' );
    fwrite( $fp, '<div class="chat-message-line user-message"><span class="message-date">(' . date( 'g:i A' ) . ')</span> <span class="message-user">' . ucfirst( $name ) . '</span>: <span class="message">' . stripslashes( $text ) . '</span></div>' );
    fclose( $fp );
}

function ajax_chat_end() {
    global $mysqli;
    
    $name = esc_str( $_POST['name'] );
    $file = $_POST['file'];
    $fp = fopen( $file, 'a' );
    fwrite( $fp, '<div class="chat-message-line ended-message"><span class="message-date">(' . date( 'g:i A' ) . ')</span> <span class="message-user">System</span>: <span class="message">' . ucfirst( $name ) . ' has ended this chat session</span></div>' );
    fclose( $fp );
    
    $session = str_replace( '.html', '', $file );
    $session = str_replace( 'uploads/chat/', '', $session );
    $status = 'closed';
    
    $stmt_update = $mysqli->prepare( "UPDATE support SET status = ? WHERE session = ?" );
    $stmt_update->bind_param( 'ss', $status, $session );
    $stmt_update->execute();
    $stmt_update->close();
}

require_once 'post.php';