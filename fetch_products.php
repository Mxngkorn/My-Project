<?php
include 'db_connection.php';

// ตั้งค่าหมวดหมู่
$categories = [
    1 => 'ชา',
    2 => 'กาแฟ'
];

// รับค่าหมวดหมู่จาก GET
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// สร้าง SQL Query
if ($category === 'all') {
    $sql = "SELECT * FROM products WHERE IsActive = 1";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT * FROM products WHERE IsActive = 1 AND CategoryId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category);
}

$stmt->execute();
$result = $stmt->get_result();

// สร้าง HTML สำหรับผลลัพธ์
if ($result->num_rows > 0) {
    echo '<div class="menu-container">';
    while ($row = $result->fetch_assoc()) {
        $categoryName = isset($categories[$row['CategoryId']]) ? $categories[$row['CategoryId']] : 'ไม่ระบุ';

        echo '
        <div class="menu-item">
           <img src="' . htmlspecialchars($row['ImageUrl']) . '" alt="' . htmlspecialchars($row['Name']) . '" style="width: 260px; height: 260px;">
            <h5>' . htmlspecialchars($row['Name']) . '</h5>
            <p>' . htmlspecialchars($row['Description']) . '</p>
            <p>ประเภท: ' . htmlspecialchars($categoryName) . '</p>
            <div class="price">฿' . htmlspecialchars($row['Price']) . '</div>
            <a href="javascript:void(0);" class="cart-btn" onclick="openModal(' . $row['ProductId'] . ', \'' . htmlspecialchars($row['Name']) . '\', \'' . htmlspecialchars($row['ImageUrl']) . '\')">เพิ่มลงตะกร้า</a>     
        </div>';
        
    
    }
} else {
    echo '<p>ไม่มีสินค้าในหมวดหมู่ที่เลือก</p>';
}

$conn->close();
?>

