<?php
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล
session_start(); // เริ่ม session ใหม่
if (!isset($_SESSION['roleuser']) || ($_SESSION['roleuser'] != 'ผู้ดูแล' && $_SESSION['roleuser'] != 'พนักงาน')) {
    header("Location: login.php");
    exit();
}
// ลบ Stock 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_stock'])) {
    $stock_id = $_POST['stock_id'];
    $sql = "DELETE FROM stock WHERE StockId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stock_id);

    if ($stmt->execute()) {
        header("Location: add_stock.php"); // Reload หน้าเพื่ออัปเดตข้อมูล
        exit();
    } else {
        $error_message = "Error deleting stock: " . $stmt->error;
    }
}

// ตรวจสอบเมื่อเพิ่ม Stock
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $error_message = '';
    $success_message = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads_stock/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                $sql = "INSERT INTO stock (Name, Quantity, ImageUrl, CreateDate) VALUES (?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sis", $name, $quantity, $filePath);
                
                if ($stmt->execute()) {
                    header("Location: add_stock.php"); // Reload หน้าเพื่ออัปเดตข้อมูล
                    exit();
                } else {
                    $error_message = "Error: " . $stmt->error;
                }
            } else {
                $error_message = "Failed to upload the image.";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $error_message = "Please upload an image.";
    }
}

// ดึงข้อมูล stock ทั้งหมด
$stock_result = $conn->query("SELECT * FROM stock ORDER BY CreateDate DESC");

// ดึง stock ที่มีจำนวนน้อยกว่า 3
$low_stock_result = $conn->query("SELECT * FROM stock WHERE Quantity < 5 ORDER BY Quantity ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
    <title>จัดการวัตถุดิบ</title>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
        .table img {
            border-radius: 5px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <h4>เพิ่มวัตถุดิบ</h4>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"> <?php echo $error_message; ?> </div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"> <?php echo $success_message; ?> </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-2">
                    <label class="form-label">ชื่อวัตถุดิบ</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">จำนวน</label>
                    <input type="number" class="form-control" name="quantity" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">อัปโหลดรูป</label>
                    <input type="file" class="form-control" name="image" accept="image/*" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">เพิ่มวัตถุดิบ</button>
                <?php
           
                if ($_SESSION['roleuser'] == 'ผู้ดูแล') {
                    echo '<a href="admin.php" class="btn btn-secondary">กลับไปหน้าผู้ดูแล</a>';
                } elseif ($_SESSION['roleuser'] == 'พนักงาน') {
                    echo '<a href="employee.php" class="btn btn-secondary">กลับไปหน้าหลัก</a>';
                }
                $conn->close();
                ?>
            </form>
        </div>
        <div class="col-md-8">
            <h4>รายการวัตถุดิบ</h4>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ชื่อ</th>
                        <th>จำนวน</th>
                        <th>รูป</th>
                        <th>วัน-เวลาที่เพิ่มวัตถุดิบ</th>
                        <th>ปุ่ม</th> <!-- ปุ่มลบ -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stock_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['Name']; ?></td>
                            <td>
                                <form method="POST" action="update_quantity.php" class="d-inline">
                                    <input type="hidden" name="stock_id" value="<?php echo $row['StockId']; ?>">
                                    <input type="hidden" name="action" value="decrease">
                                    <button class="btn btn-warning btn-sm update-stock" data-id="<?php echo $row['StockId']; ?>" data-action="decrease">-</button>
                                </form>
                                <span id="qty-<?php echo $row['StockId']; ?>"><?php echo $row['Quantity']; ?></span>
                                <form method="POST" action="update_quantity.php" class="d-inline">
                                    <input type="hidden" name="stock_id" value="<?php echo $row['StockId']; ?>">
                                    <input type="hidden" name="action" value="increase">
                                    <button class="btn btn-success btn-sm update-stock" data-id="<?php echo $row['StockId']; ?>" data-action="increase">+</button>
                                </form>
                            </td>
                            <td><img src="<?php echo $row['ImageUrl']; ?>" width="50"></td>
                            <td><?php echo $row['CreateDate']; ?></td>
                            <td>
                                <!-- ปุ่มแก้ไข -->
                                <form method="GET" action="edit_stock.php" class="d-inline">
                                    <input type="hidden" name="stock_id" value="<?php echo $row['StockId']; ?>">
                                    <button type="submit" class="btn btn-info btn-sm">แก้ไข</button>
                                </form>
                                <!-- ปุ่มลบ -->
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this stock?');" class="d-inline">
                                    <input type="hidden" name="stock_id" value="<?php echo $row['StockId']; ?>">
                                    <button type="submit" name="delete_stock" class="btn btn-danger btn-sm">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
    <h4>วัตถุดิบเหลือน้อย ต่ำกว่า 5</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-warning">
            <tr>
                <th>ชื่อ</th>
                <th>จำนวน</th>
                <th>รูปภาพ</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $low_stock_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Name']; ?></td>
                    <td class="text-center"><?php echo $row['Quantity']; ?></td>
                    <td><img src="<?php echo $row['ImageUrl']; ?>" width="50"></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".update-stock").click(function(event) {
            event.preventDefault(); // ป้องกันการรีเฟรชหน้า

            var stockId = $(this).data("id");
            var action = $(this).data("action");

            $.ajax({
                url: "update_quantity.php",
                type: "POST",
                data: { stock_id: stockId, action: action },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $("#qty-" + stockId).text(response.newQuantity);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    alert("เกิดข้อผิดพลาดในการอัปเดตข้อมูล");
                }
            });
        });
    });
</script>



</body>
</html>


