<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if the profile is not created yet
if (!isset($_SESSION['profile_created'])) {
    header("Location: create_profile.php");
    exit();
}

// Database connection (replace with your database details)
require_once 'db.php';

// Handle profile updates
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission for profile updates
    
    // Your code to update profile information goes here
    
    // Redirect to the profile page or any other desired page after updating profile information
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Edit Profile</h1>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <!-- Profile picture input -->
                    <div class="form-group text-center">
                        <label for="profile_picture"><i class="bi bi-camera"></i> Profile Picture:</label><br>
                        <div class="profile-picture-container" onclick="document.getElementById('profile_picture_input').click()">
                            <?php if (isset($_SESSION['profile_picture'])): ?>
                                <img src="<?php echo $_SESSION['profile_picture']; ?>" alt="Profile Picture" class="profile-picture" id="profile_picture_preview">
                            <?php else: ?>
                                <div class="overlay">
                                    <i class="bi bi-camera"></i>
                                </div>
                                <img src="" alt="Profile Picture" class="profile-picture" id="profile_picture_preview" style="display: none;">
                            <?php endif; ?>
                        </div>
                        <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*" onchange="previewProfilePicture(event)">
                    </div>
                    <!-- Form fields to edit profile information -->
                    <div class="form-group">
                        <label for="bio"><i class="bi bi-person"></i> Bio:</label>
                        <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo isset($_SESSION['bio']) ? $_SESSION['bio'] : ''; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="location"><i class="bi bi-geo-alt"></i> Location:</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo isset($_SESSION['location']) ? $_SESSION['location'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="interests"><i class="bi bi-heart"></i> Interests:</label>
                        <input type="text" class="form-control" id="interests" name="interests" value="<?php echo isset($_SESSION['interests']) ? $_SESSION['interests'] : ''; ?>">
                    </div>
                    <!-- Button to save changes -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and additional scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewProfilePicture(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('profile_picture_preview');
                output.src = reader.result;
                output.style.display = 'block';
                var overlay = document.querySelector('.overlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>
