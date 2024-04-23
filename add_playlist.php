<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_database";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['playlist_name'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $playlist_name = $_POST['playlist_name'];
    $songs = isset($_POST['songs']) ? $_POST['songs'] : [];

    $insert_playlist_sql = "INSERT INTO playlists (name) VALUES ('$playlist_name')";
    if ($conn->query($insert_playlist_sql) === TRUE) {
        $new_playlist_id = $conn->insert_id;

        foreach ($songs as $song_id) {
            $insert_playlist_song_sql = "INSERT INTO playlist_songs (playlist_id, song_id) VALUES ('$new_playlist_id', '$song_id')";
            $conn->query($insert_playlist_song_sql);
        }

        echo "Playlist added successfully!";
    } else {
        echo "Error: " . $insert_playlist_sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    header("Location: playlist.php");
    exit();
}
?>
