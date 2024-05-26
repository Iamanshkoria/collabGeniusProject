<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // User is not logged in, redirect to the signup page
    header("Location: register.php");
    exit();
}

// Check if the user has created a profile
if (!isset($_SESSION['profile_created'])) {
    // User has not created a profile, redirect to the profile creation page
    header("Location: create_profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea/Product Submission</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS styles */
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        .container h2 {
            color: #333;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-group label {
            font-size: 18px;
            font-weight: bold;
        }

        .form-control {
            font-size: 16px;
            border-radius: 8px;
        }

        .submit-btn {
            font-size: 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
        }

        /* Image preview styles */
        .image-wrapper {
            position: relative;
            width: 100%;
            height: auto;
            border: 2px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); /* Add box shadow for a lifted effect */
            padding: 5px; /* Add padding to prevent border merging */
        }

        #imagePreview {
            max-width: 100%;
            max-height: 300px;
            height: auto;
            display: block;
            border-radius: 8px;
            object-fit: cover; /* Ensure the image covers the container */
            transition: all 0.3s ease;
        }

        #imagePreview:hover {
            transform: scale(1.05);
            opacity: 1;
            filter: brightness(80%); /* Decrease brightness on hover for a highlighted effect */
        }

        /* Category selection styles */
        .title-group label {
            color: #3498DB;
        }

        .description-group label {
            color: #E74C3C;
        }

        .category-group label {
            color: #27AE60;
        }

        .tags-group label {
            color: #FFA500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-lightbulb mr-2"></i>Submit Your Idea/Product</h2>
        <form action="submit.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>">
            <div class="form-group title-group">
                <label for="title"><i class="fas fa-pencil-alt mr-2"></i>Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group description-group">
                <label for="description"><i class="fas fa-align-left mr-2"></i>Description:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group category-group">
                <label for="category"><i class="fas fa-tag mr-2"></i>Category:</label>
                <select class="form-control" id="category" name="category">
                    <option value="Marketing">Marketing</option>
                    <option value="Technology">Technology</option>
                    <option value="Business">Business</option>
                </select>
            </div>
            <div class="form-group tags-group">
                <label for="tags"><i class="fas fa-tags mr-2"></i>Tags (comma separated):</label>
                <input type="text" class="form-control" id="tags" name="tags">
            </div>
            <div class="form-group">
                <label for="image"><i class="fas fa-image mr-2"></i>Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" onchange="previewImage(this)">
                <div class="image-wrapper">
                    <img id="imagePreview" class="mt-2 img-thumbnail d-none" src="#" alt="Preview">
                </div>
            </div>
            <div class="form-group">
                <input type="checkbox" class="form-check-input" id="allow_participation" name="allow_participation" checked>
                <label class="form-check-label" for="allow_participation">Allow Participation in Competitions</label>
            </div>
            <button type="submit" class="btn btn-primary submit-btn"><i class="fas fa-upload mr-2"></i>Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and Font Awesome JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <!-- Custom JavaScript for image preview (if needed) -->
    <script>
        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>
