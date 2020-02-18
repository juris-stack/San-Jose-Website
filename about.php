<?php
/**
 * The main index
 * 
 * @package SJM
 * @author
 */
require_once 'functions.php';

$site_title = get_siteinfo( 'site-name' );
$page_title = 'About';
include_once 'header.php'; ?>

<div class="container stug-content">
    <div class="row">
        <div class="col-md-6 content">
            <img src="assets/images/about.jpg" alt="About San Jose">
        </div>
        <div class="col-md-6 content bg-text">
            <h3 class="title-01">Our Shop</h3>
            <p>On June 4, 2012 San Jose Motor Parts and Accessories was launch in the City of Tagbilaran, located in San Jose Street Cogon, District. This business is owned by Bernard Mahinay.</p>
            <h3 class="title-02">Our Business</h3>
            <p>Through the years of successful business. San Jose Motor Parts and Accessories were known because of their accessibility, affordability and genuine spare parts. And they have the best automotive technician in locality.</p>
            <h3 class="title-02">Hours Opened</h3>
            <p>Monday to Sunday - 7:00am - 6:00pm</p>
            <h3 class="title-02">We Offer</h3>
            <p>Motor parts and accessories, motor repair and check up.</p>

        </div>
    </div>
</div>


<?php include_once 'footer.php';