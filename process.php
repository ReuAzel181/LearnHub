<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "learnhub_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'] ?? '';
$icon = $_POST['icon'] ?? '';
$note = $_POST['note'] ?? '';
$date = date('Y-m-d');

// Log received data
file_put_contents('php://stderr', "Received data: name=$name, icon=$icon, note=$note, date=$date\n");

// Prepare and bind the statement
$stmt = $conn->prepare("INSERT INTO modules (name, icon, note, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $icon, $note, $date);

// Insert data into database
$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'New module created successfully';
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to add module: ' . $stmt->error;
}

// Log SQL query
file_put_contents('php://stderr', "SQL query: " . $stmt->sqlstate . "\n");

// Close statement and connection
$stmt->close();
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
