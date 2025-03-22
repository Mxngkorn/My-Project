<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // แสดงค่าที่ส่งมาจากฟอร์ม (สำหรับ Debugging)


    // ตรวจสอบค่าที่ได้รับว่ามีการตั้งค่าหรือไม่
    $user_name  = isset($_POST['user_name']) ? trim($_POST['user_name']) : '';
    $lastname   = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $mobile     = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
    $email      = isset($_POST['email']) ? trim($_POST['email']) : '';
    $department = isset($_POST['department']) ? trim($_POST['department']) : '';
    $password   = isset($_POST['password_h']) ? $_POST['password_h'] : '';

    // ตรวจสอบค่าว่าง
    if (empty($user_name) || empty($lastname) || empty($mobile) || empty($email) || empty($department) || empty($password)) {
        die("Error: ข้อมูลไม่ครบ กรุณากรอกทุกช่อง");
    }

    // เข้ารหัสรหัสผ่าน
    $password_h = md5($password);
    $roleuser = "สมาชิก"; 
    $imageUrl = ''; // ตั้งค่าเริ่มต้นให้ ImageUrl เป็นค่าว่าง

   

    // SQL Insert
    $sql = "INSERT INTO users (User_Name, Lastname, Mobile, Email, Department, Password_h, ImageUrl, CreateDate, RoleUser) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";

    // เตรียม statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssss", $user_name, $lastname, $mobile, $email, $department, $password_h, $imageUrl, $roleuser);

      

        // Execute คำสั่ง SQL
        if ($stmt->execute()) {
            echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='login.php';</script>";
        } else {
            echo "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . $stmt->error;
        }

        $stmt->close(); // ปิด statement
    } else {
        echo "เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
    }

    $conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: url('images/ปก1.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            width: 500px;
            border-radius: 10px;
        }
        .btn-primary {
            background: linear-gradient(to right,rgb(255, 123, 0),rgb(239, 154, 56));
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background: linear-gradient(to right,rgb(239, 174, 83),rgb(245, 163, 0));
        }
    </style>
</head>
<body>
    <div class="card shadow-lg p-4">
        <h3 class="text-center mb-4">สมัครสมาชิก</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">ชื่อ</label>
                    <input type="text" name="user_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">นามสกุล</label>
                    <input type="text" name="lastname" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">แผนก</label>
                    <input type="text" name="department" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">เบอร์โทร</label>
                    <input type="text" name="mobile" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">อีเมล</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">รหัสผ่าน</label>
                    <input type="password" name="password_h" class="form-control" required>
                </div>
            </div>
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">สมัครสมาชิก</button>
            <a href="login.php" class="btn btn-secondary mt-2">กลับไปหน้าเข้าสู่ระบบ</a>
        </div>
        </form>
    </div>
</body>
</html>