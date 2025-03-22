<?php
include 'db_connection.php';

if (isset($_GET['orderNo'])) {
    $orderNos = explode(',', $_GET['orderNo']); // แยก orderNo ออกเป็น array
    $orderNos = array_map('trim', $orderNos);  // ตัดช่องว่างเผื่อมี

    if (empty($orderNos)) {
        echo "<script>alert('ไม่มี Order ID ที่ถูกต้อง ❌'); window.location.href = 'order_management.php';</script>";
        exit();
    }

    // สร้างเครื่องหมายคำถามสำหรับ IN()
    $placeholders = implode(',', array_fill(0, count($orderNos), '?'));

    $sql = "UPDATE isorder SET Statuss = 'เสร็จสิ้น' WHERE orderNo IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    // สร้าง string type สำหรับ bind_param
    $types = str_repeat('s', count($orderNos));
    $stmt->bind_param($types, ...$orderNos);  // ใช้ "s" แทน "i" เพราะ orderNo เป็น VARCHAR

    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตสถานะสำเร็จ! ✅'); window.location.href = 'order_management.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด! ❌'); window.location.href = 'order_management.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
