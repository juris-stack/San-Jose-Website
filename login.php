<?php
/**
 * Login template
 * 
 * @package SJM
 * @author 
 */

// Include the main functions
require_once 'main.php';

// Get the action (login, register, or forgotpassword)
$action = empty( $_GET['action'] ) ? '' : $_GET['action'];

// get redirect url
$redirect = empty( $_GET['redirect'] ) ? 'admin/' : urldecode( $_GET['redirect'] );

if( $action === 'logout' ) {
    update_currentuserinfo( 'status', 'offline' );
    session_destroy();
    setcookie( 'uid', '', time() -3600, '/' );
    redirect( $redirect );
}

if( $action === 'autologin' && !empty( $_GET['auth'] ) && !empty( $_GET['id'] ) ) {
    $auth = $_GET['auth'];
    $id = esc_int( $_GET['id'] );
    $get_user = $mysqli->prepare( "SELECT * FROM users WHERE ID=? AND auth=? LIMIT 1" );
    $get_user->bind_param( "is", $id, $auth );
    $get_user->execute();
    $get_user_result = $get_user->get_result();
    if( $get_user_result->num_rows > 0 ) {
        while( $row = $get_user_result->fetch_assoc() ) {
            $_SESSION['uid'] = $row['ID'];
            update_userinfo( 'status', 'online', $row['ID'] );
        }
        redirect( 'admin/profile.php' );
    }else{
        set_site_notice( 'Sorry the one time login auth key has expired. Please request a new one.', 'error' );
    }
    $get_user->close();
}

// Check if the user is loggedin already and redirect if he/she is
if( user_is_loggedin() ) {
    redirect( $redirect );
}

if( isset( $_POST['forgotpassword'] ) ) {
    $get_user = $mysqli->prepare( "SELECT * FROM users WHERE username=? OR email=? LIMIT 1" );
    $get_user->bind_param( "ss", $useremail, $useremail );
    $get_user->execute();
    $get_user_result = $get_user->get_result();
    if( $get_user_result->num_rows > 0 ) {
        while( $row = $get_user_result->fetch_assoc() ) {
            $auth = md5( $row['username'] . time() );
            
            /** Save the authentication key for this user */
            update_userinfo( 'auth', $auth, $row['ID'] );
            
            /** Lets send a new login email for the user to follow */
            $to = $row['email'];
            $subject = 'Forgot password login request &mdash; ' . get_siteinfo( 'site-name' );
            $mssg = "You have requested a one time login link to change your password \r\n\r\n";
            $mssg .= "Please click this link to login http://" . site_url( '/login.php?action=autologin&auth=' . $auth . '&id=' . $row['ID'] ) . "\r\n\r\n";
            $mssg .= "Note: This is a one time login only. You need to request new login again if you fail to update your password.";
            send_mail( $to, $subject, $mssg );
            
            /** Inform the user that the login link was set already */
            set_site_notice( 'An automatic login link was sent to your email. Change your password once you are logged in.', 'success' );
        }
    }else{
        set_site_notice( 'Username and/or password does not exist.', 'error' );
    }
    $get_user->close();
}

if( isset( $_POST['login'] ) ) {
    $useremail = $mysqli->real_escape_string( $_POST['useremail'] );
    $password = $mysqli->real_escape_string( $_POST['password'] );
    $md5 = md5( $password );
    $get_user = $mysqli->prepare( "SELECT * FROM users WHERE (username=? OR email=?) AND password=? LIMIT 1" );
    $get_user->bind_param( "sss", $useremail, $useremail, $md5 );
    $get_user->execute();
    $get_user_result = $get_user->get_result();
    if( $get_user_result->num_rows > 0 ) {
        while( $row = $get_user_result->fetch_assoc() ) {
            if( $row['active'] === '1' ) {
                $_SESSION['uid'] = $row['ID'];
                if( isset( $_POST['remember'] ) ) {
                    setcookie( 'uid', $row['ID'], time() +2592000, '/' );
                }
                update_userinfo( 'status', 'online', $row['ID'] );
                redirect( 'admin/' );
            }else{
                set_site_notice( 'Your account is inactive. If you think this is an error, you may contact us for assistance.', 'error' );
            }
        }
    }else{
        set_site_notice( 'Username and/or password is incorrect.', 'error' );
    }
    $get_user->close();
}

if( isset( $_POST['register'] ) ) {
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
        $role = 1;
        $active = '1';
        
        $insert_user = $mysqli->prepare( "INSERT INTO users (username, email, password, role, active) VALUES (?, ?, ?, ?, ?)" );
        $insert_user->bind_param( 'sssis', $username, $email, $md5, $role, $active );
        $insert_user->execute();
        $insert_user->close();
        set_site_notice( 'Registered successfully.', 'success' );
    }
    $check_user_sql->close();
    $check_email_sql->close();
}

switch( $action ) {
    case 'register' :
        $site_title = 'Register';
        break;
    default :
        $site_title = 'Login';
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">

    <!-- Title Page-->
    <title><?php echo $site_title; ?></title>

    <!-- Fontfaces CSS-->
    <link href="assets/css/font-face.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/font-awesome-5/css/fontawesome-all.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/mdi-font/css/material-design-iconic-font.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="assets/vendor/bootstrap-4.1/bootstrap.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="assets/vendor/animsition/animsition.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/wow/animate.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/css-hamburgers/hamburgers.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/slick/slick.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/select2/select2.min.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">
    <link href="assets/vendor/perfect-scrollbar/perfect-scrollbar.css?ver=<?php echo $verion; ?>" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="assets/css/admin.css?ver=<?php echo filemtime( 'assets/css/admin.css' ); ?>" rel="stylesheet" media="all">

</head>

<body class="animsition login">
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="index.php">
                                <img src="assets/images/logo-small.png" alt="CoolAdmin">
                            </a>
                        </div>
                        <?php show_site_notice(); ?>
                        <div class="login-form">
                            <form action="" method="post">
                                <?php if( empty( $action ) || $action === 'forgotpassword' ) : ?>
                                <div class="form-group">
                                    <label>Username or Email</label>
                                    <input class="au-input au-input--full" type="text" name="useremail" placeholder="Username or Email">
                                </div>
                                <?php elseif( $action === 'register' ) : ?>
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="au-input au-input--full" type="text" name="username" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email">
                                </div>
                                <?php endif; ?>
                                 <?php if( $action  !== 'forgotpassword') : ?>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password">
                                </div>
                                <?php endif; ?>
                                <?php if( empty( $action ) ) : ?>
                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Remember Me
                                    </label>
                                    <label>
                                        <a href="login.php?action=forgotpassword">Forgotten Password?</a>
                                    </label>
                                </div>
                                <?php endif; ?>
                                <?php if( empty( $action ) ) : ?>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">sign in</button>
                                <input type="hidden" value="1" name="login">
                                <?php elseif( $action === 'register' ) : ?>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">register</button>
                                <input type="hidden" value="1" name="register">
                                <?php elseif( $action === 'forgotpassword' ) : ?>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">request login link</button>
                                <input type="hidden" value="1" name="forgotpassword">
                                <?php endif; ?>
                            </form>
                            <div class="register-link">
                                <p>
                                    <?php if( empty( $action ) ) : ?>
                                    Don't you have account?
                                    <a href="login.php?action=register">Sign Up Here</a>
                                    <?php elseif( $action === 'register' ) : ?>
                                    Already have account?
                                    <a href="login.php">Sign In</a>
                                    <?php elseif( $action === 'forgotpassword' ) : ?>
                                    Don't you have account?
                                    <a href="login.php?action=register">Sign Up Here</a><br>
                                    Already have account?
                                    <a href="login.php">Sign In</a>
                                    <?php endif; ?>
                                    
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Jquery JS-->
    <script src="assets/vendor/jquery-3.2.1.min.js?ver=<?php echo $verion; ?>"></script>
    <!-- Bootstrap JS-->
    <script src="assets/vendor/bootstrap-4.1/popper.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/bootstrap-4.1/bootstrap.min.js?ver=<?php echo $verion; ?>"></script>
    <!-- Vendor JS       -->
    <script src="assets/vendor/slick/slick.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/wow/wow.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/animsition/animsition.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/counter-up/jquery.waypoints.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/counter-up/jquery.counterup.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/circle-progress/circle-progress.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/perfect-scrollbar/perfect-scrollbar.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/chartjs/Chart.bundle.min.js?ver=<?php echo $verion; ?>"></script>
    <script src="assets/vendor/select2/select2.min.js?ver=<?php echo $verion; ?>"></script>

    <!-- Main JS-->
    <script src="assets/js/admin.js?ver=<?php echo filemtime( 'assets/js/admin.js' ); ?>"></script>

</body>

</html>
<!-- end document-->