<?php
/* 
 * Form post handler
 * 
 * @package SJM
 * @author
 */

if( isset( $_POST['add-to-cart'] ) ) {
    $product_id = esc_int( $_POST['product-id'] );
    $quantity = isset( $_POST['quantity'] ) ? esc_int( $_POST['quantity'] ) : 1;
    
    $c_items = get_cart_items();
    
    $qty = $quantity;
    if( isset( $c_items[$product_id] ) ) {
        $qty = $c_items[$product_id] + $quantity;
    }
    
    $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
    $get_product_stmt->bind_param( 'i', $product_id );
    $get_product_stmt->execute();
    $get_product_result = $get_product_stmt->get_result();
    if( $get_product_result->num_rows > 0 ) {
        while( $row = $get_product_result->fetch_assoc() ) {
            $stocks = esc_int( $row['stocks'] );
            if( $qty <= $stocks ) {
                update_cart_item( $product_id, $qty );
                set_site_notice( 'Product added to cart.', 'success' );
            }else{
                set_site_notice( 'Error adding to cart. You have selected quantity which is higher than the available stocks.', 'error' );
            }
        }
    }
    $get_product_stmt->close();
}