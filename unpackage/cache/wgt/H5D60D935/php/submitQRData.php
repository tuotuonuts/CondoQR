<?php
// Database configuration
$host = 'localhost'; // or your host
$dbname = 'your_database_name';
$username = 'your_database_username';
$password = 'your_database_password';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode the JSON body from the request
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the received data
    if (isset($data['username'], $data['time'], $data['scannerUsername'], $data['scannerLocation'])) {
        $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
        $time = filter_var($data['time'], FILTER_SANITIZE_STRING);
        $scannerUsername = filter_var($data['scannerUsername'], FILTER_SANITIZE_STRING);
        $scannerLocation = filter_var($data['scannerLocation'], FILTER_SANITIZE_STRING);

        // Prepare an insert statement
        $sql = "INSERT INTO qr_scan_logs (username, scan_time, scanner_username, scanner_location) VALUES (:username, :scan_time, :scanner_username, :scanner_location)";

        try {
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':scan_time', $time, PDO::PARAM_STR);
            $stmt->bindParam(':scanner_username', $scannerUsername, PDO::PARAM_STR);
            $stmt->bindParam(':scanner_location', $scannerLocation, PDO::PARAM_STR);

            // Execute the prepared statement
            $stmt->execute();

            // Send a success response
            echo json_encode(['success' => true]);
        } catch(PDOException $e) {
            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
        }
    } else {
        // Send an error response
        echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
    }
} else {
    // Not a POST request
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
