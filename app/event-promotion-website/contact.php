<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    
    $tcontent = <<<PAGE
    <style>
    .contact-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }
    .contact-heading {
        color: #2c3e50;
        text-align: center;
    }
    .contact-content {
        margin-top: 20px;
        line-height: 1.6;
        font-size: 16px;
        color: #34495e;
    }
</style>

<div class="contact-container">
    <h2 class="contact-heading">Contact Us</h2>
    <p class="contact-content">
        Need assistance or have a question? Our team is here to help!
    </p>
    <p class="contact-content">
        <strong>Phone:</strong> +92 3265490217<br>
        <strong>Email:</strong> support@eventify.com
    </p>
    <p class="contact-content">
        <strong>Office Hours:</strong> Monday to Friday, 9 AM to 5 PM (EST)
    </p>
    <p class="contact-content">
        <strong>Address:</strong><br>
        Eventify Inc.<br>
        123 Eventify Blvd,<br>
        Islamabad, Pakistan
    </p>
    <p class="contact-content">
        For press inquiries, please contact our media team at <strong>media@eventify.com</strong>.
    </p>
    <p class="contact-content">
        We look forward to hearing from you and will do our best to respond promptly to your inquiries.
    </p>
</div>

    

PAGE;
    return $tcontent;
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage();

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Home Page");
$tindexpage->setDynamic1($tpagecontent);

$tindexpage->renderPage();

?>

