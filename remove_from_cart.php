<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId']; // รับ ID สินค้าที่ต้องการลบ

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]); // ลบสินค้าจากตะกร้า
    }

    header('Location: cart.php'); // รีเฟรชหน้าตะกร้า
    exit();
}
?>
