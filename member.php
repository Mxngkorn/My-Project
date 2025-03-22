
<?php
include 'db_connection.php'; // นำไฟล์ db_connect.php เข้ามาใช้
session_start();

if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];

    // ดึงข้อมูลผู้ใช้ตาม UserId
    $stmt = $conn->prepare("SELECT User_Name, Lastname, Email, Mobile, Department, ImageUrl , RoleUser FROM users WHERE UserId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit;
    }
} else {
    // หากไม่มีข้อมูล session ให้แสดงข้อความหรือทำการเปลี่ยนเส้นทาง
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> กาแฟสด"เด็กคอม" </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- nice select  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/css/nice-select.min.css" integrity="sha512-CruCP+TD3yXzlvvijET8wV5WxxEh5H8P4cmz0RFbKK6FlZ2sYl3AEsKlLPHbniXKSrDdFewhbmBK5skbdsASbQ==" crossorigin="anonymous" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    /* style.css */
body {
  font-family: 'Kanit', sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
}
h1, h2, h3, .menu ,li , p , h5, .cart-btn , .price ,.order_online, label{
    font-family: 'Kanit', sans-serif;
}

.menu-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
}

.menu-item {
    width: 300px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    text-align: center;
    background: #ffffff;
    transition: transform 0.3s ease;
}

.menu-item:hover {
    transform: scale(1.05);
}

.menu-item img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 10px;
}

.menu-item h5 {
    font-size: 20px;
    margin: 10px 0;
    color: #333;
}

.menu-item p {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}

.menu-item .price {
    font-size: 18px;
    color: #000;
    margin: 10px 0;
}

.menu-item .cart-btn {
    display: inline-block;
    padding: 10px 15px;
    background: #ff6b6b;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.menu-item .cart-btn:hover {
    background: #ff4949;
}
.filters_menu {
    display: flex;
    justify-content: center;
    list-style-type: none;
    padding: 0;
    margin: 20px 0;
}

.filters_menu li {
    margin: 0 15px;
    padding: 10px 20px;
    cursor: pointer;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.filters_menu li.active {
    background-color: #ff6b6b;
    color: #fff;
}

.filters_menu li:hover {
    background-color: #ff8c8c;
    color: #fff;
}
.cart-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #ff6b6b;
    border-radius: 50%;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    z-index: 9999;
}

.cart-icon img {
    width: 30px;
    height: 30px;
}

#cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #fff;
    color: #ff6b6b;
    border-radius: 50%;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: bold;
}
  </style>

</head>
<body class="Mali font">
  <div class="hero_area">
    <div class="bg-box">
      <img src="images/ปก1.jpg" alt="">
    </div>

    <!-- header section starts -->
    <header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <h3 style="color: Azure;">กาแฟสด<span style="color: orange;">"เด็กคอม"</span></h3>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
              <li class="nav-item active">
                <a class="nav-link" href="member.php">หน้าหลัก <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#food_section">เมนู</a>
              </li>
            </ul>
            <div class="user_option">
              <!-- User Icon -->
              <a href="#" class="user_link" onclick="openUserProfile()">
                <img src="images/user.png" alt="User Image" style="width: 30px; height: 30px; border-radius: 50%;" aria-hidden="true">
              </a>
              <!-- Modal Popup -->
              <div id="userProfileModal" class="modal">
                <div class="modal-content">
                  <span class="close" onclick="closeUserProfile()">&times;</span>
                  <h2>User Profile</h2>
                  <img src="<?php echo htmlspecialchars($user['ImageUrl']); ?>" alt="Profile Image" style="max-width: 200px;">
                  <p>ชื่อ: <?php echo htmlspecialchars($user['User_Name']); ?></p>
                  <p>อีเมล: <?php echo htmlspecialchars($user['Email']); ?></p>
                  <form method="post" action="update_profile.php" enctype="multipart/form-data">
                    <label for="name">ชื่อ:</label>
                    <input type="text" name="name" id="user_name" value="<?php echo htmlspecialchars($user['User_Name']); ?>" required>
                    <br>
                    <label for="email">อีเมล:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                    <br>
                    <label for="profile_picture">รูปภาพ:</label>
                    <input type="file" name="imageurl" id="imageurl">
                    <br>
                    <button type="submit">บันทึก</button>
                  </form>
                </div>
              </div>
              <a href="logout.php" class="order_online" style="text-decoration: none;">ออกจากระบบ</a>
            </div>
          </div>
        </nav>
      </div>
    </header>
    <!--- ไอคอลตะกร้า---->
    <div class="cart-icon">
      <a href="cart.php" class="view-cart">
        <img src="images/cart-icon.png" alt="Cart">
      </a>
    </div>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section">
      <div id="customCarousel1" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="container">
              <div class="row">
                <div class="col-md-5 col-lg-6">
                  <div class="detail-box">
                    <h1>กาแฟเทคนิค<span style="color: orange;">คอม</span></h1>
                    <p>เว็บไซต์โปรเจ็คนี้เป็นส่วนหนึ่งในการทำโปรเจ็ควิชา การออกแบบและพัฒนาเว็บไซต์ ของนักศึกษาวิทยาลัยเทคนิคพระนครศรีอยุธยา</p>
                    <div class="btn-box"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- end slider section -->
  </div>

  <br>
  <br>

  <!-- food section -->
  <?php
  include 'db_connection.php'; // เชื่อมต่อฐานข้อมูล

  // ดึงหมวดหมู่จากฐานข้อมูล
  $categories = [];
  $categoryQuery = "SELECT CategoryId, Name FROM categories";
  $categoryResult = $conn->query($categoryQuery);
  while ($row = $categoryResult->fetch_assoc()) {
    $categories[$row['CategoryId']] = $row['Name'];
  }
  ?>

  <section id="food_section" class="food_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>เมนู</h2>
      </div>
      <!-- ตัวกรองเมนู -->
      <ul class="filters_menu">
        <li class="active" data-filter="all">ทั้งหมด</li>
        <?php
        foreach ($categories as $id => $name) {
          echo '<li data-filter="' . $id . '">' . htmlspecialchars($name) . '</li>';
        }
        ?>
      </ul>
      <div id="menu-container" class="menu-container">
        <?php
        // Query เพื่อดึงสินค้าที่ IsActive = 1
        $sql = "SELECT * FROM products WHERE IsActive = 1";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $categoryName = isset($categories[$row['CategoryId']]) ? $categories[$row['CategoryId']] : 'ไม่ระบุ';
            echo '
            <div class="menu-item" data-category="' . $row['CategoryId'] . '">
              <img src="' . htmlspecialchars($row['ImageUrl']) . '" alt="' . htmlspecialchars($row['Name']) . '" style="width: 260px; height: 260px;">
              <h5>' . htmlspecialchars($row['Name']) . '</h5>
              <p>ประเภท: ' . htmlspecialchars($categoryName) . '</p>
              <div class="price">฿' . htmlspecialchars($row['Price']) . '</div>
              <a href="javascript:void(0);" class="cart-btn" onclick="openModal(' . $row['ProductId'] . ', \'' . htmlspecialchars($row['Name']) . '\', \'' . htmlspecialchars($row['ImageUrl']) . '\')">เพิ่มลงตะกร้า</a>
            </div>';
          }
        } else {
          echo '<p>No items found</p>';
        }
        $conn->close();
        ?>
      </div>
    </div>
  </section>

  <!-- Modal สำหรับเลือกจำนวนและระดับความหวาน -->
  <div id="cartModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">เลือกจำนวนและระดับความหวาน</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="cartForm" method="POST" action="add_to_cart.php">
            <input type="hidden" name="productId" id="modalProductId">
            <p id="modalProductName"></p>
            <img id="modalProductImage" src="" alt="Product Image" style="width: 100%; height: auto; margin-bottom: 15px;">
            <label>จำนวน:</label>
            <input type="number" name="quantity" id="modalQuantity" value="1" min="1" class="form-control">
            <label>ระดับความหวาน:</label>
            <select name="sweetness" id="modalSweetness" class="form-control">
              <option value="ไม่หวาน">ไม่หวาน</option>
              <option value="หวานน้อย">หวานน้อย</option>
              <option value="หวานปกติ">หวานปกติ</option>
              <option value="หวานมาก">หวานมาก</option>
            </select>
            <button type="submit" class="btn btn-success mt-3">เพิ่มลงตะกร้า</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
  function openModal(productId, productName, productImage) {
    document.getElementById('modalProductId').value = productId;
    document.getElementById('modalProductName').innerText = productName;
    document.getElementById('modalProductImage').src = productImage;
    document.getElementById('modalQuantity').value = 1; // ค่าเริ่มต้น
    document.getElementById('modalSweetness').value = "หวานปกติ"; // ค่าเริ่มต้น
    new bootstrap.Modal(document.getElementById('cartModal')).show();
  }
  </script>

  <!-- JavaScript สำหรับตัวกรองเมนู -->
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const filterButtons = document.querySelectorAll(".filters_menu li");
    const menuItems = document.querySelectorAll(".menu-item");
    filterButtons.forEach(button => {
      button.addEventListener("click", function () {
        filterButtons.forEach(btn => btn.classList.remove("active"));
        this.classList.add("active");
        const filter = this.getAttribute("data-filter");
        menuItems.forEach(item => {
          if (filter === "all" || item.getAttribute("data-category") === filter) {
            item.style.display = "block";
          } else {
            item.style.display = "none";
          }
        });
      });
    });
  });
  </script>
  <!-- end food section -->
  <!-- footer section -->
  <footer class="footer_section">
    <div class="container">
      <div class="row">
        <div class="col-md-4 footer-col">
          <div class="footer_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="member.php">
                <span>
                  หน้าหลัก
                </span>
              </a>
                
                <span>
                  Call +01 1234567890
                </span>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  demo@gmail.com
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <div class="footer_detail">
            <a href="" class="footer-logo" style="text-decoration: none;">
              Topic
            </a>
            <p>
              เว็บนี้ถูกสร้างขึนเพื่อการศึกษาและทำโปรเจ็คจบของวิทยาลัยเทคนิคพระนครศรีอยุธยาชั้น ปวส.2 สาขาวิชาเทคโนโลยีคอมพิวเตอร์ แผนกเทคนิคคอมพิวเตอร์
            </p>
          </div>
        </div>
        <div class="col-md-4 footer-col">
          <h4>
            Opening Hours
          </h4>
          <p>
            จันทร์-ศุกร์
          </p>
          <p>
            10.00 Am -10.00 Pm
          </p>
        </div>
      </div>
      <div class="footer-info">
        <p>
          &copy; <span id="displayYear"></span> All Rights Reserved By
          <a href="https://html.design/">Free Html Templates</a><br><br>
          &copy; <span id="displayYear"></span> Distributed By
          <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>
        </p>
      </div>
    </div>
  </footer>
  <!-- footer section -->



  <script>
// เปิด Modal
function openUserProfile() {
    document.getElementById("userProfileModal").style.display = "block";
}

// ปิด Modal
function closeUserProfile() {
    document.getElementById("userProfileModal").style.display = "none";
}

// ปิด Modal เมื่อคลิกที่นอก Modal
window.onclick = function(event) {
    if (event.target == document.getElementById("userProfileModal")) {
        closeUserProfile();
    }
}
// เลือกหมวดหมู่
    document.querySelectorAll('.filters_menu li').forEach(item => {
        item.addEventListener('click', function() {
            // เปลี่ยนคลาสของปุ่มกรองที่ active
            document.querySelector('.filters_menu .active').classList.remove('active');
            this.classList.add('active');

            // รับหมวดหมู่จาก data-filter
            const category = this.getAttribute('data-filter');

            // ส่งคำขอ AJAX เพื่อโหลดข้อมูล
            fetch(`fetch_products.php?category=${category}`)
                .then(response => response.text())
                .then(data => {
                    // อัปเดตผลลัพธ์สินค้าใน #menu-container
                    document.getElementById('menu-container').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });

    function addToCart(productId, sweetness) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `productId=${productId}&sweetness=${encodeURIComponent(sweetness)}`
    })
    .then(response => response.json()) // รับผลลัพธ์เป็น JSON
    .then(data => {
        if (data.success) {
            alert(data.message); // ✅ แสดง alert เมื่อเพิ่มสินค้าสำเร็จ
            updateCartCount(); // ✅ อัปเดตจำนวนสินค้าในตะกร้า
        } else {
            alert(data.message); // แสดงข้อผิดพลาด
        }
    })
    .catch(error => console.error('Error:', error));
}

// ✅ ฟังก์ชันอัปเดตจำนวนสินค้าในไอคอนตะกร้า
function updateCartCount() {
    fetch('update_cart_count.php')
        .then(response => response.text())
        .then(count => {
            document.getElementById('cart-count').textContent = count;
        })
        .catch(error => console.error('Error:', error));
}


    

</script>






<!-- CSS สำหรับ Modal -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
<!-- CSS Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- JS Bootstrap (ต้องอยู่ก่อนปิด </body>) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>