<?php
/* 
 * Report Template
 * 
 * @package SJM
 * @author
 */

// include the admin functions
require_once 'functions.php';

$site_name = get_siteinfo( 'site-name' );
$year = date( 'Y' );
$today = date( 'M j, Y' );

$today_earning = 0;
$today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE (status = 'processed' OR status = 'completed') AND DATE(date_added) = CURDATE()" );
$today_earnings_stmt->execute();
$today_earnings_result = $today_earnings_stmt->get_result();
$today_count = $today_earnings_result->num_rows;
if( $today_earnings_result->num_rows > 0 ) {
    while( $row = $today_earnings_result->fetch_assoc() ) {
        $today_earning += $row['amount'];
    }
}
$today_earnings_stmt->close();
$today_total = number_format( $today_earning, 2 );

$yesterday_earning = 0;
$yesterday_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE (status = 'processed' OR status = 'completed') AND DATE(date_added) = CURDATE() - INTERVAL 1 DAY" );
$yesterday_earnings_stmt->execute();
$yesterday_earnings_result = $yesterday_earnings_stmt->get_result();
$yesterday_count = $yesterday_earnings_result->num_rows;
if( $yesterday_earnings_result->num_rows > 0 ) {
    while( $row = $yesterday_earnings_result->fetch_assoc() ) {
        $yesterday_earning += $row['amount'];
    }
}
$yesterday_earnings_stmt->close();
$yesterday_total = number_format( $yesterday_earning, 2 );

$week_earning = 0;
$week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE (status = 'processed' OR status = 'completed') AND YEARWEEK(date_added) = YEARWEEK(CURDATE())" );
$week_earnings_stmt->execute();
$week_earnings_result = $week_earnings_stmt->get_result();
$week_count = $week_earnings_result->num_rows;
if( $week_earnings_result->num_rows > 0 ) {
    while( $row = $week_earnings_result->fetch_assoc() ) {
        $week_earning += $row['amount'];
    }
}
$week_earnings_stmt->close();
$week_total = number_format( $week_earning, 2 );

$month_earning = 0;
$month_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE (status = 'processed' OR status = 'completed') AND MONTH(date_added) = MONTH(CURDATE())" );
$month_earnings_stmt->execute();
$month_earnings_result = $month_earnings_stmt->get_result();
$month_count = $month_earnings_result->num_rows;
if( $month_earnings_result->num_rows > 0 ) {
    while( $row = $month_earnings_result->fetch_assoc() ) {
        $month_earning += $row['amount'];
    }
}
$month_earnings_stmt->close();
$month_total = number_format( $month_earning, 2 );

$total_earning = 0;
$total_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status = 'processed' OR status = 'completed'" );
$total_earnings_stmt->execute();
$total_earnings_result = $total_earnings_stmt->get_result();
if( $total_earnings_result->num_rows > 0 ) {
    while( $row = $total_earnings_result->fetch_assoc() ) {
        $total_earning += $row['amount'];
    }
}
$total_earnings_stmt->close();
$grand_total = number_format( $total_earning, 2 );

// Include the main TCPDF library (search for installation path).
require_once('../source/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        global $site_name;
        // Logo
        //$image_file = site_url( '/assets/images/logo.png' );
        //$this->Image($image_file, 10, 10, 25, '', 'PNG', site_url(), 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetY(20);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 0, $site_name . ' Sales Report', 0, false, 'C', 0, site_url(), 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($site_name);
$pdf->SetTitle('Sales Report');
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('Report, Sales, Sales Report, summmary');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// define some HTML content with style
$html = <<<EOF
<html>
        <head>
<style>
.title {
padding-top: 30px;
    margin: 0 auto 10px;
    max-width: 700px;
}
.table {
    border-collapse: collapse;
    max-width: 700px;
    margin: 20px auto;
    width: 100%;
}
.thead {
    background-color: #444;
    color: #fff;
}
.table td, .table th {
    padding: 10px 10px;
    text-align: left;
}
.table tr {
    border-bottom: 1px solid #ddd;
}
</style>
        </head>
        <body>

<h2 class="title">Sales Summary</h2>
        <table class="table" cellpadding="4" cellspacing="0">
                <tr class="thead">
                    <td></td>
                    <td>Number of Orders</td>
                    <td>Total Revenue</td>
                </tr>
                <tr>
                    <td>Today ($today)</td>
                    <td>$today_count</td>
                    <td>$today_total</td>
                </tr>
                
                <tr>
                    <td>Yesterday</td>
                    <td>$yesterday_count</td>
                    <td>$yesterday_total</td>
                </tr>
                
                <tr>
                    <td>This week</td>
                    <td>$week_count</td>
                    <td>$week_total</td>
                </tr>
                
                <tr>
                    <td>This month</td>
                    <td>$month_count</td>
                    <td>$month_total</td>
                </tr>
                <tr>
                    <td><b>Grand Total Sales</b></td>
                    <td></td>
                    <td><b>$grand_total</b></td>
                </tr>
        </table>
        </body>
        </html>
EOF;

// output the HTML content
$pdf->writeHTML($html, true, false, false, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('report-' . time() . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+