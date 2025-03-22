<?php
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล
session_start();

$error_message = '';
$success_message = '';

// เพิ่มสินค้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description =  null; // รับค่า description หากมี
    $quantity = 0;
    $price = $_POST['price'];
    $categoryId = $_POST['categoryId'];
    $isActive = isset($_POST['isActive']) ? 1 : 0;

    // ตรวจสอบว่าไฟล์ถูกอัปโหลดหรือไม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads_products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . '_' . basename($_FILES['image']['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
            // เพิ่มข้อมูลสินค้า
        $sql = "INSERT INTO products (Name, Descriptions, Price, Quantity, CategoryId, ImageUrl, IsActive, CreateDate) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiiss", $name, $description, $price, $quantity, $categoryId, $filePath, $isActive);

        if ($stmt->execute()) {
            $success_message = "เพิ่มสินค้าสำเร็จ!";
            echo "ข้อมูลที่บันทึกลงฐานข้อมูล:<br>";
            echo "ชื่อสินค้า: $name<br>";
            echo "คำอธิบาย: $description<br>";
            echo "ราคา: $price<br>";
            echo "จำนวน: $quantity<br>";
            echo "หมวดหมู่: $categoryId<br>";
            echo "URL รูปภาพ: $filePath<br>";
            echo "สถานะ: " . ($isActive ? 'พร้อมขาย' : 'ไม่พร้อมขาย') . "<br>";
        } else {
            $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
        }
            } else {
                $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
            }
            } else {
            $error_message = "ไม่สามารถอัปโหลดรูปภาพได้";
            }
        } else {
            $error_message = "ชนิดไฟล์ไม่ถูกต้อง อนุญาตเฉพาะ JPG, JPEG, PNG และ GIF เท่านั้น";
        }
        } else {
        $error_message = "กรุณาอัปโหลดรูปภาพ";
        }


// เปลี่ยนสถานะการใช้งานของสินค้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['toggle_active'])) {
    $productId = $_POST['product_id'];
    $currentStatus = $_POST['current_status'];

    $newStatus = $currentStatus == 1 ? 0 : 1;

    $sql = "UPDATE products SET IsActive = ? WHERE ProductId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $newStatus, $productId);
    if ($stmt->execute()) {
        $success_message = "สถานะสินค้าเปลี่ยนสำเร็จ!";
    } else {
        $error_message = "ไม่สามารถเปลี่ยนสถานะสินค้าได้";
    }
}

// ลบสินค้า
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];

    $sql = "DELETE FROM products WHERE ProductId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    if ($stmt->execute()) {
        $success_message = "ลบสินค้าสำเร็จ!";
    } else {
        $error_message = "ไม่สามารถลบสินค้าได้";
    }
}

// Fetch all products
$product_result = mysqli_query($conn, "SELECT * FROM products ORDER BY CreateDate DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>เพิ่มสินค้า</title>
</head>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <h2>เพิ่มสินค้า</h2>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="form-label">ชื่อสินค้า</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
          
                <div class="mb-4">
                    <label for="price" class="form-label">ราคา</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="mb-4">
                    <label for="categoryId" class="form-label">หมวดหมู่</label>
                    <select class="form-control" id="categoryId" name="categoryId">
                        <option value="1">ชา</option>
                        <option value="2">กาแฟ</option>
                        <option value="3">โซดา</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image" class="form-label">อัปโหลดรูป</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <div class="mb-4">
                    <label for="isActive" class="form-label">สถานะพร้อมใช้งาน</label>
                    <input type="checkbox" id="isActive" name="isActive" value="1">
                </div>
                <button type="submit" name="submit" class="btn btn-primary">เพิ่มสินค้า</button>
            </form>
        </div>

        <div class="col-md-8">
            <h2>รายการสินค้า</h2>
            <?php if ($product_result->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ชื่อ</th>
                            <th>ราคา</th>
                            <th>หมวดหมู่</th>
                            <th>รูปภาพ</th>
                            <th>สถานะพร้อมใช้งาน</th>
                            <th>ปุ่มการเปลี่ยนสถานะ/ปุ่มลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $product_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['Name']; ?></td>
                                <td><?php echo $row['Price']; ?></td>
                                <td><?php echo $row['CategoryId']; ?></td>
                                <td><img src="<?php echo $row['ImageUrl']; ?>" width="50"></td>
                                <td class="text-center"><?php echo ($row['IsActive'] ? 'พร้อมขาย' : 'ไม่พร้อมขาย'); ?></td>
                                <td>
                                    <form method="POST" action="" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $row['ProductId']; ?>">
                                        <input type="hidden" name="current_status" value="<?php echo $row['IsActive']; ?>">
                                        <button type="submit" name="toggle_active" 
                                            class="btn btn-sm <?php echo ($row['IsActive'] ? 'btn-success' : 'btn-danger'); ?>">
                                            <?php echo ($row['IsActive'] ? 'พร้อมขาย' : 'ไม่พร้อมขาย'); ?>
                                        </button>
                                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm">ลบสินค้า</button>
                                    </form>
                                    <!-- ปุ่มแก้ไขสินค้า -->
                                    <a href="edit_product.php?product_id=<?php echo $row['ProductId']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>ไม่พบสินค้า</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
