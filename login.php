<?php
session_start();
include 'db_connection.php'; // นำไฟล์เชื่อมฐานข้อมูลเข้ามาใช้

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ดึงข้อมูลผู้ใช้ตาม Email
    $stmt = $conn->prepare("SELECT UserId, User_Name, Lastname, Password_h, RoleUser FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // ตรวจสอบผลลัพธ์
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ตรวจสอบรหัสผ่าน (เปรียบเทียบแบบตรง ๆ)
        if (md5($password) === $user['Password_h']) { // เปรียบเทียบรหัสผ่านด้วย md5.
            // ตั้งค่า Session
            $_SESSION['userid'] = $user['UserId'];
            $_SESSION['user_name'] = $user['User_Name'];
            $_SESSION['lastname'] = $user['Lastname'];
            $_SESSION['email'] = $email;
            $_SESSION['roleuser'] = $user['RoleUser']; // กำหนด session ตาม Role

            // ตรวจสอบสิทธิ์ผู้ใช้ (RoleUser)
            switch ($user['RoleUser']) {
                case 'ผู้ดูแล': // Admin
                    header("Location: admin.php");
                    break;
                case 'พนักงาน': // Employee
                    header("Location: employee.php");
                    break;
                case 'สมาชิก': // Member
                    header("Location: member.php");
                    break;
                default:
                    echo "สิทธิ์การเข้าถึงไม่ถูกต้อง!";
                    exit();
            }
            exit();
        } else {
            $error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        $error = "ไม่พบข้อมูลผู้ใช้ในฐานข้อมูล!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="css/stylelog.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
        .content, p, .field, .signup, .back-to-index {
            font-family: 'Kanit', sans-serif;
        }
        p {
            font-size: 34px;
            color: white;
        }
        .bg-img {
            background: url('uploads/loginpage.jpg');
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="bg-img">
        <div class="content">
            <p>เข้าสู่ระบบ</p>
            <?php if (isset($error)) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="field">
                    <span class="fa fa-user"></span>
                    <input type="text" name="email" autocomplete="email" required placeholder="Email or Phone">
                </div>
                <div class="field space">
                    <span class="fa fa-lock"></span>
                    <input type="password" name="password" class="pass-key" autocomplete="current-password" required placeholder="Password">
                    <span class="show">SHOW</span>
                </div>
                <div class="field">
                    <input type="submit" value="เข้าสู่ระบบ">
                </div>
            </form>
           
            <div class="signup">ยังไม่ได้สมัครใช่หรือไม่
                <a href="register.php">สมัครสมาชิก</a>
            </div>
            <div class="back-to-index">
                <a href="index.php" style="text-decoration: none; color:black;">ยกเลิก</a>
            </div>
        </div>
    </div>

    <script>
        const pass_field = document.querySelector('.pass-key');
        const showBtn = document.querySelector('.show');
        showBtn.addEventListener('click', function() {
            if (pass_field.type === "password") {
                pass_field.type = "text";
                showBtn.textContent = "HIDE";
                showBtn.style.color = "#3498db";
            } else {
                pass_field.type = "password";
                showBtn.textContent = "SHOW";
                showBtn.style.color = "#222";
            }
        });
    </script>
</body>
</html>