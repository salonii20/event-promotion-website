<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include ("api/api.inc.php");

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    
    $tcontent = <<<PAGE

<style>
        .about-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .about-heading {
            color: #2c3e50;
            text-align: center;
        }
        .about-content {
            margin-top: 20px;
            line-height: 1.6;
            font-size: 16px;
            color: #34495e;
        }
    </style>

    <div class="about-container">
    <h2 class="about-heading">About Us</h2>
    <p class="about-content">
        Welcome to Eventify, your premier destination for experiencing the vibrancy of live events.
        Founded in 2010, our mission has been to connect people through shared experiences, from soul-stirring poetry readings and electrifying music concerts to laugh-out-loud comedy nights. We are committed to providing a platform where artists can shine and audiences can gather to celebrate the arts.
    </p>
    <p class="about-content">
        Our team is made up of event enthusiasts who are passionate about creating unforgettable moments. We work tirelessly to curate a diverse lineup of events that cater to all tastes and preferences, ensuring that there's something for everyone. Whether you're a die-hard music fan, a poetry aficionado, or a comedy lover, Eventify is your gateway to top-tier entertainment.
    </p>
    <p class="about-content">
        At Eventify, we believe that great experiences are born from great communities. This is why we strive to build strong relationships with local artists, venues, and cultural organizations across various cities. By fostering these connections, we help to enrich the local arts scene, while offering our users exclusive access to unique and engaging events.
    </p>
    <p class="about-content">
        Your feedback and insights are incredibly valuable to us. They enable us to continuously improve and bring you events that delight and inspire. Join us on our journey to transform how people experience and enjoy events. With Eventify, every night is an opportunity to discover something spectacular.
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

