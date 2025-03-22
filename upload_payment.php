upload_payment 
<?php
session_start();
require 'db_connection.php'; // Connect to the database

// Ensure the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['uploadSlip'])) {
    if (!isset($_SESSION['userid'])) {
        echo "<script>alert('Please login to upload payment slip.'); window.location.href='member.php';</script>";
        exit();
    }

    // Get total price and user ID
    $totalPrice = $_SESSION['totalPrice'];
    $userId = $_SESSION['userid'];
    $cart = $_SESSION['cart']; // Cart data

    // Upload payment slip
    $targetDir = "uploads_slip/"; // Directory to upload the file
    $fileName = basename($_FILES["paymentSlip"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow only specific file types
    $allowTypes = array('jpg', 'png', 'jpeg', 'pdf');
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES["paymentSlip"]["tmp_name"], $targetFilePath)) {
            // Insert payment details into the 'payments' table
            $paymentMethod = "เงินโอน"; // Set PaymentMode to "Transfer"
            $paymentStatus = "ชำระเรียบร้อย"; // Set PaymentStatus to "Paid"
            $orderNo = "ORD" . time(); // Generate Order No
            $status = "กำลังทำ"; // Order status

            // Escape values before inserting to prevent SQL injection
            $paymentMethod = mysqli_real_escape_string($conn, $paymentMethod);
            $paymentStatus = mysqli_real_escape_string($conn, $paymentStatus);
            $targetFilePath = mysqli_real_escape_string($conn, $targetFilePath);

            // Insert data into the 'payments' table
            $query = "INSERT INTO payments (UserId, PaymentMode, Amount, PaymentStatus, SlipImageUrl) 
                      VALUES ('" . mysqli_real_escape_string($conn, $userId) . "', 
                              '$paymentMethod', 
                              '$totalPrice', 
                              '$paymentStatus', 
                              '$targetFilePath')";
            if (mysqli_query($conn, $query)) {
                $paymentId = mysqli_insert_id($conn); // Get the inserted payment ID

                // Insert data into the 'isorder' table for each product in the cart
                foreach ($cart as $productId => $item) {
                    $quantity = $item['quantity'];
                    $description = $item['description']; // Sweetness level from the session

                    // Escape values before inserting to prevent SQL injection
                    $productId = mysqli_real_escape_string($conn, $productId);
                    $quantity = mysqli_real_escape_string($conn, $quantity);
                    $description = mysqli_real_escape_string($conn, $description);

                    // Insert order data
                    $query = "INSERT INTO isorder (OrderNo, ProductId, Quantity, UserId, Statuss, PaymentId, Descriptions) 
                              VALUES ('$orderNo', 
                                      '$productId', 
                                      '$quantity', 
                                      '" . mysqli_real_escape_string($conn, $userId) . "', 
                                      '$status', 
                                      '$paymentId', 
                                      '$description')";
                    if (!mysqli_query($conn, $query)) {
                        echo "<script>alert('Error inserting order for Product ID " . $productId . ".'); window.history.back();</script>";
                        exit();
                    }
                }

                // Clear the cart and redirect
                unset($_SESSION['cart']);
                echo "<script>alert('อัปโหลดสลิปสำเร็จ'); window.location.href='member.php';</script>";
                exit();

            } else {
                echo "<script>alert('Error inserting payment details.'); window.history.back();</script>";
                exit();
            }

        } else {
            echo "<script>alert('Error uploading payment slip.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid file format. Please upload JPG, PNG, JPEG, or PDF.'); window.history.back();</script>";
        exit();
    }
}
?>
