<?php
/**
 * Checkout template
 * 
 * @package SJM
 * @author 
 */
require_once 'functions.php';

$billing_fname = '';
$billing_lname = '';
$billing_email = '';
$billing_telephone = '';
$billing_address = '';
$billing_city = '';
$billing_state = '';
$billing_zip = '';
$shipping_fname = '';
$shipping_lname = '';
$shipping_telephone = '';
$shipping_address = '';
$shipping_city = '';
$shipping_state = '';
$shipping_zip = '';
$get_cart_items = get_cart_items();

if( user_is_loggedin() ) {
    $billing_fname = get_currentusermeta( 'billing-firstname' );
    $billing_lname = get_currentusermeta( 'billing-lastname' );
    $billing_email = get_currentuser( 'email' );
    $billing_telephone = get_currentusermeta( 'billing-telephone' );
    $billing_address = get_currentusermeta( 'billing-address' );
    $billing_city = get_currentusermeta( 'billing-city' );
    $billing_state = get_currentusermeta( 'billing-state' );
    $billing_zip = get_currentusermeta( 'billing-zip' );
    $shipping_fname = get_currentusermeta( 'shipping-firstname' );
    $shipping_lname = get_currentusermeta( 'shipping-lastname' );
    $shipping_telephone = get_currentusermeta( 'shipping-telephone' );
    $shipping_address = get_currentusermeta( 'shipping-address' );
    $shipping_city = get_currentusermeta( 'shipping-city' );
    $shipping_state = get_currentusermeta( 'shipping-state' );
    $shipping_zip = get_currentusermeta( 'shipping-zip' );
}

$site_title = 'Checkout &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Checkout';
include_once 'header.php'; ?>

<main class="main">
    <div class="container content">
        <?php
        $cart_count = count( $get_cart_items );
        if( $cart_count > 0 ) : ?>
        <form method="POST" action="order-complete.php" class="content">
            <div class="row">
                <div class="col-md-6 checkout-billing">
                    <h4>Billing Details</h4>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col">
                                <label for="billing-firstname">First Name</label>
                                <input type="text" value="<?php echo $billing_fname; ?>" class="form-control" id="billing-firstname" name="billing-firstname" required="">
                            </div>
                            <div class="col">
                                <label for="billing-lastname">Last Name</label>
                                <input type="text" value="<?php echo $billing_lname; ?>" class="form-control" id="billing-lastname" name="billing-lastname" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="billing-email">Email Address</label>
                        <input type="email" value="<?php echo $billing_email; ?>" name="billing-email" class="form-control" id="billing-email" required="">
                    </div>
                    <div class="form-group">
                        <label for="billing-telephone">Mobile Number</label>
                        <input type="text" value="<?php echo $billing_telephone; ?>" name="billing-telephone" class="form-control" id="billing-telephone" pattern="[0-9]{11}" title="PLEASE PUT 11-DIGIT NUMBER WITH COUNTRY CODE" onKeyDown="if(this.value.length==11 && event.keyCode!=8) return false;">
                    </div>
                    <div class="form-group">
                        <label for="billing-address">House no., Purok, Sitio, Barangay</label>
                        <input type="text" value="<?php echo $billing_address; ?>" name="billing-address" class="form-control" id="billing-address" required="">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="billing-city">City or Municipality</label>
                            <input type="text" value="<?php echo $billing_city; ?>" name="billing-city" class="form-control" id="billing-city" required="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="billing-state">State or Province</label>
                            <input type="text" value="<?php echo $billing_state; ?>" name="billing-state" class="form-control" id="billing-state" required="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="billing-zip">Zip Code</label>
                            <input name="billing-zip" value="<?php echo $billing_zip; ?>" type="text" class="form-control" id="billing-zip" required="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 checkout-shipping">
                    <h4>Shipping Details</h4>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col">
                                <label for="shipping-firstname">First Name</label>
                                <input type="text" value="<?php echo $shipping_fname; ?>" class="form-control" id="shipping-firstname" name="shipping-firstname" required="">
                            </div>
                            <div class="col">
                                <label for="shipping-lastname">Last Name</label>
                                <input type="text" value="<?php echo $shipping_lname; ?>" class="form-control" id="shipping-lastname" name="shipping-lastname" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shipping-telephone">Mobile Number</label>
                        <input type="text" value="<?php echo $shipping_telephone; ?>" name="shipping-telephone" class="form-control" id="shipping-telephone" required="" pattern="[0-9]{11}" title="PLEASE PUT 11-DIGIT NUMBER WITH COUNTRY CODE" onKeyDown="if(this.value.length==11 && event.keyCode!=8) return false;">
                    </div>
                    <div class="form-group">
                        <label for="shipping-address">House no., Purok, Sitio, Barangay</label>
                        <input type="text" value="<?php echo $shipping_address; ?>" name="shipping-address" class="form-control" id="shipping-address" required="">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="shipping-city">City or Municipality</label>
                            <input type="text" value="<?php echo $shipping_city; ?>" name="shipping-city" class="form-control" id="shipping-city" required="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="shipping-state">State or Province</label>
                            <input type="text" value="<?php echo $shipping_state; ?>" name="shipping-state" class="form-control" id="shipping-state" required="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="shipping-zip">Zip Code</label>
                            <input name="shipping-zip" value="<?php echo $shipping_zip; ?>" type="text" class="form-control" id="shipping-zip" required="">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 checkout-order">
                    <h4>Order Summary</h4>
                    <table class="table table-bordered table-cart">
                        <thead>
                            <tr class="cart-header">
                                <th class="th-product-name" scope="col">Product</th>
                                <th class="th-price" scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cart_total = 0;
                            foreach( $get_cart_items as $id => $q ) :
                                $product_ids[] = $id;
                                $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
                                $get_product_stmt->bind_param( 'i', $id );
                                $get_product_stmt->execute();
                                $get_product_result = $get_product_stmt->get_result();
                                if( $get_product_result->num_rows > 0 ) {
                                    echo '<tr>';
                                    while( $row = $get_product_result->fetch_assoc() ) : 
                                        $price = empty( $row['sale_price'] ) ? $row['price'] : $row['sale_price'];
                                        $total_price = esc_float( $price ) * $q;
                                        $cart_total += $total_price; ?>
                                        <td><?php echo $row['name'] . ' X ' . $q; ?></td>
                                        <td class="td-total">&#8369; <?php echo $total_price; ?></td>
                                    <?php 
                                    endwhile;
                                    echo '</tr>';
                                }
                                $get_product_stmt->close(); ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Shipping Fee</td>
                                <td class="shipping-fee">&#8369; <?php
                                $shipping_fee = get_siteinfo( 'shipping-fee' ); 
                                echo $shipping_fee; ?></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td class="cart-total"><strong>&#8369; <?php echo $cart_total + $shipping_fee; ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-md-6 checkout-payment">
                    <h4>Payment</h4>
                    <div class="payment-gateways">
                        <div class="payment-gateway">
                            <p class="bubble">Please send your payment to the payment portal available near you. The credentials will be given at the end of this process.</p>
                        </div>
                    </div>
                    <div class="payment-finalize-action text-right">
                        <input class="btn btn-secondary" type="submit" value="Finalize Order">
                    </div>
                </div>
            </div>
            <input type="hidden" name="finalize-cart" value="1">
            <input name="cart-total" type="hidden" value="<?php echo $cart_total + $shipping_fee; ?>">
        </form>
        <?php else : ?>
            <p>Your shopping cart is empty.</p>
            <p><a href="products.php" class="btn btn-primary"><i class="fa fa-shopping-basket"></i> Continue Shopping</a></p>
        <?php endif; ?>
    </div>
</main>

<?php include_once 'footer.php';