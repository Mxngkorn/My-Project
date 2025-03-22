<?php
include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลผู้ใช้ตาม ID
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM users WHERE UserId = " . $userId;
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    echo "User not found.";
    exit();
}

$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = $_POST['user_name'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['roleuser'];

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพหรือไม่
    if (!empty($_FILES['ImageUrl']['name'])) {
        $targetDir = "uploads/";  // ตั้งค่าตำแหน่งเซฟไฟล์
        $fileName = basename($_FILES['ImageUrl']['name']);
        $targetFilePath = $targetDir . time() . "_" . $fileName; // ป้องกันชื่อซ้ำ
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // ตรวจสอบประเภทไฟล์ (เฉพาะ JPG, JPEG, PNG เท่านั้น)
        $allowedTypes = ['jpg', 'jpeg', 'png'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES["ImageUrl"]["tmp_name"], $targetFilePath)) {
                // บันทึก URL ของรูปภาพลงฐานข้อมูล
                $updateQuery = "UPDATE users SET User_Name = '$name', Lastname = '$lastname', Email = '$email', RoleUser = '$role', ImageUrl = '$targetFilePath' WHERE UserId = $userId";
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์ ❌";
                exit();
            }
        } else {
            echo "ไฟล์รูปต้องเป็น JPG, JPEG หรือ PNG เท่านั้น ❌";
            exit();
        }
    } else {
        // ถ้าไม่มีการอัปโหลดรูป ใช้ค่ารูปเดิม
        $updateQuery = "UPDATE users SET User_Name = '$name', Lastname = '$lastname', Email = '$email', RoleUser = '$role' WHERE UserId = $userId";
    }

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: edit_users.php"); // กลับไปหน้ารายชื่อผู้ใช้
        exit();
    } else {
        echo "Failed to update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขผู้ใช้</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">แก้ไขข้อมูล : <?php echo htmlspecialchars($user['Name']); ?></h1>
        <form method="POST" class="card p-4 shadow-sm" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อ:</label>
                <input type="text" class="form-control" id="name" name="user_name" value="<?php echo htmlspecialchars($user['User_Name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">นามสกุล:</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['Lastname']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="roleuser" class="form-label">หน้าที่:</label>
                <select class="form-select" id="roleuser" name="roleuser" required>
                    <option value="ผู้ดูแล" <?php echo $user['RoleUser'] === 'ผู้ดูแล' ? 'selected' : ''; ?>>ผู้ดูแล</option>
                    <option value="สมาชิก" <?php echo $user['RoleUser'] === 'สมาชิก' ? 'selected' : ''; ?>>สมาชิก</option>
                    <option value="พนักงาน" <?php echo $user['RoleUser'] === 'พนักงาน' ? 'selected' : ''; ?>>พนักงาน</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="profile_picture" class="form-label">รูปโปรไฟล์</label>
                <input type="file" class="form-control" id="ImageUrl" name="ImageUrl">
                <?php if (!empty($user['ImageUrl'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo htmlspecialchars($user['ImageUrl']); ?>" alt="Profile Picture" width="100">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="edit_users.php" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
