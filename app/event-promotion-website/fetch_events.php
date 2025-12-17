<?php

include("connection.php");  // Ensure your database connection is correctly set up.
$field = getParam('field');
$value = getParam('value');

error_log("Received field: $field with value: $value");
error_log("Field: $field, Value: $value");

// Function to safely fetch GET parameters
function getParam($param) {
    return isset($_GET[$param]) ? $_GET[$param] : null;
}

// Define the function to filter events based on a specified field and value
function filterEvents($mysqli, $field, $value) {
    $fieldMap = [
        'date_time' => 'date_time',
        'artist'    => 'artist_details',
        'category'  => 'category'
    ];

    // Check if the provided field is valid and not empty
    if (!isset($fieldMap[$field]) || empty($value)) {
        return "Invalid filter or no value provided.";
    }

    $columnName = $fieldMap[$field];
    $value = $mysqli->real_escape_string($value); // Sanitize the value to prevent SQL Injection
    $query = "SELECT * FROM events WHERE {$columnName} = '{$value}' ORDER BY date_time ASC";

    $result = $mysqli->query($query);
    if ($result) {
        $html = "<div class='event-list'>";
        while ($row = $result->fetch_assoc()) {
            $html .= "<div class='event'>";
            $html .= "<h3>" . htmlspecialchars($row['name']) . "</h3>";
            foreach ($row as $key => $val) {
                $html .= "<p>" . ucfirst($key) . ": " . htmlspecialchars($val) . "</p>";
            }
            $html .= "</div>";  // End of event div
        }
        $html .= "</div>";  // End of event list div
        return $html ?: "No events found matching your filter."; // Ternary shorthand for empty check
    } else {
        return "Error executing query: " . $mysqli->error; // Provide specific error
    }
}

// Example usage
echo filterEvents($mysqli, $field, $value);

?>
