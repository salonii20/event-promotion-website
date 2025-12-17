<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// ----INCLUDE APIS------------------------------------
include("api/api.inc.php");  // Include our Website API
include("connection.php"); 

function createPage() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['event_id'])) {
        $event_id = $_POST['event_id'];

        // Database connection setup
        $conn = new mysqli("localhost", "root", "", "event_promotion_site");
        if ($conn->connect_error) {
            return "<div style='padding: 10px; background-color: #f44336; color: white; margin-bottom: 20px;'>Connection failed: " . htmlspecialchars($conn->connect_error) . "</div>";
        }

        // SQL to delete an event
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            return "<div style='padding: 10px; background-color: #4CAF50; color: white; margin-bottom: 20px;'>Event deleted successfully.</div>";
        } else {
            return "<div style='padding: 10px; background-color: #2196F3; color: white; margin-bottom: 20px;'>No event found with ID {$event_id}, or deletion failed.</div>";
        }

        $stmt->close();
        $conn->close();
    } else {
        return "<div style='padding: 10px; background-color: #ff9800; color: white; margin-bottom: 20px;'>Invalid request.</div>";
    }
}

// ----BUSINESS LOGIC---------------------------------
$tpagecontent = createPage(); // Execute the function and capture output in $tpagecontent

// ----BUILD OUR HTML PAGE----------------------------
$tindexpage = new MasterPage("Home Page");
$tindexpage->setDynamic1($tpagecontent);
$tindexpage->renderPage();

?>
