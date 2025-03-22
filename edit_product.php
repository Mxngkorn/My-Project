<?php
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล
session_start();

// ตรวจสอบว่าได้รับ product_id หรือไม่
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // ดึงข้อมูลสินค้าตาม ProductId
    $sql = "SELECT * FROM products WHERE ProductId = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "ไม่พบสินค้าที่ต้องการแก้ไข";
        exit();
    }
} else {
    echo "ไม่มี ProductId ที่ส่งมา";
    exit();
}

// อัปเดตข้อมูลสินค้า
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $error_message = '';

    // ตรวจสอบว่าได้รับค่าจากฟอร์มหรือไม่
    if (!empty($name) && !empty($price) && !empty($category_id)) {

        // อัปเดตข้อมูลสินค้าในฐานข้อมูล
        $update_sql = "UPDATE products SET Name = '$name', Descriptions = '$description', Price = $price, CategoryId = $category_id, IsActive = $is_active WHERE ProductId = $product_id";
        
        if (mysqli_query($conn, $update_sql)) {
            echo "<script>alert('อัปเดตสินค้าเรียบร้อย!'); window.location.href='add_product.php';</script>";
        } else {
            echo "เกิดข้อผิดพลาดในการอัปเดตสินค้า: " . mysqli_error($conn);
        }
    } else {
        $error_message = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">แก้ไขสินค้า</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" class="form-edit-product">
            <div class="form-group">
                <label for="name">ชื่อสินค้า:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['Name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="price">ราคา:</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" value="<?php echo $product['Price']; ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id">หมวดหมู่:</label>
                <select id="category_id" name="category_id" class="form-control">
                    <?php
                    // ดึงหมวดหมู่สินค้าทั้งหมด
                    $cat_sql = "SELECT * FROM categories";
                    $cat_result = mysqli_query($conn, $cat_sql);
                    while ($row = mysqli_fetch_assoc($cat_result)) {
                        $selected = ($product['CategoryId'] == $row['CategoryId']) ? "selected" : "";
                        echo "<option value='{$row['CategoryId']}' $selected>{$row['Name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" id="is_active" name="is_active" class="form-check-input" <?php echo ($product['IsActive'] ? "checked" : ""); ?>>
                <label for="is_active" class="form-check-label">สินค้าพร้อมขาย</label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                <a href="add_product.php" class="btn btn-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
