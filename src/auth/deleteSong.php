<?php
include(__DIR__ . "/../utils/dbConnection.php");
include(__DIR__ . "/auth.php");

// Kiểm tra trạng thái đăng nhập
if (!isset($authenticated) || !$authenticated) {
    header("Location: ./login.php");
    exit();
}

// Kiểm tra quyền quản trị
if (!isset($admin) || !$admin) {
    header("Location: ./unauth.php");
    exit();
}

// Kiểm tra id hợp lệ
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: editSong.php?error=invalid_id");
    exit();
}

$id = intval($_GET['id']);

// Sử dụng Prepared Statement để xóa bài hát
$sql = "DELETE FROM songs WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    header("Location: editSong.php");
    exit();
} else {
    echo "Error deleting the song.";
}
?>
