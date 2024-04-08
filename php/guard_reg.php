<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$servername = "";
$username = "";
$password = "";
$db_name = "";

// 创建连接
$conn = new mysqli($servername, $username, $password, $db_name);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

header('Content-Type: application/json');

// 接收表单数据
$user_username = $_POST['username'];
$user_password = $_POST['password']; // 应该使用password_hash函数来加密密码
$user_email = $_POST['email'];
$user_phone = $_POST['phone'];
$user_id_card = $_POST['id']; // 注意：这里的'name'属性应该是'ID'，但为了避免混淆使用'id'
$user_firstname = $_POST['firstname']; // 接收firstname
$user_lastname = $_POST['lastname']; // 接收lastname

// 检查用户名是否已存在
$sql = "SELECT username FROM condoQR WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // 如果用户名已存在
    echo json_encode(['success' => false, 'error' => 'username_exists']);
    $stmt->close();
    $conn->close();
    exit();
}

// 密码加密
$hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

// 插入新用户
$sql = $conn->prepare("INSERT INTO condoQR (username, password, email, phone, id_card, firstname, lastname, occu) VALUES (?, ?, ?, ?, ?, ?, ?, 'guard')");
$sql->bind_param("ssssiss", $user_username, $hashed_password, $user_email, $user_phone, $user_id_card, $user_firstname, $user_lastname);

// 执行
if ($sql->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '注册失败，数据库错误。']);
}

$sql->close();
$conn->close();
?>
