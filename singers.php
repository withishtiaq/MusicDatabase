<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$singers = [];

$query = "SELECT singer_id, s_name, COUNT(singer_id_songs) AS num_songs FROM singer LEFT JOIN songs ON singer.singer_id = songs.singer_id_songs GROUP BY singer.singer_id";
$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $singers[] = $row;
        }
    } else {
        echo "No singers found in the database.";
    }
} else {

    echo "Error executing query: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Singers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A1834;
            color: #fff;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: black; 
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <div class="container">
        <h2>List of Singers</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Number of Songs</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($singers as $singer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($singer['s_name']); ?></td>
                        <td><?php echo htmlspecialchars($singer['num_songs']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a class="btn" href="songupload.php">Upload Songs</a>
    </div>
</body>
</html>
