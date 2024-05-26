<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to the signup page
    header("Location: register.php");
    exit();
}

// Check if the user has created a profile
if (!isset($_SESSION['profile_created'])) {
    // User has not created a profile, redirect to the profile creation page
    header("Location: create_profile.php");
    exit();
}

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user_id is provided in the form data
    if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
        echo "Error: Missing user_id. Please provide a valid user_id.";
        exit();
    }

    // Retrieve form data
    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $tags = $_POST["tags"];

    // File upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_directory = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION)); // Extract file extension
        $target_file = $target_directory . $user_id . "." . $image_extension; // Append extension to user ID

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "CollabGenius";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL statement to insert idea/product
            $sql = "INSERT INTO ideas (user_id, title, description, category, tags, image_path) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                die("Error: " . $conn->error);
            }

            // Bind parameters and execute SQL statement
            $stmt->bind_param("isssss", $user_id, $title, $description, $category, $tags, $image_path);

            if ($stmt->execute()) {
                echo '<script>alert("Idea/Product submitted successfully!"); window.location.href = "home.php";</script>';
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement and database connection
            $stmt->close();
            $conn->close();
        } else {
            // File upload failed
            echo "Error: There was an error uploading your file.";
            exit();
        }
    } else {
        // No file selected or error during upload
        echo "Error: Please select an image file.";
        exit();
    }
} else {
    // If the form submission method is not POST, redirect to the signup page
    header("Location: register.php");
    exit();
}
?>

