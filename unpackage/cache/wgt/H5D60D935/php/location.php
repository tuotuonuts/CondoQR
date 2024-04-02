<?php
// 连接数据库参数
$host = 'localhost'; // 数据库服务器地址
$username = 'your_database_username'; // 数据库用户名
$password = 'your_database_password'; // 数据库密码
$database = 'your_database_name'; // 数据库名

// 创建数据库连接
$conn = new mysqli($host, $username, $password, $database);

// 检查连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 从POST请求中获取username和address
$postData = json_decode(file_get_contents('php://input'), true);
$userName = $conn->real_escape_string($postData['username']);
$address = $conn->real_escape_string($postData['address']);

// 更新地址的SQL语句
$sql = "UPDATE users SET address = '$address' WHERE username = '$userName'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("message" => "Address updated successfully!"));
} else {
    echo json_encode(array("message" => "Error updating address: " . $conn->error));
}

// 关闭数据库连接
$conn->close();
?>
