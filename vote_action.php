<?php
session_start();



require_once 'db.php';


$user_id = $_SESSION['user_id'];
$idea_id = intval($_POST['idea_id']);


$sql_check = "SELECT * FROM vote WHERE user_id = $user_id AND idea_id = $idea_id";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    $_SESSION['vote_message'] = "You have already voted for this idea.";
} else {
    $sql_vote = "INSERT INTO vote (user_id, idea_id) VALUES ($user_id, $idea_id)";
    if ($conn->query($sql_vote) === TRUE) {
        $_SESSION['vote_message'] = "Your vote has been recorded.";
    } else {
        $_SESSION['vote_message'] = "Error: " . $conn->error;
    }
}

$conn->close();


header("Location: vote.php");
exit();
?>