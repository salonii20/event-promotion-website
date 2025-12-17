<?php
// ----INCLUDE APIS------------------------------------
// Include our Website API
include("api/api.inc.php");
include("connection.php");  // Include the database connection

// ----PAGE GENERATION LOGIC---------------------------

function displayEvents($mysqli) {
 // Check if the connection is valid
if ($mysqli->connect_error) {
    return "Failed to connect to MySQL: " . $mysqli->connect_error;
}

// Fetch upcoming and past events
$currentDate = date('Y-m-d H:i:s');
$upcomingQuery = "SELECT e.id, e.name, e.date_time, e.artist_details, e.category FROM events e WHERE e.date_time >= '$currentDate' ORDER BY e.date_time ASC";
$pastQuery = "SELECT e.id, e.name, e.date_time, e.artist_details, e.category FROM events e WHERE e.date_time < '$currentDate' ORDER BY e.date_time DESC";

// Execute queries
$upcomingResult = $mysqli->query($upcomingQuery);
$pastResult = $mysqli->query($pastQuery);

// Start building the HTML for Upcoming event display
$html = <<<HTML
<div style="display: flex; flex-direction: column; width: 100%; padding: 20px; background-color: #f0f0f0; border: 2px solid #ccc; margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Events</h2>
    </div>
    <div style='width: 100%; padding: 20px; background-color: #e0e0e0;'>
        <h3>Upcoming Events</h3>
        <div style='display: flex; flex-direction: column; justify-content: start;'>

HTML;

// Process upcoming events
if ($upcomingResult->num_rows > 0) {
    while($row = $upcomingResult->fetch_assoc()) {
        $html .= "<form action='event_details.php' method='GET' style='margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;'>
                    <h3>{$row['name']}</h3>
                    <p>Date and Time: {$row['date_time']}</p>
                    <p>Artist Details: {$row['artist_details']}</p>
                    <p>Category: {$row['category']}</p>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button type='submit'>More Details</button>
                  </form>";
    }
} else {
    $html .= "<p>No upcoming events.</p>";
}

$html .= "</div></div><div style='width: 100%; padding: 20px; background-color: #e0e0e0; margin-top: 20px;'>
<h3>Past Events</h3>
<div style='display: flex; flex-direction: column; justify-content: start;'>";

// Process past events
if ($pastResult->num_rows > 0) {
    while($row = $pastResult->fetch_assoc()) {
        $html .= "<form action='event_details.php' method='GET' style='margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;'>
                    <h3>{$row['name']}</h3>
                    <p>Date and Time: {$row['date_time']}</p>
                    <p>Artist Details: {$row['artist_details']}</p>
                    <p>Category: {$row['category']}</p>
                    <input type='hidden' name='id' value='{$row['id']}'>
                    <button type='submit'>More Details</button>
                  </form>";
    }
} else {
    $html .= "<p>No past events.</p>";
}

$html .= "</div></div></div>";
return $html;

}

// ----BUSINESS LOGIC---------------------------------
$mysqli = new mysqli('localhost', 'root', '', 'event_promotion_site'); // Example connection setup

$tpageContent2 = displayEvents($mysqli); // Ensure that $mysqli is passed

// ----BUILD OUR HTML PAGE----------------------------
// Create an instance of our Page class
$tindexpage = new MasterPage("Home Page");

$tindexpage->setDynamic2($tpageContent2);
$tindexpage->renderPage();
?>
