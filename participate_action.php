<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST['competition_id']) && isset($_POST['idea_id'])) {
        $competition_id = $_POST['competition_id'];
        $idea_id = $_POST['idea_id'];

        
        $check_competition = $conn->prepare("SELECT * FROM competitions WHERE competition_id = ?");
        $check_competition->bind_param("i", $competition_id);
        $check_competition->execute();
        $check_competition_result = $check_competition->get_result();

        if ($check_competition_result->num_rows > 0) {
      
            $stmt = $conn->prepare("INSERT INTO competition_participate (user_id, competition_id, idea_id) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $user_id, $competition_id, $idea_id);
            
            if ($stmt->execute()) {
                echo '<script>alert("Your idea has successfully participated in the competition."); window.location.href = "home.php";</script>'; 
            } else {
                echo "Participation Failed: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Invalid competition ID.</div>";
        }

        $check_competition->close();
    } else {
        echo "Invalid request.";
    }
}

$conn->close();
?>