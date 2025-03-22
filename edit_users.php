<?php
session_start();
include 'db_connection.php';

// ตรวจสอบสิทธิ์ (อนุญาตเฉพาะ admin)
if (!isset($_SESSION['userid']) || $_SESSION['roleuser'] !== 'ผู้ดูแล') {
    echo "You do not have permission to access this page.";
    exit();
}

// Query ดึงข้อมูลผู้ใช้ทั้งหมด
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
    <!-- Bootstrap CSS -->
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
        <h1 class="mb-4">การจัดการผู้ใช้</h1>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ไอดี</th>
                    <th>ชื่อ</th>
                    <th>นามสกุล</th>
                    <th>อีเมล</th>
                    <th>บทบาท</th>
                    <th>ปุ่ม</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['UserId']); ?></td>
                        <td><?php echo htmlspecialchars($row['User_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Lastname']); ?></td>
                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                        <td><?php echo htmlspecialchars($row['RoleUser']); ?></td>
                        <td>
                            <a href="edit_user_form.php?id=<?php echo $row['UserId']; ?>" class="btn btn-warning btn-sm">แก้ไข</a>
                            <a href="delete_user.php?id=<?php echo $row['UserId']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="text-center mt-4">
                    <a href="admin.php" class="btn btn-primary">🔙 กลับไปหน้า Admin</a>
                </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
