<?php
$servername = "localhost"; // hoặc IP của máy chủ
$username = "root"; // tên đăng nhập database
$password = ""; // mật khẩu database
$dbname = "myspotify"; // tên cơ sở dữ liệu

// Tạo kết nối
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
