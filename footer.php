<?php
/**
 * Site footer template
 * 
 * @package SJM
 * @author 
 */
?>
            <footer class="bg3 p-t-75 p-b-32">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3 p-b-50">
                            <h4 class="stext-301 cl0 p-b-30">
                                <strong>Links</strong>
                            </h4>
                                <li class="p-b-10"><a href="index.php" class="stext-107 cl7 hov-cl1 trans-04">Home</a></li>
                                <li class="p-b-10"><a href="products.php" class="stext-107 cl7 hov-cl1 trans-04">Shop</a></li>
                                <li class="p-b-10"><a href="about.php" class="stext-107 cl7 hov-cl1 trans-04">About</a></li>
                                <li class="p-b-10"><a href="contact.php" class="stext-107 cl7 hov-cl1 trans-04">Contact</a></li>
                        </div>

                        <div class="col-sm-6 col-lg-3 p-b-50">
                            <h4 class="stext-301 cl0 p-b-30">
                                <strong>Categories</strong>
                            </h4>
                                <?php
                                    $get_category_stmt = $mysqli->prepare( "SELECT * FROM category WHERE status='published' ORDER BY name ASC" );
                                    $get_category_stmt->execute();
                                    $get_category_result = $get_category_stmt->get_result();
                                    if( $get_category_result->num_rows > 0 ) {
                                        while( $row = $get_category_result->fetch_assoc() ) {
                                            echo '<li class="p-b-10"><a href="category.php?p=' . $row['slug'] . '" class="stext-107 cl7 hov-cl1 trans-04">' . $row['name'] . '</a></li>';
                                        }
                                    }
                                    $get_category_stmt->close(); 
                                ?>
                        </div>

                        <div class="col-sm-6 col-lg-3 p-b-50">
                            <h4 class="stext-301 cl0 p-b-30">
                                <strong>Brands</strong>
                            </h4>
                                <?php
                                    $get_brand_stmt = $mysqli->prepare( "SELECT * FROM brand WHERE status='published' ORDER BY name ASC" );
                                    $get_brand_stmt->execute();
                                    $get_brand_result = $get_brand_stmt->get_result();
                                    if( $get_brand_result->num_rows > 0 ) {
                                        while( $row = $get_brand_result->fetch_assoc() ) {
                                            echo '<li class="p-b-10"><a href="brand.php?p=' . $row['slug'] . '" class="stext-107 cl7 hov-cl1 trans-04">' . $row['name'] . '</a></li>';
                                        }
                                    }
                                    $get_brand_stmt->close(); 
                                ?>
                        </div>

                        <div class="col-sm-6 col-lg-3 p-b-50">
                            <h4 class="stext-301 cl0 p-b-30">
                                <strong>GET IN TOUCH</strong>
                            </h4>

                            <p class="stext-107 cl7 size-201">
                                Any questions? Can't find what you need? Call us at <u><?php echo get_siteinfo( 'company-phone' ); ?></u>
                            </p>
                        </div>

                    </div>
                </div>
                <div class="copyright">
                    <div class="container-footer cl7 centered">
                        <p class="stext-107 cl7 size-201">&copy; <?php echo get_siteinfo( 'site-name' ); ?> <?php echo date( 'Y' ); ?>. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
        <?php
        $status_message = 'We are online. Chat with us!';
        $count_query = $mysqli->query( "SELECT COUNT(ID) FROM users WHERE status = 'online' AND role > 1" );
        $row = $count_query->fetch_row();
        $count_rows = $row[0]; ?>
        <div id="chat">
            <?php if( $count_rows > 0 ) : ?>
            <div id="chat-menu-open" class="chat-menu chat-menu-open clearfix">
                <p class="chat-welcome">Chat with us!</p>
                <p class="chat-actions">
                    <a href="#" title="Minimize" id="minimize-chat"><i class="fa fa-caret-square-down"></i></a>
                    <a id="end-chat" href="#" title="End Chat"><i class="fa fa-window-close"></i></a>
                </p>
            </div>
            <div id="chat-menu-close" class="chat-menu chat-menu-close clearfix">
                <p class="chat-welcome"><?php echo $status_message; ?></p>
            </div>
            <div id="chat-content" class="chat-content">
                <div id="chat-box" class="chat-box"></div>
                <h2 id="chat-status"><?php echo $status_message; ?></h2>
                <div id="chat-notice">
                    <p>Please enter your name, email, and message to start chatting with us.</p>
                </div>
                <form name="message" action="" id="chat-form">
                    <input name="chat-name" class="form-control" type="text" id="chat-name" placeholder="Name" required="">
                    <input name="chat-email" class="form-control" type="email" id="chat-email" placeholder="Email" required="">
                    <textarea name="chat-message" class="form-control" id="chat-message" placeholder="Message" required=""></textarea>
                    <input class="btn btn-primary" name="submitmsg" type="submit"  id="chat-submit" value="Start Chat">
                </form>
            </div>
            <?php else : ?>
            <div class="chat-menu offline clearfix">
                <p class="chat-welcome">Live Chat is offline<br><small>Our office time is 8AM-5PM, Mon-Sat</small></p>
            </div>
            <?php endif; ?>
        </div>
        <script src="assets/vendor/jquery-3.2.1.min.js?ver=<?php echo $verion; ?>"></script>
        <script src="assets/js/frontend.js?ver=<?php echo filemtime( 'assets/js/frontend.js' ); ?>"></script>
    </body>
</html>