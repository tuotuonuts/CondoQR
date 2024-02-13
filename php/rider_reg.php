<?php
$servername = "tuotuohome.asia";
$username = "tuotuo";
$password = "zrd00531";
$db_name = "mydatabase";

// 创建连接
$conn = new mysqli($servername, $username, $password, $db_name);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 接收表单数据
$user_username = $_POST['username'];
$user_password = $_POST['password']; // 应该使用password_hash函数来加密密码
$user_email = $_POST['email'];
$user_phone = $_POST['phone'];
$user_id_card = $_POST['id']; // 注意：这里的'name'属性应该是'ID'，但为了避免混淆使用'id'

// 密码加密
$hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

// 检查是否至少有一个其他字段不为空
if (empty($user_username) && empty($user_password) && empty($user_email) && empty($user_phone) && empty($user_id_card)) {
    echo "注册失败：至少需要提供一个字段值除了职业类别。";
} else {
    // 准备并绑定参数
    $sql = $conn->prepare("INSERT INTO condoQR (username, password, email, phone, id_card, occu) VALUES (?, ?, ?, ?, ?, 'rider')");
    $sql->bind_param("sssss", $user_username, $hashed_password, $user_email, $user_phone, $user_id_card);

    // 执行
    if ($sql->execute()) {
        echo "注册成功！";
    } else {
        echo "错误: " . $sql->error;
    }

    $sql->close();
}

$conn->close();
?>
