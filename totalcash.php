<?php
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล

// ดึงยอดเงินรวมของคำสั่งซื้อที่ "ชำระเสร็จสิ้น"
$sql = "SELECT SUM(Amount) AS totalAmount FROM payments WHERE PaymentStatus = 'ชำระเรียบร้อย'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalAmount = $row['totalAmount'] ? number_format($row['totalAmount'], 2) : '0.00'; // ถ้าไม่มีข้อมูล ให้แสดง 0.00
?>

<div class="col-md-4 col-sm-6 col-12">
    <!-- small box -->
    <div class="small-box bg-orange">
        <div class="inner">
            <h3>฿<?php echo $totalAmount; ?></h3> <!-- แสดงยอดเงินรวม -->
            <p>ยอดเงินที่ชำระเสร็จสิ้น</p>
        </div>
        <div class="icon">
            <i class="ion ion-cash"></i> <!-- เปลี่ยนไอคอนให้เหมาะกับยอดเงิน -->
        </div>
    </div>
</div>

<?php $conn->close(); ?>
