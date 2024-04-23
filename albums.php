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
function isAuthorizedToAddAlbum($conn) {
    if (isset($_SESSION['user_id']) && $_SESSION['usertype'] === "Singer") {
        return true;
    }
    return false;
}
$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    if (!empty($searchTerm)) {
        header("Location: http://localhost/music_database/search.php?search=" . urlencode($searchTerm));
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $album_name = $_POST["album_name"];
    $num_of_songs = $_POST["num_of_songs"];
    $type = $_POST["type"];
    $release_date = $_POST["release_date"];
    $singer_id = $_SESSION["singer_id"]; 
    if ($_SESSION["usertype"] !== "Singer") {
        echo "Error adding album. You are not authorized as a singer.";
    } else {
        $insert_album_sql = "INSERT INTO album (album_name, no_of_songs, type, release_date, singer_id_fk) 
        VALUES ('$album_name', '$num_of_songs', '$type', '$release_date', '$singer_id')";

        if ($conn->query($insert_album_sql) === TRUE) {
            header("Location: http://localhost/music_database/albums.php");
            exit();
        } else {
            echo "Error adding album: " . $conn->error;
        }
    }
}
$query_albums = "SELECT * FROM album";
$result_albums = $conn->query($query_albums);
$albums = [];
while ($row = $result_albums->fetch_assoc()) {
    $albums[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Albums and Songs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0A1832, #1D2A4E);
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

        form {
            background-color: #2C3E50;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            color: #fff;
            margin-bottom: 10px;
        }

        form input[type=text],
        form input[type=number],
        form input[type=date] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        form input[type=submit] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            color: #fff;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        p {
            color: #fff;
        }

        .message-box {
            background-color: #f44336;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            display: <?php echo (empty($searchTerm) && isset($_GET['search'])) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <div class="container">
        <h2>Add New Album</h2>
        <?php if (isAuthorizedToAddAlbum($conn)): ?>
            <form action="albums.php" method="POST">
                <label for="album_name">Album Name:</label>
                <input type="text" id="album_name" name="album_name" required><br>

                <label for="num_of_songs">Number of Songs:</label>
                <input type="number" id="num_of_songs" name="num_of_songs" required><br>

                <label for="type">Type:</label>
                <input type="text" id="type" name="type" required><br>

                <label for="release_date">Release Date:</label>
                <input type="date" id="release_date" name="release_date" value="<?php echo date('Y-m-d'); ?>" required><br>

                <input type="submit" value="Add Album">
            </form>
        <?php else: ?>
            <p>You are not authorized as a singer to add a new album.</p>
        <?php endif; ?>

        <hr>

        <h2>Albums</h2>
        <table>
            <thead>
                <tr>
                    <th>Album Name</th>
                    <th>Number of Songs</th>
                    <th>Type</th>
                    <th>Release Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($albums as $album): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($album['album_name']); ?></td>
                        <td><?php echo htmlspecialchars($album['no_of_songs']); ?></td>
                        <td><?php echo htmlspecialchars($album['type']); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($album['release_date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

