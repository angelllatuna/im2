<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "bookvault";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['userId'];

// Prepare a statement to fetch user data
$stmt = $conn->prepare("SELECT userid, name, email, contact_number, address, birthday, username FROM users WHERE userid = ?");
$stmt->bind_param("i", $userId);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch user data
$user = null;
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Calculate age
    $user['age'] = date_diff(date_create($user['birthday']), date_create('today'))->y;
} else {
    $user = [
        'userid' => 'N/A',
        'name' => 'N/A',
        'email' => 'N/A',
        'contact_number' => 'N/A',
        'address' => 'N/A',
        'birthday' => 'N/A',
        'age' => 'N/A',
        'username' => 'N/A'
    ];
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">User Profile</h1>
        <ul class="list-group mb-3">
            <li class="list-group-item">Name: <span class="fw-bold"><?php echo htmlspecialchars($user['name']); ?></span></li>
            <li class="list-group-item">Email: <span class="fw-bold"><?php echo htmlspecialchars($user['email']); ?></span></li>
            <li class="list-group-item">Contact Number: <span class="fw-bold"><?php echo htmlspecialchars($user['contact_number']); ?></span></li>
            <li class="list-group-item">Address: <span class="fw-bold"><?php echo htmlspecialchars($user['address']); ?></span></li>
            <li class="list-group-item">Birthday: <span class="fw-bold"><?php echo htmlspecialchars($user['birthday']); ?></span></li>
            <li class="list-group-item">Age: <span class="fw-bold"><?php echo htmlspecialchars($user['age']); ?></span></li>
            <li class="list-group-item">Username: <span class="fw-bold"><?php echo htmlspecialchars($user['username']); ?></span></li>
        </ul>
    </div>

    <!-- Bootstrap JS Bundle (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cM03K5JdRmsKN/t8FzfGQ1ZXOlGIZ7Wv24e1LYD+47kxR/1D9t8L+BxNk3A2lQA5" crossorigin="anonymous"></script>
</body>
</html>
