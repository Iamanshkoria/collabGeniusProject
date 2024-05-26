<?php
session_start();
require 'auth.php'; 
require_once 'db.php';// Assuming you have an auth.php to handle authentication

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "You need to be logged in to perform this action."]);
    exit;
}

if (isset($_POST['action']) && isset($_POST['idea_id'])) {
    $action = $_POST['action'];
    $idea_id = intval($_POST['idea_id']);
    $user_id = getUserID(); // Assuming you have a function to get the logged-in user's ID


    if ($action == 'like') {
        $sql_check = "SELECT * FROM likes WHERE user_id = ? AND idea_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $idea_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 0) {
            $sql = "INSERT INTO likes (user_id, idea_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $idea_id);
            $stmt->execute();
            $stmt->close();
        }
        $stmt_check->close();
    } elseif ($action == 'dislike') {
        $sql_check = "SELECT * FROM dislikes WHERE user_id = ? AND idea_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $user_id, $idea_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 0) {
            $sql = "INSERT INTO dislikes (user_id, idea_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $idea_id);
            $stmt->execute();
            $stmt->close();
        }
        $stmt_check->close();
    }

    $conn->close();
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
