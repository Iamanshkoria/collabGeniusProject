<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Results</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }

        h2 {
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            animation: fadeIn 1s ease-in-out;
        }

        h3 {
            margin-top: 20px;
            color: #007bff;
        }

        .container {
            margin-top: 40px;
        }

        .list-group-item {
            font-size: 1.2rem;
            margin-bottom: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            animation: slideIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .list-group-item {
                font-size: 1rem;
                padding: 15px;
            }
        }

        @media (max-width: 576px) {
            .list-group-item {
                font-size: 0.9rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Voting Results</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3>Idea Rankings</h3>
                <ul class="list-group">
                    <?php
                    require_once 'db.php';

                    $sql = "SELECT ideas.*, COUNT(vote.idea_id) AS vote_count
                            FROM ideas
                            LEFT JOIN vote ON ideas.idea_id = vote.idea_id
                            GROUP BY ideas.idea_id
                            ORDER BY vote_count DESC";
                    $result = $conn->query($sql);

                    if (!$result) {
                        // Query execution failed, display error message
                        echo '<li class="list-group-item">Error executing query: ' . $conn->error . '</li>';
                    } elseif ($result->num_rows > 0) {
                        // Your code to fetch and display results
                        $rank = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<li class="list-group-item">Rank ' . $rank . ': ' . $row["title"] . ' - Votes: ' . $row["vote_count"] . '</li>';
                            $rank++;
                        }
                    } else {
                        // No rows found
                        echo '<li class="list-group-item">No ideas found.</li>';
                    }

                    $conn->close();
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
