<?php
session_start();
require 'db_connection.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $sweetness = isset($_POST['sweetness']) ? $_POST['sweetness'] : 'ปกติ'; // ค่าเริ่มต้น
    $description = "ระดับความหวาน: " . $sweetness; // บันทึกเป็นข้อความใน Description

    if ($productId > 0) {
        // ตรวจสอบว่าสินค้ามีอยู่ในฐานข้อมูลหรือไม่
        $query = "SELECT * FROM products WHERE productId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // ตรวจสอบว่า $_SESSION['cart'] เป็น array หรือไม่
            if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // เพิ่มสินค้าเข้าไปในตะกร้า โดยเก็บระดับความหวานใน Description
            $_SESSION['cart'][$productId] = [
                'quantity' => $quantity,
                'description' => $description
            ];

            // ✅ แสดง alert และ redirect กลับไปหน้า member.php
            echo "<script>
                    alert('เพิ่มสินค้าในตะกร้าเรียบร้อย!');
                    window.location.href = 'member.php';
                  </script>";
            exit();

        } else {
            echo "<script>
                    alert('สินค้าไม่ถูกต้อง');
                    window.location.href = 'member.php';
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                alert('ไม่มีรหัสสินค้า');
                window.location.href = 'member.php';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('คำขอไม่ถูกต้อง');
            window.location.href = 'member.php';
          </script>";
    exit();
}
?>
