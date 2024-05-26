<?php
session_start();

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

    // Process the sign-up form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age']; // Add age field

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $sql = "INSERT INTO users (username, email, age, password) VALUES ('$username', '$email', '$age', '$hashed_password')"; // Include age in SQL query

    if ($conn->query($sql) === TRUE) {
        // Set user_id in session
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $conn->insert_id; // Get the auto-generated user_id
        $_SESSION['signed_up'] = true; // Set the session variable indicating sign-up
        header("Location: header.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
