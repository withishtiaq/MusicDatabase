<?php
include("navbar.html");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['playlist_name'])) {
    $playlist_name = $_POST['playlist_name'];
    $songs = isset($_POST['songs']) ? $_POST['songs'] : [];

    $insert_playlist_sql = "INSERT INTO playlists (name) VALUES ('$playlist_name')";
    if ($conn->query($insert_playlist_sql) === TRUE) {
        $new_playlist_id = $conn->insert_id;

        foreach ($songs as $song_id) {
            $insert_playlist_song_sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES ('$new_playlist_id', '$song_id')";
            $conn->query($insert_playlist_song_sql);
        }
    } else {
        echo "Error: " . $insert_playlist_sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT p.playlist_id, p.name, COUNT(ps.song_id) AS num_songs 
        FROM playlists p
        LEFT JOIN playlist_songs ps ON p.playlist_id = ps.playlist_id
        GROUP BY p.playlist_id, p.name";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .playlist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .playlist-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .num-songs {
            font-size: 14px;
            color: #666;
        }

        .playlist-actions {
            display: flex;
            align-items: center;
        }

        .playlist-actions button {
            margin-left: 10px;
            padding: 8px 12px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Playlists</h2>
        <ul class="playlist-list">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='playlist-item'>
                            <div>
                                <span class='playlist-name'><a href='playlist_details.php?playlist_id={$row['playlist_id']}'>{$row['name']}</a></span>
                                <span class='num-songs'>({$row['num_songs']} songs)</span>
                            </div>
                            <div class='playlist-actions'>
                                <form method='post' action='playlist.php' onsubmit='return confirm(\"Are you sure you want to delete this playlist?\")'>
                                    <input type='hidden' name='playlist_id' value='{$row['playlist_id']}'>
                                    <button type='submit' name='delete_playlist'>Delete</button>
                                </form>
                            </div>
                          </li>";
                }
            } else {
                echo "No playlists found.";
            }
            ?>
        </ul>

        <h2>Add Playlist</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="playlist_name">Playlist Name:</label>
            <input type="text" id="playlist_name" name="playlist_name" required>
            <br><br>
            <label>Songs:</label><br>
            <?php

            $sql_songs = "SELECT id, name FROM songs";
            $result_songs = $conn->query($sql_songs);
            
            while ($row = $result_songs->fetch_assoc()) {
                echo "<input type='checkbox' id='song_{$row['id']}' name='songs[]' value='{$row['id']}'>
                      <label for='song_{$row['id']}'>{$row['name']}</label><br>";
            }
            ?>
            <br>
            <button type="submit">Add Playlist</button>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_playlist'])) {
    $playlist_id = $_POST['playlist_id'];

    $delete_playlist_sql = "DELETE FROM playlists WHERE playlist_id = '$playlist_id'";
    if ($conn->query($delete_playlist_sql) === TRUE) {
        $delete_playlist_songs_sql = "DELETE FROM playlist_songs WHERE playlist_id = '$playlist_id'";
        $conn->query($delete_playlist_songs_sql);
        
        header("Location: playlist.php");
        exit();
    } else {
        echo "Error deleting playlist: " . $conn->error;
    }
}

$conn->close();
?>
