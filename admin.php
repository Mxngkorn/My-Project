<?php
session_start();
include 'db_connection.php';
// ตรวจสอบว่าเข้าสู่ระบบแล้วหรือยัง
if (!isset($_SESSION['userid'])) {
   // ดึงข้อมูลผู้ใช้ตาม UserId
   $stmt = $conn->prepare("SELECT User_Name, Lastname, Email, Mobile, Department, ImageUrl , RoleUser FROM users WHERE UserId = ?");
   $stmt->bind_param("i", $userId);
   $stmt->execute();
   $result = $stmt->get_result();
    header("Location: login.php"); // หากยังไม่ได้เข้าสู่ระบบ ให้กลับไปที่หน้า login
    exit();
}

// ตรวจสอบสิทธิ์ (Row)
if ($_SESSION['roleuser'] !== 'ผู้ดูแล') {
    echo "You do not have permission to access this page.";
    exit();
}

$query = "SELECT COUNT(*) AS user_count FROM users";
$result = $conn->query($query);
$userCount = 0;

if ($result && $row = $result->fetch_assoc()) {
    $userCount = $row['user_count']; // จำนวนผู้ใช้งาน
}
// นับจำนวนคำสั่งซื้อที่ยังไม่เสร็จสิ้น
$sql = "SELECT COUNT(*) AS total_orders FROM isorder WHERE Statuss != 'เสร็จสิ้น'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalOrders = $row['total_orders']; // ดึงค่าจำนวนออเดอร์

// ดึงจำนวนคำสั่งซื้อที่มีสถานะ "เสร็จสิ้น"
$sql = "SELECT COUNT(*) AS totalSales FROM isorder WHERE Statuss = 'เสร็จสิ้น'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$totalSales = $row['totalSales'] ? $row['totalSales'] : '0'; // ถ้าไม่มีข้อมูล ให้แสดง 0
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>แดชบอร์ด | หน้าหลัก</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
  body {
    font-family: 'Kanit', sans-serif;
  }
</style>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">



  <!-- Navbar -->
  <nav class="navbar navbar-expand navbar-dark bg-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">ผู้ดูแล</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <?php
      // ดึงข้อมูลผู้ใช้ตาม UserId
      $stmt = $conn->prepare("SELECT User_Name, Lastname, ImageUrl FROM users WHERE UserId = ?");
      $stmt->bind_param("i", $_SESSION['userid']);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result && $row = $result->fetch_assoc()) {
          $name = $row['User_Name'];
          $lastname = $row['Lastname'];
          $imageUrl = $row['ImageUrl'];
      }
      ?>
      <li class="nav-item">
        <a class="nav-link" href="#">
            <img src="<?php echo $imageUrl; ?>" class="img-circle" alt="User Image" width="30" height="30">
          <?php echo $name . ' ' . $lastname; ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">ออกจากระบบ</a>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  

  <!-- Content Wrapper. Contains page content -->
  
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">แดชบอร์ด</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="">หน้าหลัก</a></li>
              <li class="breadcrumb-item active">แดชบอร์ด</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <!-- Small box 1 -->
      <div class="col-md-4 col-sm-6 col-12">
        <div class="small-box bg-primary">
          <div class="inner">
        <?php
        // ดึงจำนวน stock จากตาราง stock
        $sql = "SELECT COUNT(*) AS totalStock FROM stock";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $totalStock = $row['totalStock'] ? $row['totalStock'] : '0'; // ถ้าไม่มีข้อมูล ให้แสดง 0
        ?>
        <h3><?php echo $totalStock; ?></h3>
        <p>วัตถุดิบ</p>
          </div>
          <div class="icon">
        <i class="ion ion-cube"></i>
          </div>
          <a href="add_stock.php" class="small-box-footer">เพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Small box 2 -->
      <div class="col-md-4 col-sm-6 col-12">
        <div class="small-box bg-secondary">
          <div class="inner">
        <?php
        // ดึงจำนวน products จากตาราง products
        $sql = "SELECT COUNT(*) AS totalProducts FROM products";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $totalProducts = $row['totalProducts'] ? $row['totalProducts'] : '0'; // ถ้าไม่มีข้อมูล ให้แสดง 0
        ?>
        <h3><?php echo $totalProducts; ?></h3>
        <p>สินค้า</p>
          </div>
          <div class="icon">
        <i class="fas fa-coffee"></i>
          </div>
          <!-- ลิงก์ไปยัง add_product.php -->
          <a href="add_product.php" class="small-box-footer">เพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>


      <!-- Small box 3 -->
      <div class="col-md-4 col-sm-6 col-12">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?php echo $userCount; ?></h3> <!-- แสดงจำนวนผู้ใช้งานจริง -->
            <p>ผู้ใช้งาน</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="edit_users.php" class="small-box-footer">เพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
              <h3><?php echo $totalOrders; ?></h3>

              <p>จัดการคำสั่งซื้อ</p>
              </div>
              <div class="icon">
              <i class="fas fa-shopping-cart"></i>
              </div>
              <a href="order_management.php" class="small-box-footer">เพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
            </div>
            </div>
          <!-- ./col -->
          <div class="col-md-4 col-sm-6 col-12">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
              <h3><?php echo $totalSales; ?></h3> <!-- แสดงจำนวนคำสั่งซื้อที่ "เสร็จสิ้น" -->

                <p>รายการขาย</p>
              </div>
              <div class="icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <a href="sales_report.php" class="small-box-footer">เพิ่มเติม <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
           <?php include 'totalcash.php';?>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- Control Sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

</body>
</html>
