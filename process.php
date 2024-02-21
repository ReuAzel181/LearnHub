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

// Check if the request is for adding or deleting a module
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $icon = $_POST['icon'] ?? '';
    $note = $_POST['note'] ?? '';
    $date = date('Y-m-d');

    // Prepare and bind the statement for adding a module
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

    // Close statement
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
    // Get the module name to delete
    $moduleName = $_POST['name'] ?? '';

    // Prepare and bind the statement for deleting a module
    $stmt = $conn->prepare("DELETE FROM modules WHERE name = ?");
    $stmt->bind_param("s", $moduleName);

    // Delete module from database
    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Module deleted successfully';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to delete module: ' . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
