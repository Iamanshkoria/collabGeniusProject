<?php
session_start();


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';


$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    
    $_SESSION['profile_created'] = true;
    header("Location: home.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $bio = $_POST['bio'];
    $location = $_POST['location'];
    $interests = $_POST['interests'];

    
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $profile_picture = '';
    if ($_FILES["profile_picture"]["error"] == UPLOAD_ERR_OK) {
        $target_file = $upload_dir . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            echo '<script>Sorry, there was an error uploading your file.</script>';
            exit();
        }
    }

    
    $stmt = $conn->prepare("INSERT INTO profiles (user_id, bio, location, interests, profile_picture) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error: " . $conn->error);
    }

    $stmt->bind_param("issss", $user_id, $bio, $location, $interests, $profile_picture);

    
    if ($stmt->execute()) {
       
        $_SESSION['profile_success'] = "Profile created successfully.";
      
        $stmt->close();
        $conn->close();
       
        echo '<script>alert("Profile Created successfully!"); window.location.href = "home.php";</script>';
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="create_profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Create Profile</div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                       
                        <div class="form-group text-center mb-4">
                            <label for="profile_picture" class="mb-0">
                                <div class="profile-picture-container" id="profile_picture_container">
                                    <img id="profile_picture_preview" src="user-icon-blue.png" class="profile-picture img-fluid rounded-circle">
                                    <input type="file" class="form-control-file d-none" id="profile_picture" name="profile_picture" onchange="previewProfilePicture(this)">
                                    <div class="overlay">
                                        <i class="bi bi-camera-fill"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                      
                        <div class="form-group">
                            <label for="bio"><i class="bi bi-file-earmark-text text-primary"></i> Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="location"><i class="bi bi-geo-alt text-danger"></i> Location</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>
                        <div class="form-group">
                            <label for="interests"><i class="bi bi-heart-fill text-danger"></i> Interests</label>
                            <input type="text" class="form-control" id="interests" name="interests">
                        </div>
                        <button type="submit" class="btn btn-primary btn-advanced"><i class="bi bi-plus-square-fill"></i> Create Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   
    function previewProfilePicture(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('profile_picture_preview').setAttribute('src', e.target.result);
                document.getElementById('profile_picture_preview').style.display = 'block';
                document.querySelector('.overlay').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
