<?php
session_start();  // Ensure session start at the beginning to access session variables
include("api/api.inc.php");  // Include your Website API

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit;
}

// ----PAGE GENERATION LOGIC---------------------------
function createPage($username)
{
    $tcontent = <<<PAGE
    <style>
        .profile-container {
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .profile-header {
            color: #333;
            margin-bottom: 20px;
        }
    </style>

    <div class="profile-container">
        <h1 class="profile-header">Welcome, $username!</h1>
        <p>This is your profile page. More interactive features will be added here soon.</p>
    </div>
    
PAGE;
    return $tcontent;
}

// Get the username from session
$username = $_SESSION['username'];

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage($username);

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of your Page class
$tindexpage = new MasterPage("Home Page");
$tindexpage->setDynamic1($tpagecontent);
$tindexpage->renderPage();

?>
