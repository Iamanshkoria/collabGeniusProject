<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote for Ideas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 60px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .idea-card, .competition-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .idea-card h3, .competition-card h3 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .idea-card p, .competition-card p {
            color: #666666;
            margin-bottom: 15px;
        }
        .user-details {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .user-details img.profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .user-details span.username {
            font-weight: bold;
            color: #007bff;
            font-size: 18px;
        }
        .card-content {
            margin-top: 10px;
            width: 100%;
        }
        .category-button {
            margin-right: 10px;
        }
        .tags {
            margin-top: 10px;
        }
        .idea-image {
            width: 500px;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .idea-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: pulse 1s infinite alternate;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.03);
            }
            100% {
                transform: scale(1);
            }
        }
        .see-more-icon {
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }
        .see-more-icon:hover {
            color: #0056b3;
        }
        .hidden-description {
            display: none;
        }
        .visible-description {
            display: block;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Vote for Ideas</h2>
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by title or username" name="search_query">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
        <?php
       
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user_id = $_SESSION['user_id'];
        
        
        require_once 'db.php';

        
        if (!isset($user_id)) {
            echo "User ID is not set in session.";
            exit;
        }

    
        $search_query = "";
        if (isset($_GET['search_query'])) {
            $search_query = $_GET['search_query'];
        }

       
        $sql = "SELECT ideas.*, users.username AS submitter_username, profiles.profile_picture AS submitter_profile_pic
                FROM ideas
                JOIN users ON ideas.user_id = users.user_id
                LEFT JOIN profiles ON ideas.user_id = profiles.user_id
                WHERE (ideas.idea_id IN (SELECT idea_id FROM competition_participate WHERE user_id = ?))
                AND (ideas.title LIKE ? OR users.username LIKE ?)";

        $stmt = $conn->prepare($sql);
        $like_search_query = "%" . $search_query . "%";
        $stmt->bind_param("iss", $user_id, $like_search_query, $like_search_query);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="idea-card">';
                echo '<div class="user-details">';
                echo '<img src="' . $row["submitter_profile_pic"] . '" alt="' . $row["submitter_username"] . '" class="profile-pic">';
                echo '<span class="username">' . $row["submitter_username"] . '</span>';
                echo '</div>';
                echo '<p><strong>Title:</strong> ' . $row["title"] . '</p>';
                echo '<p><strong>Description:</strong> ' . $row["description"] . '</p>';
                echo '<img src="' . $row["image_path"] . '" alt="' . $row["title"] . '" class="idea-image">';
                echo '<form method="post" action="vote_action.php">';
                echo '<input type="hidden" name="idea_id" value="' . $row["idea_id"] . '">';
                echo '<button type="submit" class="btn btn-primary">Vote</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "No ideas found.";
        }

        $stmt->close();
        $conn->close();
        ?>

        <?php
       
        if (isset($_SESSION['vote_message'])) {
            echo '<script>alert("' . $_SESSION['vote_message'] . '");</script>';
            unset($_SESSION['vote_message']); 
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
