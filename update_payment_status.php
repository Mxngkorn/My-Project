<?php
include 'db_connection.php';

if (isset($_GET['orderNo'])) {
    $orderNo = $_GET['orderNo'];

    $sql = "UPDATE payments 
            SET PaymentStatus = 'ชำระเรียบร้อย' 
            WHERE PaymentId = (SELECT PaymentId FROM isorder WHERE OrderNo = ? LIMIT 1)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderNo);
    if ($stmt->execute()) {
        echo "<script>alert('อัปเดตสถานะการชำระเงินเรียบร้อยแล้ว!'); window.location.href='order_management.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด!'); window.history.back();</script>";
    }
}
?>
