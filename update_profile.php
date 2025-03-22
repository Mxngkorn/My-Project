<?php
include 'db_connection.php'; // Connect to the database
session_start();

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userid'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted name and email
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $profilePicture = null; // This will store the path of the uploaded profile picture

    // Check if the user uploaded a new profile picture
    if (isset($_FILES['imageurl']) && $_FILES['imageurl']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Directory to store uploaded files
        $fileName = basename($_FILES['imageurl']['name']); // Get the file name
        $fileTmpPath = $_FILES['imageurl']['tmp_name']; // Temporary file path
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // Get file extension

        // Allowed file types
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (in_array($fileType, $allowedTypes)) {
            // Create a new unique file name
            $newFileName = uniqid() . '.' . $fileType;
            $uploadPath = $uploadDir . $newFileName;

            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $profilePicture = $uploadPath; // Store the file path to be updated in the database
            } else {
                echo "Failed to upload the image.";
                exit;
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            exit;
        }
    }

    // Update user data in the database
    if ($profilePicture) {
        // If a new profile picture is uploaded
        $sql = "UPDATE users SET User_Name = ?, Email = ?, ImageUrl = ? WHERE UserId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $profilePicture, $userId);
    } else {
        // If no new profile picture is uploaded, update without changing the ImageUrl
        $sql = "UPDATE users SET User_Name = ?, Email = ? WHERE UserId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $userId);
    }

    // Execute the query and handle success/failure
    if ($stmt->execute()) {
        // Redirect to the profile page after a successful update
        header("Location: member.php");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
