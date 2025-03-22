<?php
header('Content-Type: application/json'); 
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stock_id']) && isset($_POST['action'])) {
    $stock_id = intval($_POST['stock_id']);
    $action = $_POST['action'];

    $result = $conn->query("SELECT Quantity FROM stock WHERE StockId = $stock_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $currentQuantity = $row['Quantity'];

        if ($action == "increase") {
            $newQuantity = $currentQuantity + 1;
        } elseif ($action == "decrease" && $currentQuantity > 0) {
            $newQuantity = $currentQuantity - 1;
        } else {
            echo json_encode(["success" => false, "message" => "ไม่สามารถลดจำนวนได้"]);
            exit();
        }

        $conn->query("UPDATE stock SET Quantity = $newQuantity WHERE StockId = $stock_id");

        echo json_encode(["success" => true, "newQuantity" => $newQuantity]);
        exit();
    }
}

echo json_encode(["success" => false, "message" => "ไม่พบสินค้า"]);
?>
