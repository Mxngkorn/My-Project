<?php
include 'db_connection.php';

if (isset($_GET['orderNo'])) {
    $orderNo = $_GET['orderNo'];

    $sql = "SELECT p.Name, o.Quantity, o.Descriptions  
            FROM isorder o
            JOIN products p ON o.ProductId = p.ProductId
            WHERE o.OrderNo = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $orderNo);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
</head>
<body>
    <div class="container mt-5">
        <h2>รายละเอียดคำสั่งซื้อ: <?php echo htmlspecialchars($orderNo); ?></h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th>จำนวน</th>
                    <th>ระดับความหวาน</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                        <td><?php echo isset($row['Descriptions']) ? htmlspecialchars($row['Descriptions']) : 'ไม่ได้ระบุ'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="order_management.php" class="btn btn-primary">🔙 กลับ</a>
    </div>
</body>
</html>
