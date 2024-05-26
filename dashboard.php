<?php
require_once('auth.php');
redirectToLogin(); 

require_once 'db.php';

$sql = "SELECT * FROM competitions";
$result = $conn->query($sql);
$competitions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $competitions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:antiquewhite;
            color: #333;
            font-family: 'Arial', sans-serif;
            animation: fadeIn 1s ease-in-out;
        }

        .container {
            margin-top: 50px;
            padding: 0 20px;
        }

        h2, h3 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
            animation: slideIn 1s ease-in-out;
        }

        ul {
            list-style-type: none;
            padding: 0;
            animation: fadeInUp 1s ease-in-out;
        }

        li {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
            padding: 15px;
            font-size: 1.2rem;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        li:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            li {
                font-size: 1rem;
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            li {
                font-size: 0.9rem;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome to the Dashboard</h2>
        <h3>Competitions</h3>
        <ul>
            <?php foreach ($competitions as $competition): ?>
                <li><?php echo htmlspecialchars($competition['title']); ?> - <?php echo htmlspecialchars($competition['description']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
