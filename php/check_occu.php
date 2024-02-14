<?php
// 数据库连接信息
$servername = "your_server_name";
$username = "your_database_username";
$password = "your_database_password";
$dbname = "your_database_name";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 设置内容类型为 JSON
header('Content-Type: application/json');

// 获取来自请求的用户名
$user_username = isset($_POST['username']) ? $_POST['username'] : '';

// 防止SQL注入
$user_username = $conn->real_escape_string($user_username);

// 查询数据库，检查用户是否存在并获取其职业（occu）
$sql = "SELECT occu FROM users WHERE username = '$user_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 用户存在，获取职业信息
    $row = $result->fetch_assoc();
    echo json_encode(['success' => true, 'occupation' => $row["occu"]]);
} else {
    // 用户不存在
    echo json_encode(['success' => false, 'message' => '用户不存在']);
}

// 关闭数据库连接
$conn->close();
?>
