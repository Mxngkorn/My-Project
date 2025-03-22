<?php
include 'db_connection.php';

session_start();
if (!isset($_SESSION['roleuser']) || ($_SESSION['roleuser'] != 'ผู้ดูแล' && $_SESSION['roleuser'] != 'พนักงาน')) {
    header("Location: login.php");
    exit();
}
// ตรวจสอบว่าผู้ใช้เลือกช่วงเวลาใด
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'custom';
if ($filter == 'today') {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d');
} elseif ($filter == 'week') {
    $startDate = date('Y-m-d', strtotime('-6 days')); // นับย้อนหลัง 7 วัน (รวมวันนี้)
    $endDate = date('Y-m-d');
} elseif ($filter == 'month') {
    $startDate = date('Y-m-01'); // เริ่มต้นเดือน
    $endDate = date('Y-m-d'); // วันนี้
} else {
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
}

// คำสั่ง SQL ดึงข้อมูลคำสั่งซื้อ
$sql = "SELECT o.OrderDetailId, o.OrderNo, p.Name AS ProductName, o.Quantity, u.User_Name AS UserName, o.Statuss, o.OrderDate, p.Price
    FROM isorder o
    JOIN products p ON o.ProductId = p.ProductId
    JOIN users u ON o.UserId = u.UserId
    WHERE o.Statuss = 'เสร็จสิ้น' 
    AND DATE(o.OrderDate) BETWEEN '$startDate' AND '$endDate'
    ORDER BY o.OrderDate DESC";

$result = $conn->query($sql);

// คำนวณยอดขายรวม, จำนวนคำสั่งซื้อ, จำนวนสินค้าขายได้
$totalSales = 0;
$totalOrders = 0;
$totalQuantity = 0;
$productSales = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalSales += ($row['Quantity'] * $row['Price']);
        $totalOrders++;
        $totalQuantity += $row['Quantity'];

        if (!isset($productSales[$row['ProductName']])) {
            $productSales[$row['ProductName']] = 0;
        }
        $productSales[$row['ProductName']] += $row['Quantity'];
    }
}

arsort($productSales);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">📊 รายงานการขาย</h2>

        <!-- ปุ่มเลือกช่วงเวลา -->
        <div class="d-flex justify-content-center mb-3">
            <button class="btn btn-outline-primary mx-1" onclick="setDateRange('today')">วันนี้</button>
            <button class="btn btn-outline-primary mx-1" onclick="setDateRange('week')">สัปดาห์นี้</button>
            <button class="btn btn-outline-primary mx-1" onclick="setDateRange('month')">เดือนนี้</button>
            <button class="btn btn-outline-secondary mx-1" onclick="setDateRange('custom')">กำหนดเอง</button>
        </div>

        <!-- ฟอร์มเลือกช่วงวันที่ -->
        <form method="GET" class="row g-3 mb-4">
            <input type="hidden" id="filter" name="filter" value="custom">
            <div class="col-md-4">
                <label class="form-label">📅 วันที่เริ่มต้น</label>
                <input type="text" name="start_date" id="start_date" class="form-control datepicker" value="<?php echo $startDate; ?>" autocomplete="off">
            </div>
            <div class="col-md-4">
                <label class="form-label">📅 วันที่สิ้นสุด</label>
                <input type="text" name="end_date" id="end_date" class="form-control datepicker" value="<?php echo $endDate; ?>" autocomplete="off">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">🔍 ค้นหา</button>
            </div>
        </form>

        <div class="row">
            <!-- สรุปยอดขาย -->
            <div class="col-md-4">
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <div class="card-body">
                        <h5 class="card-title">สรุปยอดขาย</h5>
                        <p class="card-text">💰 ยอดขายรวม: ฿<?php echo number_format($totalSales, 2); ?></p>
                        <p class="card-text">📦 จำนวนคำสั่งซื้อ: <?php echo $totalOrders; ?></p>
                        <h6 class="card-title mt-4">จำนวนสินค้าที่ขาย</h6>
                        <ul class="list-group">
                            <?php foreach ($productSales as $productName => $quantity) { ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo $productName; ?>
                                    <span class="badge bg-primary rounded-pill"><?php echo $quantity; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <!-- สินค้าขายดี 3 อันดับแรก -->
            <div class="col-md-12">
                <div class="card shadow p-3 mb-5  bg-white rounded">
                    <div class="card-body">
                        <h5 class="card-title">สินค้าขายดี 3 อันดับแรก</h5>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>อันดับ</th>
                                    <th>สินค้า</th>
                                    <th>จำนวน</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $rank = 1;
                                foreach (array_slice($productSales, 0, 3) as $productName => $quantity) { ?>
                                    <tr class="text-center">
                                        <td><?php echo $rank++; ?></td>
                                        <td><?php echo $productName; ?></td>
                                        <td><?php echo $quantity; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
            <!-- ตารางข้อมูล -->
            <div class="col-md-8">
                <div class="card shadow p-3 mb-5 bg-white rounded">
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Order No</th>
                                    <th>สินค้า</th>
                                    <th>จำนวน</th>
                                    <th>ลูกค้า</th>
                                    <th>ราคา</th>
                                    <th>วันที่</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0) { 
                                    $result->data_seek(0);
                                    while ($row = $result->fetch_assoc()) { 
                                ?>
                                    <tr class="text-center">
                                        <td><?php echo $row['OrderNo']; ?></td>
                                        <td><?php echo $row['ProductName']; ?></td>
                                        <td><?php echo $row['Quantity']; ?></td>
                                        <td><?php echo $row['UserName']; ?></td>
                                        <td>฿<?php echo number_format($row['Quantity'] * $row['Price'], 2); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['OrderDate'])); ?></td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">❌ ไม่มีข้อมูล</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="text-center mt-4">
                        <?php
         
                if ($_SESSION['roleuser'] == 'ผู้ดูแล') {
                    echo '<a href="admin.php" class="btn btn-secondary">กลับไปหน้าผู้ดูแล</a>';
                } elseif ($_SESSION['roleuser'] == 'พนักงาน') {
                    echo '<a href="employee.php" class="btn btn-secondary">กลับไปหน้าหลัก</a>';
                }
                $conn->close();
                ?>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        function setDateRange(range) {
            document.getElementById('filter').value = range;
            document.querySelector("form").submit();
        }
    </script>
</body>
</html>
