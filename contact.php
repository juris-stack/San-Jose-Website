<?php
/**
 * The main index
 * 
 * @package SJM
 * @author
 */
require_once 'functions.php';

$site_title = get_siteinfo( 'site-name' );
$page_title = 'Locate Us';
include_once 'header.php'; ?>

<div class="container stug-content">
    <div class="row">
        <div class="col-md-8 content no-bg">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15733.33132082908!2d123.851291!3d9.652576!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x59a5d628dfcc0108!2sSan+Jose+Motor+Parts!5e0!3m2!1sen!2sph!4v1488683731588" width="100%" height="450px" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="col-md-4 content bg-text">
            <img src="assets/images/loc.jpg" alt="Location">
            <p class="text-01">San Jose Motor Parts and Accessories is located in #23 San Jose Street Cogon, District Tagbilaran City.</p>
            <p class="text01">Visit us <a href="https://www.facebook.com/SanJoseMotorPartsAndAccessories/" target="_blank">@facebook.com</a></p>
        </div>
    </div>
</div>

<?php include_once 'footer.php';