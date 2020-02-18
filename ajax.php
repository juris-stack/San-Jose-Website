<?php
/* 
 * Ajax handler
 * 
 * @package SJM
 * @author 
 */

// Include the single functions
require_once 'functions.php';

if( !empty( $_POST['action'] ) ) {
    // lets grab the action function
    $function = $_POST['action'];

    // Lets run the function
    $function();
}