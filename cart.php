<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('Please login before proceeding to checkout.'); window.location.href='member.php';</script>";
    exit();
}

$userId = $_SESSION['userid'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalPrice = 0;

// คำนวณราคารวม
foreach ($cart as $productId => $item) {
    $quantity = $item['quantity']; // ดึงจำนวนสินค้า
    $sweetness = $item['description']; // ระดับความหวาน
    $query = "SELECT Price FROM products WHERE ProductId = " . mysqli_real_escape_string($conn, $productId);
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $totalPrice += $row['Price'] * $quantity;
    }
}

// บันทึกค่า Total Price ลงใน SESSION
$_SESSION['totalPrice'] = $totalPrice;

// ลบสินค้าออกจากตะกร้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
    $removeId = $_POST['productId'];
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php");
    exit();
}

// เพิ่มจำนวนสินค้าในตะกร้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['increase'])) {
    $increaseId = $_POST['productId'];
    $_SESSION['cart'][$increaseId]['quantity']++;
    header("Location: cart.php");
    exit();
}

// ลดจำนวนสินค้าในตะกร้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['decrease'])) {
    $decreaseId = $_POST['productId'];
    if ($_SESSION['cart'][$decreaseId]['quantity'] > 1) {
        $_SESSION['cart'][$decreaseId]['quantity']--;
    } else {
        unset($_SESSION['cart'][$decreaseId]);
    }
    header("Location: cart.php");
    exit();
}
// ดำเนินการสั่งซื้อ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['paymentMethod'])) {
    // Check if cart is empty
    if (empty($cart)) {
        echo "<script>alert('ตะกร้าว่าง.'); window.location.href='member.php';</script>";
        exit();
    }

    $paymentMethod = "เงินสด";
    $paymentStatus = "ยังไม่ชำระ";
    $orderNo = "ORD" . time();
    $status = "กำลังทำ";

    // Print the variables for debugging
    echo "Payment Method: " . $paymentMethod . "<br>";
    echo "Payment Status: " . $paymentStatus . "<br>";
    echo "Order No: " . $orderNo . "<br>";

    // บันทึกข้อมูลลงตาราง payments
    $query = "INSERT INTO payments (UserId, PaymentMode, Amount, PaymentStatus, SlipImageUrl) 
              VALUES ('" . mysqli_real_escape_string($conn, $userId) . "', 
                      '" . mysqli_real_escape_string($conn, $paymentMethod) . "', 
                      '" . mysqli_real_escape_string($conn, $totalPrice) . "', 
                      '" . mysqli_real_escape_string($conn, $paymentStatus) . "', 
                      '" . mysqli_real_escape_string($conn, $_POST['slipImageUrl']) . "')";
    
    echo "Executing query: " . $query . "<br>"; // Debug the query
    
    if (mysqli_query($conn, $query)) {
        $paymentId = mysqli_insert_id($conn);  // Get the inserted payment ID
        echo "Payment successfully inserted with ID: " . $paymentId . "<br>";
    } else {
        echo "Error inserting payment: " . mysqli_error($conn) . "<br>";
        exit();  // Exit to prevent further execution if the payment insertion fails
    }

    // บันทึกข้อมูลลงตาราง isorder พร้อมระดับความหวาน
    foreach ($cart as $productId => $item) {
        $quantity = $item['quantity'];
        $description = $item['description']; // ดึงระดับความหวานจาก session

        // Print each product's details for debugging
        echo "Product ID: " . $productId . "<br>";
        echo "Quantity: " . $quantity . "<br>";
        echo "Description: " . $description . "<br>";

        $query = "INSERT INTO isorder (OrderNo, ProductId, Quantity, UserId, Statuss, PaymentId, Descriptions) 
                  VALUES ('" . mysqli_real_escape_string($conn, $orderNo) . "', 
                          '" . mysqli_real_escape_string($conn, $productId) . "', 
                          '" . mysqli_real_escape_string($conn, $quantity) . "', 
                          '" . mysqli_real_escape_string($conn, $userId) . "', 
                          '" . mysqli_real_escape_string($conn, $status) . "', 
                          '" . mysqli_real_escape_string($conn, $paymentId) . "', 
                          '" . mysqli_real_escape_string($conn, $description) . "')";

        echo "Executing query for isorder: " . $query . "<br>";  // Debug the query for isorder
        
        if (mysqli_query($conn, $query)) {
            echo "Order for Product ID " . $productId . " inserted successfully.<br>";
        } else {
            echo "Error inserting order for Product ID " . $productId . ": " . mysqli_error($conn) . "<br>";
        }
    }

    // Clear cart and redirect to member page
    unset($_SESSION['cart']);
    echo "<script>alert('สั่งซื้อสำเส็จ'); window.location.href='member.php';</script>";
    exit();
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">🛒 ตะกร้า</h1>

    <?php if (empty($cart)) : ?>
        <div class="alert alert-warning text-center">
            Your cart is empty. <a href="member.php" class="btn btn-primary btn-sm">Go Shopping</a>
        </div>
    <?php else : ?>
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>สินค้า</th>
                    <th>จำนวน</th>
                    <th>ระดับความหวาน</th>
                    <th>ราคารวม</th>
                    <th>ปุ่ม</th> 
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cart as $productId => $item) : ?>
                <?php
                $quantity = $item['quantity'];
                $description = $item['description'];

                $query = "SELECT * FROM products WHERE ProductId = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $itemTotal = $product['Price'] * $quantity;
                    $totalPrice += $itemTotal;
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($product['Name']) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="productId" value="<?= $productId ?>">
                            <button type="submit" name="decrease" class="btn btn-danger btn-sm">-</button>
                            <?= htmlspecialchars($quantity) ?>
                            <button type="submit" name="increase" class="btn btn-danger btn-sm">+</button>
                        </form>
                    </td>
                    <td><?= htmlspecialchars($description) ?></td>
                    <td>฿<?= number_format($itemTotal, 2) ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="productId" value="<?= $productId ?>">
                            <button type="submit" name="remove" class="btn btn-danger btn-sm">ลบ</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <h2 class="text-end"><?= number_format($_SESSION['totalPrice'] ?? 0, 2) ?> บาท</h2>

        <div class="d-flex justify-content-between">
            <a href="member.php" class="btn btn-secondary">🛍 กลับหน้าเลือกสินค้า</a>
            <button class="btn btn-success" onclick="showPaymentPopup()">สั่งซื้อ</button>
        </div>
    <?php endif; ?>
</div>

<!-- Popup Payment -->
<div id="paymentPopup" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">เลือกช่องทางชำระ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <button type="submit" name="paymentMethod" value="cash" class="btn btn-primary w-100">💵 เงินสด</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" onclick="showTransferPopup()">🏦 โอนเงิน</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Popup for QR Code & Upload -->
<div id="transferPopup" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">อัปโหลดสลิป</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <img src="images/qrcode1.jpg" class="img-fluid mb-3">
                <form method="POST" action="upload_payment.php" enctype="multipart/form-data">
                    <input type="hidden" name="userId" value="<?php echo $_SESSION['userid']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $totalPrice; ?>">
                    <input type="file" name="paymentSlip" class="form-control" required>
                    <button type="submit" name="uploadSlip" class="btn btn-success mt-2">ยืนยัน</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showPaymentPopup() { new bootstrap.Modal(document.getElementById('paymentPopup')).show(); }
function showTransferPopup() { new bootstrap.Modal(document.getElementById('transferPopup')).show(); }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>