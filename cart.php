<?php
/**
 * Cart template
 * 
 * @package SJM
 * @author 
 */
require_once 'functions.php';

if( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
    $pid = esc_int( $_GET['id'] );
    $new_cart_items = get_cart_items();
    if( isset( $new_cart_items[$pid] ) ) {
        unset( $new_cart_items[$pid] );
        update_cart_items( $new_cart_items );
        set_site_notice( 'Your cart has been updated.', 'success' );
    }else{
        redirect( 'cart.php' );
    }
}

if( isset( $_POST['update-cart'] ) ) {
    $pids = unserialize( $_POST['product-ids'] );
    foreach ( $pids as $pid ) {
        $qty = esc_int( $_POST['quantity-' . $pid] );
        $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
        $get_product_stmt->bind_param( 'i', $pid );
        $get_product_stmt->execute();
        $get_product_result = $get_product_stmt->get_result();
        if( $get_product_result->num_rows > 0 ) {
            while( $row = $get_product_result->fetch_assoc() ) {
                $stocks = esc_int( $row['stocks'] );
                if( $qty <= $stocks ) {
                    update_cart_item( $pid, $qty );
                }
            }
        }
        $get_product_stmt->close();
    }
    set_site_notice( 'Your cart has been updated.', 'success' );
}

$site_title = 'Cart &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Cart';
include_once 'header.php'; ?>

<main class="main">
    <div class="container content">
        <?php
        $product_ids = [];
        $get_cart_items = get_cart_items();
        if( count( get_cart_items() ) > 0 ) : ?>
        <div class="row">
            <div class="table-responsive col-md-9">
                <div class="row">
                    <div class="cart-bg">
                        <form method="POST" action="cart.php">
                            <table class="table table-cart">
                                <thead>
                                    <tr class="cart-header">
                                        <th class="th-action" scope="col"></th>
                                        <th class="th-thumbnail" scope="col"></th>
                                        <th class="th-product-name" scope="col">Product</th>
                                        <th class="th-price" scope="col">Price</th>
                                        <th class="th-quantity" scope="col">Quantity</th>
                                        <th class="th-total" scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cart_total = 0;
                                    foreach( get_cart_items() as $id => $q ) :
                                        $product_ids[] = $id;
                                        $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
                                        $get_product_stmt->bind_param( 'i', $id );
                                        $get_product_stmt->execute();
                                        $get_product_result = $get_product_stmt->get_result();
                                        if( $get_product_result->num_rows > 0 ) {
                                            echo '<tr class="cart-body">';
                                            while( $row = $get_product_result->fetch_assoc() ) : 
                                                $price = empty( $row['sale_price'] ) ? $row['price'] : $row['sale_price'];
                                                $total_price = esc_float( $price ) * $q;
                                                $cart_total += $total_price; ?>
                                                <td class="td-action"><a href="cart.php?action=delete&id=<?php echo $id; ?>"><i class="fa fa-times-circle"></i></a></td>
                                                <td class="td-thumbnail"><a href="product.php?p=<?php echo $row['slug']; ?>"><img src="<?php echo get_productimage( $id, 'medium' ); ?>" alt=""></a></td>
                                                <td class="td-product-name"><a href="product.php?p=<?php echo $row['slug']; ?>"><?php echo $row['name']; ?></a></td>
                                                <td class="td-price">&#8369; <?php echo $price; ?></td>
                                                <td class="td-quantity">
                                                    <select name="quantity-<?php echo $id; ?>" class="form-control">
                                                        <?php 
                                                        for( $i = 1; $i <= $row['stocks']; $i++ ) {
                                                            echo '<option value="' . $i . '" ';
                                                            selected( $i, $q );
                                                            echo '>' . $i . '</option>';
                                                        } ?>
                                                    </select>
                                                </td>
                                                <td class="td-total">&#8369; <?php echo $total_price; ?></td>
                                            <?php 
                                            endwhile;
                                            echo '</tr>';
                                        }
                                        $get_product_stmt->close(); ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="cart-action text-right">
                                <p><input type="submit" value="UPDATE CART" class="btn btn-primary update-cart"></p>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="cart-summary">
                    <h3 class="cart-summary-title"><strong>Cart Summary</strong></h3>
                    <div class="cart-summary-value">
                        <p>Sub Total : &#8369; <?php echo $cart_total; ?></p>
                        <p>Shipping Fee : <span class="shipping-fee">&#8369; <?php
                                    $shipping_fee = get_siteinfo( 'shipping-fee' );
                                    echo $shipping_fee; ?></span></p>
                        <p>Total : <span class="cart-total">&#8369; <?php echo $cart_total + $shipping_fee; ?></span></p>
                        <hr>
                        <center>
                            <a href="checkout.php" class="btn btn-danger checkout">PROCEED TO CHECKOUT</a>
                        </center>  
                    </div>
                </div>
                <input type="hidden" name="product-ids" value="<?php echo serialize( $product_ids ); ?>">
                <input type="hidden" name="update-cart" value="1">
            </form>
            </div>
        </div>
        <?php else : ?>
            <p>Your shopping cart is empty.</p>
            <p><a href="products.php" class="btn btn-primary"><i class="fa fa-shopping-basket"></i> Continue Shopping</a></p>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'footer.php';