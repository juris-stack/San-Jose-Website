<?php
/**
 * Admin function
 * 
 * @package SJM
 * @author 
 */

// include the main functions
require_once '../main.php';

function ajax_chat_send_message() {
    $text = esc_str( $_POST['text'] );
    $name = get_currentuser( 'username' );
    $file = $_POST['file'];
    $fp = fopen( $file, 'a' );
    fwrite( $fp, '<div class="chat-message-line operator-message"><span class="message-date">(' . date( 'g:i A' ) . ')</span> <span class="message-user">' . ucfirst( $name ) . '</span>: <span class="message">' . stripslashes( $text ) . '</span></div>' );
    fclose( $fp );
}

function ajax_get_chat_queue() {
    global $mysqli;
    
    $output = '';
    $select_stmt = $mysqli->prepare( "SELECT * FROM support WHERE status = 'open' ORDER BY date_added DESC" );
    $select_stmt->execute();
    $stmt_result = $select_stmt->get_result();
    $notif_count = $stmt_result->num_rows;
    $i = 0;
    if( $notif_count > 0 ) {
        while( $row = $stmt_result->fetch_assoc() ) {
            if( $i >= 5 ) {
                break;
            }
            $output .= '<div class="notifi__item">';
            $output .= '<div class="bg-c1 img-cir img-40">';
            $output .= '<i class="zmdi zmdi-comment-text"></i>';
            $output .= '</div>';
            $output .= '<a class="content" href="chat.php?id=' . $row['ID'] . '">';
            $output .= '<p>New chat on queue</p>';
            //$output .= '<p>' . $row['from_email'] . '</p>';
            $output .= '<span class="date">' . ucfirst( $row['from_name'] ) . ', ' . time_ago( $row['date_added'] ) . '</span>';
            $output .= '</a>';
            $output .= '</div>';
            $i++;
        }
    }
    $select_stmt->close();
    
    echo json_encode( array( $output, $notif_count ) );
    die();
}

function ajax_clear_notifications() {
    global $mysqli;
    
    $stmt_update = $mysqli->prepare( "UPDATE notifications SET status = 'read' WHERE status = 'unread'" );
    $stmt_update->execute();
    $stmt_update->close();
}

function ajax_search_products() {
    global $mysqli;
    
    $html = '';
    $search = '%' . esc_str( $_POST['search'] ) . '%';
    if( ! empty( $search ) ) {
        $select_stmt = $mysqli->prepare( "SELECT * FROM products WHERE name LIKE ? ORDER BY name ASC" );
        $select_stmt->bind_param( 's', $search );
    }else{
        $select_stmt = $mysqli->prepare( "SELECT * FROM products ORDER BY name ASC LIMIT 10" );
    }
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $product_id = $row['ID'];
            $html .= '<tr class="tr-shadow">';
            $html .= '<td>';
            $html .= '<a href="' . site_url( '/product.php?p=' . $row['slug'] ) . '">';
            $html .= '<img class="image-small" src="' . get_productimage( $product_id, 'small' ) . '" alt="' . $row['name'] . '">';
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td><a href="' . site_url( '/product.php?p=' . $row['slug'] ) . '">' . $row['name'] . '</a></td>';
            $html .= '<td>' . $row['date_added'] . '</td>';
            $html .= '<td>' . $row['stocks'] . '</td>';
            $html .= '<td>' . ucfirst( $row['status'] ) . '</td>';
            $html .= '<td>';
            $html .= '<div class="table-data-feature">';
            $html .= '<a href="' . site_url( '/admin/product_edit.php?id=' . $product_id . '&action=edit' ) . '" class="item" data-toggle="tooltip" data-placement="top" title="Edit">';
            $html .= '<i class="zmdi zmdi-edit"></i>';
            $html .= '</a>';
            $html .= '<a href="#" class="item ajax-confirm" data-href="' . site_url( '/admin/product_edit.php?id=' . $product_id . '&action=delete' ) . '" data-toggle="tooltip" data-placement="top" title="Delete">';
            $html .= '<i class="zmdi zmdi-delete"></i>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr class="spacer"></tr>';
        }
        $html .= '<tr class="spacer"></tr>';
    }
    $select_stmt->close();
    echo json_encode( $html );
    die();
}

function ajax_search_orders() {
    global $mysqli;
    
    $html = '';
    $search = '%' . esc_str( $_POST['search'] ) . '%';
    if( ! empty( $search ) ) {
        $select_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE ID LIKE ? ORDER BY ID DESC" );
        $select_stmt->bind_param( 's', $search );
    }else{
        $select_stmt = $mysqli->prepare( "SELECT * FROM orders ORDER BY ID DESC LIMIT 10" );
    }
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $product_id = $row['ID'];
            $html .= '<tr class="tr-shadow">';
            $html .= '<td>';
            $html .= '<a href="' . site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ) . '">';
            $html .= $row['ID'];
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td>';
            if( $row['user_ID'] ) {
            $html .= '<a href="profile.php?id=' . $row['user_ID'] . '">' . get_userby( 'username', $row['user_ID'] ) . '</a>';
            };
            $html .= '</td>'; 
            $html .= '<td>&#8369; ' . $row['amount'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
            $html .= '<td>' . $row['date_added'] . '</td>';
            $html .= '<td>';
            $html .= '<div class="table-data-feature">';
            $html .= '<a href="' . site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ) . '" class="item" data-toggle="tooltip" data-placement="top" title="Edit">';
            $html .= '<i class="zmdi zmdi-edit"></i>';
            $html .= '</a>';
            $html .= '<a href="#" data-href="' . site_url( '/admin/order.php?action=delete&id=' . $row['ID'] ) . '" class="item ajax-confirm" data-confirm-text="Are you sure you want to delete this order?"data-toggle="tooltip" data-placement="top" title="Delete">';
            $html .= '<i class="zmdi zmdi-delete"></i>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr class="spacer"></tr>';
        }
        $html .= '<tr class="spacer"></tr>';
    }
    $select_stmt->close();
    echo json_encode( $html );
    die();
}

function ajax_select_order_status() {
    global $mysqli;
    
    $html = '';
    $search = esc_str( $_POST['status'] );
    if( ! empty( $search ) ) {
        $select_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status LIKE ? ORDER BY ID DESC" );
        $select_stmt->bind_param( 's', $search );
    }else{
        $select_stmt = $mysqli->prepare( "SELECT * FROM orders ORDER BY ID DESC LIMIT 10" );
    }
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $product_id = $row['ID'];
            $html .= '<tr class="tr-shadow">';
            $html .= '<td>';
            $html .= '<a href="' . site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ) . '">';
            $html .= $row['ID'];
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td>';
            if( $row['user_ID'] ) {
            $html .= '<a href="profile.php?id=' . $row['user_ID'] . '">' . get_userby( 'username', $row['user_ID'] ) . '</a>';
            };
            $html .= '</td>'; 
            $html .= '<td>&#8369; ' . $row['amount'] . '</td>';
            $html .= '<td>' . $row['status'] . '</td>';
            $html .= '<td>' . $row['date_added'] . '</td>';
            $html .= '<td>';
            $html .= '<div class="table-data-feature">';
            $html .= '<a href="' . site_url( '/admin/order.php?action=edit&id=' . $row['ID'] ) . '" class="item" data-toggle="tooltip" data-placement="top" title="Edit">';
            $html .= '<i class="zmdi zmdi-edit"></i>';
            $html .= '</a>';
            $html .= '<a href="#" data-href="' . site_url( '/admin/order.php?action=delete&id=' . $row['ID'] ) . '" class="item ajax-confirm" data-confirm-text="Are you sure you want to delete this order?"data-toggle="tooltip" data-placement="top" title="Delete">';
            $html .= '<i class="zmdi zmdi-delete"></i>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr class="spacer"></tr>';
        }
        $html .= '<tr class="spacer"></tr>';
    }
    $select_stmt->close();
    echo json_encode( $html );
    die();
}

function ajax_search_messages() {
    global $mysqli;
    
    $html = '';
    $search = '%' . esc_str( $_POST['search'] ) . '%';
    if( ! empty( $search ) ) {
        $select_stmt = $mysqli->prepare( "SELECT * FROM support WHERE from_name LIKE ? ORDER BY ID ASC" );
        $select_stmt->bind_param( 's', $search );
    }else{
        $select_stmt = $mysqli->prepare( "SELECT * FROM support ORDER BY ID ASC LIMIT 10" );
    }
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $product_id = $row['ID'];
            $html .= '<tr class="tr-shadow' . $row['status'] . '">';
            $html .= '<td>';
            $html .= '<a href="' . site_url( '/admin/chat.php?id=' . $row['ID'] ) . '">';
            $html .= $row['from_name'];
            $html .= '</a>';
            $html .= '</td>';
            $html .= '<td><a href="' . site_url( '/admin/chat.php?id=' . $row['ID'] ) . '">';
            $html .= $row['from_email'];
            $html .= '</a>';
            $html .= '</td>'; 
            $html .= '<td>' . $row['status'] . '</td>';
            $html .= '<td>' . $row['date_added'] . '</td>';
            $html .= '<td>';
            $html .= '<div class="table-data-feature">';
            $html .= '<a href="' . site_url( '/admin/chat.php?id=' . $row['ID'] ) . '" class="item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Reply">';
            $html .= '<i class="zmdi zmdi-mail-reply"></i>';
            $html .= '</a>';
            $html .= '<a href="#" data-href="' . site_url( '/admin/chat.php?action=delete&id=' . $row['ID'] ) . '" class="item ajax-confirm" data-confirm-text="Are you sure you want to delete this message?" data-toggle="tooltip" data-placement="top" title="Delete">';
            $html .= '<i class="zmdi zmdi-delete"></i>';
            $html .= '</a>';
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr class="spacer"></tr>';
        }
        $html .= '<tr class="spacer"></tr>';
    }
    $select_stmt->close();
    echo json_encode( $html );
    die();
}

function ajax_search_users() {
    global $mysqli;
    
    $html = '';
    $search = '%' . esc_str( $_POST['search'] ) . '%';
    if( ! empty( $search ) ) {
        $select_stmt = $mysqli->prepare( "SELECT * FROM users WHERE username LIKE ? ORDER BY username ASC" );
        $select_stmt->bind_param( 's', $search );
    }else{
        $select_stmt = $mysqli->prepare( "SELECT * FROM users ORDER BY username ASC LIMIT 10" );
    }
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $uid = $row['ID'];
            $role = 'Customer';
            switch( $row['role'] ) {
                case 3 :
                    $role = 'Admin';
                    break;
                case 2 :
                    $role = 'Staff';
                    break;
            }
            $html .= '<tr class="tr-shadow">';
            $html .= '<td><a href="profile.php?id=' . $uid . '"><img class="image-small" src="' . get_userimage( $uid, 'small' ) . '" alt="' . $row['username'] . '"></a></td>';
            $html .= '<td><a href="profile.php?id=' . $uid . '">' . $row['username'] . '</a></td>';
            $html .= '<td><span class="block-email">' . $row['email'] . '</span></td>';
            $html .= '<td>' . $row['reg_date'] . '</td>';
            $html .= '<td>' . $role . '</td>';
            $html .= '<td>';
            switch( $row['active'] ) {
                case 0 :
                case '0' :
                    $html .= 'Blocked';
                    break;
                case 1 :
                case '1' :
                    $html .= 'Active';
                    break;
            }
            $html .= '</td>';
            $html .= '<td>';
            $html .= '<div class="table-data-feature">';
            $html .= '<a href="' . site_url( '/admin/user.php?action=edit&id=' . $uid ) . '" class="item" data-toggle="tooltip" data-placement="top" title="Edit">';
            $html .= '<i class="zmdi zmdi-edit"></i>';
            $html .= '</a>';
            if( currentuser_is_admin() ) :
                if( esc_int( $row['active'] ) > 0 ) {
                    $html .= '<a href="#" data-href="' . site_url( '/admin/user.php?action=edit&block=1&id=' . $uid ) . '" class="item ajax-confirm" data-confirm-text="Are you sure you want to block this user?" data-toggle="tooltip" data-placement="top" title="Block">';
                    $html .= '<i class="zmdi zmdi-block"></i>';
                }else{
                    $html .= '<a href="#" data-href="' . site_url( '/admin/user.php?action=edit&unblock=1&id=' . $uid ) . '" class="item ajax-confirm" data-confirm-text="Are you sure you want to unblock this user?" data-toggle="tooltip" data-placement="top" title="Unblock">';
                    $html .= '<i class="zmdi zmdi-refresh-alt"></i>';
                }
                $html .= '</a>';
                $html .= '<a href="#" data-href="' . site_url( '/admin/user.php?action=delete&id=' . $uid ) . '" class="item ajax-confirm" data-confirm-text="Are you sure you want to delete this user?" data-toggle="tooltip" data-placement="top" title="Delete">';
                $html .= '<i class="zmdi zmdi-delete"></i>';
                $html .= '</a>';
            endif;
            $html .= '</div>';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr class="spacer"></tr>';
        }
    }
    $select_stmt->close();
    echo json_encode( $html );
    die();
}

function ajax_pos_add_item() {
    global $mysqli;
    $html = '<tr>';
    $html .= '<td width="50"><a href="#" class="item-remove"><i class="fa fa-times-circle"></i></a></td>';
    $html .= '<td><select class="item-select form-control">';
    $html .= '<option value="">Choose product...</option>';
    $select_stmt = $mysqli->prepare( "SELECT * FROM products WHERE status='published' AND stocks > 0 ORDER BY name ASC" );
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $html .= '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
        }
    }
    $select_stmt->close();
    $html .= '</select></td>';
    $html .= '<td class="item-price"></td>';
    $html .= '<td class="item-quantity"></td>';
    $html .= '<td class="item-total"></td>';
    $html .= '</tr>';
    echo json_encode( $html );
    die();
}

function ajax_pos_select_product() {
    global $mysqli;
    $html = '';
    $price = '';
    $id = esc_int( $_POST['id'] );
    $select_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ?" );
    $select_stmt->bind_param( 'i', $id );
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();
    if( $select_result->num_rows > 0 ) {
        while( $row = $select_result->fetch_assoc() ) {
            $price = $row['price'];
            $html .= '<select class="form-control quantity-select">';
            for( $i=1; $i <= esc_int( $row['stocks'] ); $i++ ) {
                $html .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $html .= '</select>';
        }
    }
    echo json_encode( array( $price, $html, $price ) );
    die();
}

