<?php

session_start();
require 'db.php'; // Include your database connection

// Function to get like count
function getLikeCount($conn, $idea_id) {
    $query = "SELECT COUNT(*) AS count FROM likes WHERE idea_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idea_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $likeCount = $result->fetch_assoc()['count'];
    $stmt->close();
    return $likeCount;
}

// Function to get dislike count
function getDislikeCount($conn, $idea_id) {
    $query = "SELECT COUNT(*) AS count FROM dislikes WHERE idea_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idea_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dislikeCount = $result->fetch_assoc()['count'];
    $stmt->close();
    return $dislikeCount;
}

// Fetch ideas from the database
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $searchQuery = '%' . $conn->real_escape_string($_GET['search_query']) . '%';
    $sql = "SELECT ideas.*, users.username AS submitter_username, profiles.profile_picture AS submitter_profile_pic 
            FROM ideas 
            JOIN users ON ideas.user_id = users.user_id 
            LEFT JOIN profiles ON ideas.user_id = profiles.user_id 
            WHERE ideas.title LIKE ? OR users.username LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
} else {
    $sql = "SELECT ideas.*, users.username AS submitter_username, profiles.profile_picture AS submitter_profile_pic 
            FROM ideas 
            JOIN users ON ideas.user_id = users.user_id 
            LEFT JOIN profiles ON ideas.user_id = profiles.user_id";
    $stmt = $conn->prepare($sql);
}

// Check for category filter
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryFilter = $conn->real_escape_string($_GET['category']);
    if ($categoryFilter != 'All') {
        $sql .= " WHERE ideas.category = ?";
        $stmt = $conn->prepare($sql); // Prepare new statement
        $stmt->bind_param("s", $categoryFilter);
    }
}

$sql .= " ORDER BY ideas.idea_id DESC LIMIT 5";
$stmt->execute();
$result = $stmt->get_result();

// Fetch competitions
$sql_competitions = "SELECT * FROM competitions WHERE end_datetime > NOW() ORDER BY competition_id DESC";
$result_competitions = $conn->query($sql_competitions);
?>
<!DOCTYPE html>
<html lang="en">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 60px; /* Adjust based on your header's height */
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Idea card styles */
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
        }

        .see-more-icon {
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s ease-in-out
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

        .like-dislike-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .like-dislike-buttons button {
            margin-right: 10px;
        }
        
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h2>Recent Ideas/Products</h2>
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search by title or username" name="search_query">
            <div class="input-group-append">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>

            </div>
        </div>
    </form>
    <?php
    echo '<h2>Current Competitions</h2>';
    
    if ($result_competitions->num_rows > 0) {
        while ($row = $result_competitions->fetch_assoc()) {
            echo '<div class="competition-card">';
            echo '<h3>' . htmlspecialchars($row["title"]) . '</h3>';
            echo '<p><strong>Description:</strong> ' . htmlspecialchars($row["description"]) . '</p>';
            echo '<p><strong>Duration:</strong> ' . htmlspecialchars($row["start_datetime"]) . ' to ' . htmlspecialchars($row["end_datetime"]) . '</p>';
            echo '<a href="participate.php?competition_id=' . urlencode($row["competition_id"]) . '" class="btn btn-primary">Participate</a>';
            echo '</div>';
        }
    
    }
    else {
        echo '<p>No active competitions found.</p>';
    }
    ?>
   
    <div class="mb-3">
        <a href="home.php" class="btn btn-primary category-button">All</a>
        <a href="home.php?category=Marketing" class="btn btn-primary category-button">Marketing</a>
        <a href="home.php?category=Technology" class="btn btn-primary category-button">Technology</a>
        <a href="home.php?category=Business" class="btn btn-primary category-button">Business</a>
    </div>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="idea-card">';
            echo '<div class="user-details">';
            echo '<img src="' . ($row["submitter_profile_pic"] ? $row["submitter_profile_pic"] : 'default_profile.png') . '" alt="' . $row["submitter_username"] . '" class="profile-pic">';
            echo '<span class="username">' . $row["submitter_username"] . '</span>';
            echo '</div>';
            echo '<div class="card-content">';
            echo '<p><strong>Title:</strong> ' . htmlspecialchars($row["title"]) . '</p>';
            echo '<p><strong>Description:</strong> <span class="visible-description">' . htmlspecialchars(substr($row["description"], 0, 100)) . '</span>';
            echo '<span class="hidden-description">' . htmlspecialchars(substr($row["description"], 100)) . '</span>';
            echo '<i class="fas fa-chevron-down see-more-icon" onclick="toggleDescription(this)"></i>';
            echo '</p>';
            echo '<p><strong>Category:</strong> ' . htmlspecialchars($row["category"]) . '</p>';
            echo '<p><strong>Tags:</strong> ' . htmlspecialchars($row["tags"]) . '</p>';
            echo '<img src="' . htmlspecialchars($row["image_path"]) . '" alt="' . htmlspecialchars($row["title"]) . '" class="idea-image">';
            
           
            echo '<div class="like-dislike-buttons">';
            echo '<form method="POST" class="like-form">';
            echo '<input type="hidden" name="idea_id" value="' . $row["idea_id"] . '">';
            echo '<input type="hidden" name="type" value="like">';
            echo '<button type="button" class="btn btn-success like-button"><i class="fas fa-thumbs-up"></i> <span class="like-count">' . getLikeCount($conn, $row["idea_id"]) . '</span></button>';
            echo '</form>';
            
            echo '<form method="POST" class="dislike-form">';
            echo'<input type="hidden" name="idea_id" value="' . $row["idea_id"] . '">';
            echo '<input type="hidden" name="type" value="dislike">';
            echo '<button type="button" class="btn btn-danger dislike-button"><i class="fas fa-thumbs-down"></i> <span class="dislike-count">' . getDislikeCount($conn, $row["idea_id"]) . '</span></button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
        }
    } else {
        echo "No ideas/products found.";
    }
    
    
    $conn->close();
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    function toggleDescription(icon) {
        var description = icon.parentElement.querySelector('.hidden-description');

        if (description.style.display === 'none' || description.style.display === '') {
            description.style.display = 'block'; 
            icon.classList.remove('fa-chevron-down'); 
            icon.classList.add('fa-chevron-up');
        } else {
            description.style.display = 'none'; 
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    $(document).ready(function() {
        $('.like-button').on('click', function() {
            var form = $(this).closest('form');
            var ideaId = form.find('input[name="idea_id"]').val();
            var type = form.find('input[name="type"]').val();
            
            $.ajax({
                url: 'handle_like_dislike.php',
                method: 'POST',
                data: {
                    idea_id: ideaId,
                    type: type
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        form.find('.like-count').text(data.like_count);
                        form.siblings('.dislike-form').find('.dislike-count').text(data.dislike_count);
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        });

        $('.dislike-button').on('click', function() {
            var form = $(this).closest('form');
            var ideaId = form.find('input[name="idea_id"]').val();
            var type = form.find('input[name="type"]').val();
            
            $.ajax({
                url: 'handle_like_dislike.php',
                method: 'POST',
                data: {
                    idea_id: ideaId,
                    type: type
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        form.find('.dislike-count').text(data.dislike_count);
                        form.siblings('.like-form').find('.like-count').text(data.like_count);
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        });
    });
</script>
</body>
</html>    