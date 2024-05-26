<?php
session_start();
require_once 'db.php';


function checkAdminStatus() {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
      
        header("Location: login.php?error=unauthorized_access");
        exit();
    }
}

// Check if user is an admin before allowing access to create competition
checkAdminStatus();

// Initialize message variable
$message = '';
$success = false;

// PHP code to create a competition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $start_datetime = $_POST['start_datetime']; // Assuming this is in the format 'YYYY-MM-DD HH:MM:SS'
    $end_datetime = $_POST['end_datetime']; // Assuming this is in the format 'YYYY-MM-DD HH:MM:SS'

    $stmt = $conn->prepare("INSERT INTO competitions (title, description, start_datetime, end_datetime) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $start_datetime, $end_datetime);

    if ($stmt->execute()) {
        $competition_id = $stmt->insert_id;
        // Set success message
        $message = 'Competition created successfully!';
        $success = true;
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Competition</title>
   
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Create Competition</h2>
        <!-- Display message if set -->
        <?php if (!empty($message)) : ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="title">Competition Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="start_datetime">Start Date and Time:</label>
                <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
            </div>
            <div class="form-group">
                <label for="end_datetime">End Date and Time:</label>
                <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Competition</button>
        </form>
    </div>
    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Script to display message and redirect -->
    <script>
        // Check if the message is not empty, display it and redirect after 3 seconds
        <?php if ($success): ?>
            alert("<?php echo $message; ?>");
            setTimeout(function() {
                window.location.href = "home.php"; // Change this to the desired redirect page
            }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>