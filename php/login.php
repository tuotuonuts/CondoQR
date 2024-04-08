<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 假设这是您的数据库连接信息
$servername = "";
$username = "";
$password = "";
$db_name = "";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $db_name);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

header('Content-Type: application/json');

// 指定日志文件路径
$logFilePath = "/www/wwwroot/ts.tuotuohome.asia/erlog.log";

// 记录POST数据到指定的日志文件
$logData = "Received POST data: " . var_export($_POST, true) . "\n";
file_put_contents($logFilePath, $logData, FILE_APPEND);

// 获取来自前端的用户名和密码
$user_username = isset($_POST['username']) ? $_POST['username'] : '';
$user_password = isset($_POST['password']) ? $_POST['password'] : '';

// 如果username或password为空，则直接返回错误
if (empty($user_username) || empty($user_password)) {
    echo json_encode(['success' => false, 'message' => '用户名和密码不能为空']);
    exit;
}

// 防止SQL注入
$user_username = $conn->real_escape_string($user_username);
$user_password = $conn->real_escape_string($user_password);

// 查询数据库，检查用户是否存在
$sql = "SELECT * FROM condoQR WHERE username = '$user_username'";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['success' => false, 'message' => '查询数据库时出错: ' . $conn->error]);
    $conn->close();
    exit;
}

if ($result->num_rows > 0) {
    // 检查密码是否匹配
    $row = $result->fetch_assoc();
    if (password_verify($user_password, $row["password"])) {
        // 登录成功，同时返回用户职业
        echo json_encode(['success' => true, 'message' => '登录成功', 'occupation' => $row["occu"]]);
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
