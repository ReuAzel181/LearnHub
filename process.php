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

// Get data from POST request
$moduleName = $_POST['moduleName'];
$thumbnailUrl = $_POST['thumbnailUrl'];
$note = $_POST['note'];
$created_at = date('Y-m-d H:i:s');

// Insert data into database
$sql = "INSERT INTO modules (name, thumbnail_url, note, created_at) VALUES ('$moduleName', '$thumbnailUrl', '$note', '$created_at')";

if ($conn->query($sql) === TRUE) {
    echo "New module created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
