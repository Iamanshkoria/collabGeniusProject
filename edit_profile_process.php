<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "CollabGenius";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process the profile update form
    $user_id = $_SESSION['user_id'];
    $bio = $_POST['bio'];
    $location = $_POST['location'];
    $interests = $_POST['interests'];

    // Prepare SQL query to update profile data
    $sql = "UPDATE profiles SET bio=?, location=?, interests=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $bio, $location, $interests, $user_id);

    if ($stmt->execute()) {
        // Redirect to profile page after successful update
        header("Location: create_profile.php");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
