<?php
/**
 * Chat admin template
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

$chat_id = !empty( $_GET['id'] ) ? $_GET['id'] : '';

if( empty( $chat_id ) ) {
    redirect( 'support.php' );
}

if( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
    $stmt_delete = $mysqli->prepare( "DELETE FROM support WHERE ID = ?" );
    $stmt_delete->bind_param( 'i', $chat_id );
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect( 'support.php' );
}

$site_title = 'Chat Support';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo $site_title; ?></h2>
        </div>
    </div>
</div>
<form action="" method="POST">
    <div class="card">
        <?php
        $session = '';
        $name = '';
        $status = '';
        $chat_stmt = $mysqli->prepare( "SELECT * FROM support WHERE ID = ? LIMIT 1" );
        $chat_stmt->bind_param( 'i', $chat_id );
        $chat_stmt->execute();
        $chat_result = $chat_stmt->get_result();
        if( $chat_result->num_rows > 0 ) {
            while( $row = $chat_result->fetch_assoc() ) {
                $session = $row['session'];
                $name = $row['from_name'];
                $status = $row['status'];
            }
            
            if( $status === 'open' ) {
                $new_status = 'replied';
                $stmt_update = $mysqli->prepare( "UPDATE support SET status = ? WHERE ID = ?" );
                $stmt_update->bind_param( 'si', $new_status, $chat_id );
                $stmt_update->execute();
                $stmt_update->close();
            }
        } 
        $chat_stmt->close(); ?>
        <div class="card-header">
            <?php if( $status !== 'closed' ) : ?>
            You are chatting with <strong><?php echo ucfirst( $name ); ?></strong>
            <?php else : ?>
            Chat log for session <strong><?php echo $session; ?></strong>
            <?php endif; ?>
        </div>
        <div class="card-body card-block">
            <div id="chat">
                <?php if( $status !== 'closed' ) : ?>
                <div id="chat-box" class="chat-box"><?php include_once( '../uploads/chat/' . $session . '.html' ); ?></div>
                <textarea name="chat-message" class="form-control" id="chat-message" placeholder="Message" required=""></textarea>
                <input id="chat-session" type="hidden" name="chat-session" value="../uploads/chat/<?php echo $session; ?>.html">
                <?php else : ?>
                <div class="chat-box"><?php include_once( '../uploads/chat/' . $session . '.html' ); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<?php include_once 'footer.php';