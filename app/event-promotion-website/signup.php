<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();  // Start the session at the very beginning
include 'connection.php';  // Include your DB connection
include("api/api.inc.php");  // Include your Website API

$registration_message = "";  // Initialize registration message variable

// Handle POST request before any HTML
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);  // Get the username, trimming any whitespace
    $password = $_POST['password'];  // Get the password

    // Check if the username already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $registration_message = "Username already exists. Please choose another.";
    } else {
        // If the username does not exist, hash the password and insert the new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashedPassword]);
        $registration_message = 'User registered successfully! Please <a href="login.php">login</a>.';
    }
}

// ----PAGE GENERATION LOGIC---------------------------
function createPage()
{
    return <<<PAGE
    <!-- Include our CSS style sheets -->
    <link href="css/login.css" rel="stylesheet"> 
    <h2>Registration Page</h2>
    PAGE;
}

$tpagecontent = createPage();

// Set dynamic content
$registrationBoxContent = <<<HTML
<div class="login-box">
    <h2>Register</h2>
    <form class="login-form" action="" method="post"> <!-- Form submits to the same page -->
        <input type="text" name="username" placeholder="Username" required>
        <div class="password-wrapper" style="position:relative;">
            <input type="password" name="password" id="password" placeholder="Password" style="padding-right:30px;">
            <button type="button" onclick="togglePasswordVisibility()" style="position:absolute; right:0; top:0; border:none; background:none; cursor:pointer;">👁️</button>
        </div>
         <input type="submit" value="Register"><br><br>
        <!-- Display registration message if set -->
        <div class="error">{$registration_message}</div>
    </form>
    <script>
function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
}
</script>
</div>
HTML;

// ----BUILD OUR HTML PAGE----------------------------
$tindexpage = new MasterPage("Registration Page");
$tindexpage->setDynamic1($tpagecontent);
$tindexpage->setDynamic2($registrationBoxContent);
$tindexpage->renderPage();
?>
