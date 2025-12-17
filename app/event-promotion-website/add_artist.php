<?php
include("connection.php"); // Ensure this file correctly sets up $conn

$response = ['success' => false, 'message' => '', 'name' => '', 'bio' => '', 'social' => '', 'picture' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $bio = htmlspecialchars(strip_tags($_POST['bio']));
    $social = htmlspecialchars(strip_tags($_POST['social']));
    $picture = $_FILES['picture']['name'];
    $uploadPath = "uploads/" . basename($picture);

    // Check for errors in the file upload
    if ($_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadPath)) {
            // Prepare SQL statement to insert data
            $sql = "INSERT INTO artist (name, biography, social_media, picture) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("ssss", $name, $bio, $social, $picture);
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Artist added successfully";
                    $response['name'] = $name;
                    $response['bio'] = $bio;
                    $response['social'] = $social;
                    $response['picture'] = $picture;
                } else {
                    $response['message'] = "Error executing query: " . htmlspecialchars($stmt->error);
                }
                $stmt->close();
            } else {
                $response['message'] = "Error preparing query: " . htmlspecialchars($conn->error);
            }
        } else {
            $response['message'] = "Failed to move uploaded file.";
        }
    } else {
        $response['message'] = "File upload error: " . $_FILES['picture']['error'];
    }
    $conn->close();
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
