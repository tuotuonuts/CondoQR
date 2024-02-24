<?php
// 假设这是您的数据库连接信息
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

header('Content-Type: application/json');

// 获取来自前端的用户名和密码
$user_username = $_POST['username'];
$user_password = $_POST['password'];

// 防止SQL注入
$user_username = $conn->real_escape_string($user_username);
$user_password = $conn->real_escape_string($user_password);

// 查询数据库，检查用户是否存在
$sql = "SELECT * FROM users WHERE username = '$user_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 检查密码是否匹配
    $row = $result->fetch_assoc();
    if (password_verify($user_password, $row["password"])) {
        // 登录成功
        echo json_encode(['success' => true, 'message' => '登录成功']);
    } else {
        // 密码错误
        echo json_encode(['success' => false, 'message' => '密码错误']);
    }
} else {
    // 用户不存在
    echo json_encode(['success' => false, 'message' => '用户不存在']);
}

$conn->close();
?>
