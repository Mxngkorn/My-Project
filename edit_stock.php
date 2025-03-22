<?php
include('db_connection.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามี stock_id หรือไม่
if (!isset($_GET['stock_id']) || empty($_GET['stock_id'])) {
    die("ไม่พบรหัสสต็อกที่ต้องการแก้ไข");
}

$stock_id = intval($_GET['stock_id']);

// ดึงข้อมูลสต็อกจากฐานข้อมูล
$sql = "SELECT * FROM stock WHERE StockId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $stock_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("ไม่พบข้อมูลสต็อก");
}

$row = $result->fetch_assoc();

// เมื่อกดปุ่มบันทึก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $quantity = intval($_POST['quantity']);
    

    // อัปเดตข้อมูล
    $update_sql = "UPDATE stock SET Name=?, Quantity=? WHERE StockId=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sii", $name, $quantity, $stock_id);
    if ($update_stmt->execute()) {
        echo "<script>alert('อัปเดตข้อมูลเรียบร้อย'); window.location.href='add_stock.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการอัปเดต');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขวัตถุดิบ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>แก้ไขข้อมูลวัตถุดิบ</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">ชื่อวัตถุดิบ</label>
            <input type="text" name="name" class="form-control" value="<?php echo $row['Name']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">จำนวน</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo $row['Quantity']; ?>" required>
        </div>
        <div class="mb-3">
        </div>
        <button type="submit" class="btn btn-primary">บันทึก</button>
        <a href="add_stock.php" class="btn btn-secondary">ยกเลิก</a>
    </form>
</div>
</body>
</html>
