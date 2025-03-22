<?php
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล
session_start(); // เริ่ม session ใหม่

if (!isset($_SESSION['roleuser']) || ($_SESSION['roleuser'] != 'ผู้ดูแล' && $_SESSION['roleuser'] != 'พนักงาน')) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT 
    o.OrderNo, 
    GROUP_CONCAT(p.Name SEPARATOR ', ') AS ProductNames, 
    SUM(o.Quantity) AS TotalQuantity, 
    u.User_Name AS UserName, 
    o.Statuss, 
    pm.PaymentMode, 
    pm.PaymentStatus, 
    pm.Amount,
    pm.SlipImageUrl,
    GROUP_CONCAT(o.Descriptions SEPARATOR ', ') AS SweetnessLevels
FROM isorder o
JOIN products p ON o.ProductId = p.ProductId
JOIN users u ON o.UserId = u.UserId
JOIN payments pm ON o.PaymentId = pm.PaymentId
WHERE o.Statuss != 'เสร็จสิ้น'  -- เพิ่มการกรองสถานะที่ไม่ใช่ 'เสร็จสิ้น'
GROUP BY o.OrderNo, u.User_Name, o.Statuss, pm.PaymentMode, pm.PaymentStatus, pm.Amount, pm.SlipImageUrl;
";
// ดึงข้อมูลคำสั่งซื้อที่ยังไม่เสร็จสิ้น พร้อมข้อมูล PaymentMode, PaymentStatus และ Amount

$result = $conn->query($sql);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
    <style>
        .slip-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: transform 0.2s;
        }
        .slip-thumbnail:hover {
            transform: scale(1.2);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">📋 จัดการคำสั่งซื้อ </h2>

        <div class="card shadow p-3 mb-5 bg-white rounded">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Order No</th>
                            <th>จำนวน</th>
                            <th>ลูกค้า</th>
                            <th>สถานะ</th>
                            <th>ช่องทางชำระ</th>
                            <th>สถานะชำระ</th>
                            <th>ยอดชำระ (บาท)</th>
                            <th>สลิปการโอน</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0) { ?>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr class="text-center">
                                    <td><?php echo $row['OrderNo']; ?></td>
                                    <td><?php echo $row['TotalQuantity']; ?></td>
                                    <td><?php echo $row['UserName']; ?></td>
                                    <td><span class="badge bg-warning text-dark">⏳ <?php echo $row['Status']; ?></span></td>
                                    <td><?php echo $row['PaymentMode']; ?></td>
                                    <td>
                                        <?php if ($row['PaymentStatus'] == "ชำระเรียบร้อย") { ?>
                                            <span class="badge bg-success">✔ <?php echo $row['PaymentStatus']; ?></span>
                                        <?php } else { ?>
                                            <button class="btn btn-sm btn-danger" onclick="updatePaymentStatus('<?php echo $row['OrderNo']; ?>')">💲 ยังไม่ชำระ</button>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo number_format($row['Amount'], 2); ?></td>
                                    <td>
                                     
                                        <?php if (!empty($row['SlipImageUrl'])) { ?>
                                            <img src="<?php echo htmlspecialchars($row['SlipImageUrl']); ?>"
                                                class="slip-thumbnail" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#slipModal"
                                                onclick="showSlip('<?php echo htmlspecialchars($row['SlipImageUrl']); ?>')">
                                        <?php } else { ?>
                                            <span class="text-muted">ไม่มีสลิป</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-sm" onclick="updateOrderStatus('<?php echo $row['OrderNo']; ?>')">✔ เสร็จสิ้น</button>
                                        <button class="btn btn-info btn-sm" onclick="showOrderDetails('<?php echo $row['OrderNo']; ?>')">🔍 รายละเอียด</button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">🎉 ไม่มีคำสั่งซื้อที่กำลังดำเนินการ</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php
                if ($_SESSION['roleuser'] == 'ผู้ดูแล') {
                    echo '<a href="admin.php" class="btn btn-secondary">กลับไปหน้าผู้ดูแล</a>';
                } elseif ($_SESSION['roleuser'] == 'พนักงาน') {
                    echo '<a href="employee.php" class="btn btn-secondary">กลับไปหน้าหลัก</a>';
                }
                ?>
            </div>
        </div>
    </div>
 
    <!-- Modal สำหรับแสดงรูปสลิป -->
    <div class="modal fade" id="slipModal" tabindex="-1" aria-labelledby="slipModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="slipModalLabel">สลิปการโอนเงิน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="SlipImageUrl" src="<?php echo htmlspecialchars($row['SlipImageUrl']); ?>" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateOrderStatus(orderNo) {
        if (confirm("คุณต้องการเปลี่ยนสถานะเป็น 'เสร็จสิ้น' หรือไม่?")) {
            window.location.href = "update_status.php?orderNo=" + orderNo;
        }
    }

    function updatePaymentStatus(orderNo) {
        if (confirm("คุณต้องการเปลี่ยนสถานะการชำระเงินเป็น 'ชำระเรียบร้อย' หรือไม่?")) {
            window.location.href = "update_payment_status.php?orderNo=" + orderNo;
        }
    }

    function showOrderDetails(orderNo) {
        window.location.href = "order_details.php?orderNo=" + orderNo;
    }

    function showSlip(imageUrl) {
    document.getElementById("SlipImageUrl").src = imageUrl;
}
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>