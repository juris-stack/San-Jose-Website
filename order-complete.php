<?php
/**
 * Order complete template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

$billing_fname = '';
$billing_lname = '';
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
$reference = '';
$get_cart_items = get_cart_items();

if( isset( $_POST['finalize-cart'] ) ) {
    $user_details = [];
    $cart_total = $_POST['cart-total'];
    $billing_fname = esc_str( $_POST['billing-firstname'] );
    $billing_lname = esc_str( $_POST['billing-lastname'] );
    $billing_email = esc_email( $_POST['billing-email'] );
    $billing_telephone = esc_str( $_POST['billing-telephone'] );
    $billing_address = esc_str( $_POST['billing-address'] );
    $billing_city = esc_str( $_POST['billing-city'] );
    $billing_state = esc_str( $_POST['billing-state'] );
    $billing_zip = esc_str( $_POST['billing-zip'] );
    $shipping_fname = esc_str( $_POST['shipping-firstname'] );
    $shipping_lname = esc_str( $_POST['shipping-lastname'] );
    $shipping_telephone = esc_str( $_POST['shipping-telephone'] );
    $shipping_address = esc_str( $_POST['shipping-address'] );
    $shipping_city = esc_str( $_POST['shipping-city'] );
    $shipping_state = esc_str( $_POST['shipping-state'] );
    $shipping_zip = esc_str( $_POST['shipping-zip'] );
    
    $user_details = array(
        'billing' => array(
            'firstname' => $billing_fname,
            'lastname' => $billing_lname,
            'email' => $billing_email,
            'telephone' => $billing_telephone,
            'address' => $billing_address,
            'city' => $billing_city,
            'state' => $billing_state,
            'zip' => $billing_zip
        ),
        'shipping' => array(
            'firstname' => $shipping_fname,
            'lastname' => $shipping_lname,
            'telephone' => $shipping_telephone,
            'address' => $shipping_address,
            'city' => $shipping_city,
            'state' => $shipping_state,
            'zip' => $shipping_zip
        )
    );
    
    $products_serialize = serialize( $get_cart_items );
    $user_details_serialize = serialize( $user_details );
    $type = 'Online order';
    $status = 'pending';
    
    $insert_order = $mysqli->prepare( "INSERT INTO orders (type, amount, products, status, user_details, user_ID) VALUES (?, ?, ?, ?, ?, ?)" );
    $insert_order->bind_param( 'sssssi', $type, $cart_total, $products_serialize, $status, $user_details_serialize, $user_id );
    $insert_order->execute();
    $reference = $mysqli->insert_id;
    $insert_order->close();
    
    foreach ( $get_cart_items as $id => $val ) {
        $get_stocks = esc_int( get_productby( 'stocks', $id ) );
        $stock = $get_stocks - $val;
        update_product( 'stocks', $stock, $id );
    }
    
    /** Empty the cart */
    remove_cart_items();
    
    /** Send email notification to admin */
    $to = get_siteinfo( 'company-email' );
    $subject = 'New Order Received &mdash; ' . get_siteinfo( 'site-name' );
    $mssg = "A new order was received with reference # $reference \n";
    $mssg .= "Visit this link to view order " . site_url( '/admin/order.php?id=' . $reference );
    send_mail( $to, $subject, $mssg );
    
    /** Push notification */
    push_notification( $reference, 'order', 'New order recieved' );
    
    /** Show notice to front-end user */
    set_site_notice( 'Your order has been submitted. Please pay your order at the earliest time of your convenience.', 'success' );
}

$site_title = 'Order Complete &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Order Complete';
include_once 'header.php'; ?>

<main class="main">
    <div class="container content">
        <div class="row">
            <div class="col-12">
                <h2>Here are your order details:</h2>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="2"><strong>Payment Details</strong><small>
                                    <br>Send your payment to any of our payment portals nearest you using the details below.</small></td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><?php echo get_siteinfo( 'recipient-name' ); ?></td>
                        </tr>
                        <tr>
                            <td>Telephone/Mobile Number</td>
                            <td><?php echo get_siteinfo( 'recipient-phone' ); ?></td>
                        </tr>
                        <tr>
                            <td>Reference Number</td>
                            <td><?php echo $reference; ?></td>
                        </tr>
                        <tr>
                            <td>Total Amount Due</td>
                            <td>&#8369; <?php echo $cart_total; ?></td>
                        </tr>
                        <!--<tr>
                            <td colspan="2"><strong>Billing Details</strong><br>
                                <small>Billing will be sent to</small></td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><?php echo $billing_fname . ' ' . $billing_lname; ?></td>
                        </tr>
                        <tr>
                            <td>Telephone/Mobile Number</td>
                            <td><?php echo $billing_telephone; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo $billing_address . ', ' . $billing_city . ', ' . $billing_state . ', ' . $billing_zip; ?></td>
                        </tr>-->
                        <tr>
                            <td colspan="2"><strong>Shipping Details</strong><br>
                                <small>Items will be delivered to</small></td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><?php echo $shipping_fname . ' ' . $shipping_lname; ?></td>
                        </tr>
                        <tr>
                            <td>Telephone/Mobile Number</td>
                            <td><?php echo $shipping_telephone; ?></td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><?php echo $shipping_address . ', ' . $shipping_city . ', ' . $shipping_state . ', ' . $shipping_zip; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include_once 'footer.php';