<?php
require_once 'db.php';

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Insert data into MySQL database
$sql = "INSERT INTO contact_us (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
 echo '<script>alert("Form submitted successfully"); window.location.href = "home.php";</script>';
   
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
