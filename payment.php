<?php
require 'db_connection.php';
if (!isset($_GET['amount']) || !isset($_GET['orderNo']) || !isset($_GET['paymentId'])) {
    die("Invalid request.");
}

$amount = number_format($_GET['amount'], 2, '.', '');
$orderNo = $_GET['orderNo'];
$paymentId = $_GET['paymentId'];

$promptPayID = 'YOUR_PROMPTPAY_ID'; // 🔹 ใส่ PromptPay ของร้านค้า
$qrData = "https://promptpay.io/$promptPayID/$amount";

$qrFile = "qrcode_$orderNo.png";
QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 10);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
</head>
<body>

<h1>Payment for Order: <?= htmlspecialchars($orderNo) ?></h1>
<p>Amount to Pay: $<?= number_format($amount, 2) ?></p>

<img src="<?= $qrFile ?>" alt="QR Code Payment" width="300">

<!-- อัปโหลดหลักฐานการชำระเงิน -->
<form method="POST" action="upload_payment.php" enctype="multipart/form-data">
    <input type="hidden" name="paymentId" value="<?= htmlspecialchars($paymentId) ?>">
    <input type="file" name="paymentProof" required>
    <button type="submit">Upload Proof of Payment</button>
    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=https://devbanban.com/&choe=UTF-8" title="Link to my Website" />
</form>

</body>
</html>
