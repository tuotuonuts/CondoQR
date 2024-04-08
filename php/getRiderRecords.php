<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");


$servername = "";
$username = "";
$password = "";
$db_name = "";

// 创建连接
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the script is being accessed via a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json'); // Specify the content type

    // Assuming you're getting the rider's name through a GET request for simplicity
    $riderName = $_GET['rider'];

    // Prepare the SQL statement
    $sql = "SELECT * FROM records WHERE rider = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => 'Failed to prepare statement']);
        exit();
    }
    $stmt->bind_param("s", $riderName);

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch all records and convert to JSON
        $records = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($records);
    } else {
        echo json_encode(['error' => 'Query execution failed']);
    }

    // Close connections
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
