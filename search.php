<?php
$searchTerm = $_GET['search'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query_songs = "SELECT id, name, singer, genre FROM songs 
                WHERE name LIKE '%$searchTerm%' OR singer LIKE '%$searchTerm%'";
$result_songs = $conn->query($query_songs);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A1832;
            color: #fff;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 15px;
            border-bottom: 1px solid #ccc;
        }
        li:last-child {
            border-bottom: none;
        }
        h2 {
            color: #4CAF50;
        }
        strong {
            color: #4CAF50;
        }
        p {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Search Results</h2>
        <?php if ($result_songs && $result_songs->num_rows > 0): ?>
            <ul>
                <?php while ($row = $result_songs->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($row['name']); ?></strong> by <?php echo htmlspecialchars($row['singer']); ?>
                        (Genre: <?php echo htmlspecialchars($row['genre']); ?>)
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No matching songs found for '<strong><?php echo htmlspecialchars($searchTerm); ?></strong>'</p>
        <?php endif; ?>
    </div>
</body>
</html>
