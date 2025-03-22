<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = "localhost";       
$username = "roof";        
$password = "";            
$dbname = "coffeestore";    


$conn = new mysqli($host, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
