<?php
/* 
 * On site point of sale
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

$products = [];
$total = '';
$received = '';
$pos_products = '';
if( isset( $_POST['pos-products'] ) ) {
    $quantity = esc_float( $_POST['pos-quantity-input'] );
    $total = esc_float( $_POST['pos-total'] );
    $received = esc_float( $_POST['pos-received'] );
    $pos_products = $_POST['pos-products'];
    $exp_products = explode( ',', $pos_products );
    foreach( $exp_products as $product ) {
        $exp_product = explode( ':', $product );
        $products[$exp_product[0]] = $exp_product[1];
    }
    if($received < $total){
        set_site_notice( 'Payment is not enough.', 'error' );
    }else{
        $products_serialize = serialize( $products );
        $type = 'Walk-in';
        $status = 'completed';
        $managed_by = get_currentuser( 'username' );
        $insert_order = $mysqli->prepare( "INSERT INTO orders (type, amount, products, status, managed_by) VALUES (?, ?, ?, ?, ?)" );
        $insert_order->bind_param( 'sssss', $type, $total, $products_serialize, $status, $managed_by );
        $insert_order->execute();
        $insert_order->close();

        foreach ( $products as $id => $quantity ) {
            $get_stocks = esc_int( get_productby( 'stocks', $id ) );
            $stock = $get_stocks - $quantity;
            update_product( 'stocks', $stock, $id );
        }
        
        set_site_notice( 'Purchase has been completed.', 'success' );
    }
}

$site_title = 'Point Of Sale';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo $site_title; ?></h2>
            <a id="pos-add-item" class="au-btn au-btn-icon au-btn--blue" href="#"><i class="zmdi zmdi-file-text"></i> Add Item</a>
        </div>
    </div>
</div>
<div class="row m-t-25">
    <div class="col-md-12">
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2">
                <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th>product</th>
                        <th>price</th>
                        <th>quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="pos-items">
                    <?php
                    $html = '';
                    if( is_array( $products ) && count( $products ) > 0 ) : 
                        foreach( $products as $id => $qty ) :
                            $stocks = 0;
                            $price = '';
                            $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE status='published' AND stocks > 0 ORDER BY name ASC" );
                            $get_products_stmt->execute();
                            $get_products_result = $get_products_stmt->get_result();
                            if( $get_products_result->num_rows > 0 ) {
                                while( $row = $get_products_result->fetch_assoc() ) {
                                    if( esc_int( $id ) === esc_int( $row['ID'] ) ) {
                                        $stocks = $row['stocks'];
                                        $price = $row['price'];
                                    }
                                    $html .= '<option value="' . $row['ID'] . '" ' . selected( esc_int( $id ), esc_int( $row['ID'] ), false ) . '>' . $row['name'] . '</option>';
                                }
                            }
                            $get_products_stmt->close(); ?>
                            <tr data-price="<?php echo $price; ?>">
                                <td width="50"><a href="#" class="item-remove"><i class="fa fa-times-circle"></i></a></td>
                                <td>
                                    <select class="item-select form-control">
                                        <option value="">Choose product...</option>
                                        <?php echo $html; ?>
                                    </select>
                                </td>
                                <td class="item-price"><?php echo $price; ?></td>
                                <td class="item-quantity">
                                    <select class="form-control quantity-select">
                                        <?php
                                        for( $i=1; $i <= $stocks; $i++ ) {
                                            echo '<option value="' . $i . '" ' . selected( $i, esc_int( $qty ), false ) . '>' . $i . '</option>';
                                        } ?>
                                    </select>
                                </td>
                                <td class="item-total"><?php echo $total; ?></td>
                            </tr>
                        <?php 
                        endforeach;
                    endif; ?>
                </tbody>
                <tfoot id="pos-footer"<?php
                if( is_array( $products ) && count( $products ) > 0 ) {
                    echo ' style="display: table-footer-group;"';
                } ?>>
                    <tr style="font-size: 20px;">
                        <td colspan="4" class="text-right">Total Sales</td>
                        <td>&#8369; <span id="pos-total" data-total="<?php echo $total; ?>"><?php echo $total; ?></span></td>
                    </tr>
                    <tr style="font-size: 20px;">
                        <td colspan="4" class="text-right">Amount Received</td>
                        <td>&#8369; <input id="pos-received" type="text" class="form-control" style="width: 100px; display: inline;" value="<?php echo $received; ?>"></td>
                    </tr>
                    <tr style="font-size: 20px;">
                        <td colspan="4" class="text-right">Change</td>
                        <td>&#8369; <span id="pos-change"></span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="text-right pos-action" id="pos-action"<?php
                if( is_array( $products ) && count( $products ) > 0 ) {
                    echo ' style="display: block;"';
                } ?>>
            <form method="POST" action="">
                <input type="submit" class="au-btn au-btn-icon au-btn--green" style="min-width: 150px; text-align: center;" value="Finalize Order">
                <input type="hidden" id="pos-products-input" name="pos-products" value="<?php echo $pos_products; ?>">
                <input type="hidden" id="" name="pos-quantity-input" value="<?php echo $qty; ?>">
                <input type="hidden" id="pos-total-input" name="pos-total" value="<?php echo $total; ?>">
                <input type="hidden" id="pos-received-input" name="pos-received" value="<?php echo $received; ?>">
            </form>
        </div>
    </div>
</div>

<?php include_once 'footer.php';