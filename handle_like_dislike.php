<?php
session_start();
require 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ideaId = $_POST['idea_id'];
    $type = $_POST['type'];
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in session

    if ($type == 'like') {
        // Check if user has already liked this idea
        $checkLike = "SELECT * FROM likes WHERE user_id = '$userId' AND idea_id = '$ideaId'";
        $resultLike = $conn->query($checkLike);

        if ($resultLike->num_rows == 0) {
            // User hasn't liked this idea yet
            $sql = "INSERT INTO likes (user_id, idea_id) VALUES ('$userId', '$ideaId')";
            $conn->query($sql);
            
            // Remove dislike if it exists
            $sql = "DELETE FROM dislikes WHERE user_id = '$userId' AND idea_id = '$ideaId'";
            $conn->query($sql);
        }
    } elseif ($type == 'dislike') {
        // Check if user has already disliked this idea
        $checkDislike = "SELECT * FROM dislikes WHERE user_id = '$userId' AND idea_id = '$ideaId'";
        $resultDislike = $conn->query($checkDislike);

        if ($resultDislike->num_rows == 0) {
            // User hasn't disliked this idea yet
            $sql = "INSERT INTO dislikes (user_id, idea_id) VALUES ('$userId', '$ideaId')";
            $conn->query($sql);

            // Remove like if it exists
            $sql = "DELETE FROM likes WHERE user_id = '$userId' AND idea_id = '$ideaId'";
            $conn->query($sql);
        }
    }

    // Get updated counts
    $likeCountQuery = "SELECT COUNT(*) AS count FROM likes WHERE idea_id = '$ideaId'";
    $likeCountResult = $conn->query($likeCountQuery);
    $likeCount = $likeCountResult->fetch_assoc()['count'];

    $dislikeCountQuery = "SELECT COUNT(*) AS count FROM dislikes WHERE idea_id = '$ideaId'";
    $dislikeCountResult = $conn->query($dislikeCountQuery);
    $dislikeCount = $dislikeCountResult->fetch_assoc()['count'];

    echo json_encode([
        'success' => true,
        'like_count' => $likeCount,
        'dislike_count' => $dislikeCount
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

$conn->close();
?>
