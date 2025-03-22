<?php
session_start(); // เริ่มต้น session

// ลบข้อมูลทั้งหมดใน session
session_unset();  // ลบข้อมูล session ที่ถูกตั้งค่าไว้ทั้งหมด
session_destroy(); // ทำลาย session

// เปลี่ยนเส้นทางไปยังหน้า login หรือ home
header("Location: index.php"); // เปลี่ยนเส้นทางไปหน้า login
exit();
?>
