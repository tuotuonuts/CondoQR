<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// 连接数据库参数
$servername = "";
$username = "";
$password = "";
$db_name = "";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $db_name);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 从POST请求中获取username和address
$postData = json_decode(file_get_contents('php://input'), true);
$userName = $conn->real_escape_string($postData['username']);
$address = $conn->real_escape_string($postData['address']);

// 更新地址的SQL语句
$sql = "UPDATE condoQR SET address = '$address' WHERE username = '$userName'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("message" => "Address updated successfully!"));
} else {
    echo json_encode(array("message" => "Error updating address: " . $conn->error));
}

// 关闭数据库连接
$conn->close();
?>
