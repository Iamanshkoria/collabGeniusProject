<?php
session_start();
require_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
  
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($username === 'Ansh Koriya' && $password === '1') {
           
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['profile_created'] = true; 
            $_SESSION['is_admin'] = true;

            header("Location: header.php?admin_login=success");
            exit();
        } elseif (password_verify($password, $row['password'])) {
           
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['profile_created'] = true; 
            $_SESSION['is_admin'] = false;

           
            header("Location: home.php");
            exit();
        } else {
    
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    } else {
       
        header("Location: login.php?error=invalid_credentials");
        exit();
    }

   
    $stmt->close();
    $conn->close();
} else {
  
    header("Location: login.php");
    exit();
}
?>
