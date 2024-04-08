<?php
// 数据库配置
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

$guard = isset($_GET['guard']) ? $_GET['guard'] : '';

if (!empty($guard)) {
    // 防止SQL注入
    $guard = $conn->real_escape_string($guard);

    // 查询数据库
    $sql = "SELECT id, rider, QRtime, address, guard, scanTime FROM records WHERE guard = '$guard'";
    $result = $conn->query($sql);

    $records = [];
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $records]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No records found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Guard username not specified.']);
}

$conn->close();
?>
