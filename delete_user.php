<?php
session_start();
include 'db_connection.php';

// ตรวจสอบการรับค่า ID
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // ลบผู้ใช้ตาม ID
    $stmt = $conn->prepare("DELETE FROM users WHERE UserId = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete user.";
    }

    $stmt->close();
}

// กลับไปยังหน้าหลัก
header("Location: edit_users.php");
exit();
?>
