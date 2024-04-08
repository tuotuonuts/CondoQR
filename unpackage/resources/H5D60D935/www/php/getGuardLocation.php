<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "tuotuohome.asia";
$username = "tuotuo";
$password = "zrd00531";
$db_name = "mydatabase";

// 创建连接
$conn = new mysqli($servername, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 读取 JSON 输入
$input = json_decode(file_get_contents('php://input'), true);

// 获取守卫用户名
$guard_username = $input['username'];

// Prepare and execute the SQL statement
if ($stmt = $conn->prepare("SELECT address FROM condoQR WHERE username = ?")) {
    $stmt->bind_param("s", $guard_username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Successfully fetched the location
        echo json_encode(array("success" => true, "location" => $row['address']));
    } else {
        // Guard username not found
        echo json_encode(array("success" => false, "message" => "Guard username not found."));
    }
    $stmt->close();
} else {
    // SQL statement preparation failed
    echo json_encode(array("success" => false, "message" => "Failed to prepare SQL statement."));
}

$conn->close();
?>
