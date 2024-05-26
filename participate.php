<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';

$user_id = $_SESSION['user_id'];


function hasSubmittedIdea($user_id, $conn) {
    $sql = "SELECT * FROM ideas WHERE user_id = $user_id";
    $result = $conn->query($sql);
    return $result->num_rows > 0; 
}


function getLatestIdeaId($user_id, $conn) {
    $sql = "SELECT idea_id FROM ideas WHERE user_id = ? ORDER BY idea_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ? $row['idea_id'] : null;
}

// Fetch the competitions
$competitions = [];
$comp_sql = "SELECT competition_id, title FROM competitions";
$comp_result = $conn->query($comp_sql);
while ($row = $comp_result->fetch_assoc()) {
    $competitions[] = $row;
}

$alreadySubmitted = hasSubmittedIdea($user_id, $conn);
$latestIdeaId = getLatestIdeaId($user_id, $conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PARTICIPATE IN COMPETITION</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: linear-gradient( 64.3deg,  rgba(254,122,152,0.81) 17.7%, rgba(255,206,134,1) 64.7%, rgba(172,253,163,0.64) 112.1% );
            color: #333;
            padding-top: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 600px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #2575fc;
        }
        .alert-info {
            background: #e0f7fa;
            color: #00796b;
            border-radius: 50px;
            padding: 10px 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>PARTICIPATE IN COMPETITION</h2>
        <?php if ($alreadySubmitted && $latestIdeaId): ?>
            <form method="post" action="participate_action.php">
                <input type="hidden" name="idea_id" value="<?php echo $latestIdeaId; ?>">
                <div class="form-group">
                    <label for="competition_id">Select Competition:</label>
                    <select name="competition_id" id="competition_id" class="form-control" required>
                        <?php foreach ($competitions as $competition): ?>
                            <option value="<?php echo $competition['competition_id']; ?>"><?php echo $competition['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Participate Now</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info text-center">You have not submitted any ideas for this competition.</div>
        <?php endif; ?>
    </div>
</body>
</html>