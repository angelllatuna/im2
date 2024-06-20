<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookvault";

// Establishing connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling book addition to favorites
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Validate and sanitize book ID
    $id = $_POST['id'];

    // Get user ID from session
    $userid = $_SESSION['userid'];

    // Prepare SQL statement to insert into favorites
    $sql = "INSERT INTO favorites (userid, id, added_at) VALUES (?, ?, CURRENT_TIMESTAMP)
            ON DUPLICATE KEY UPDATE added_at = CURRENT_TIMESTAMP";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userid, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to main.php after successful insertion
        header("Location: main.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
