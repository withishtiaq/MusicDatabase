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
if (isset($_GET['playlist_id'])) {
    $playlist_id = intval($_GET['playlist_id']);

    $sql = "SELECT p.playlist_id, p.name AS playlist_name, s.name AS song_name
            FROM playlists p
            LEFT JOIN playlist_songs ps ON p.playlist_id = ps.playlist_id
            LEFT JOIN songs s ON ps.song_id = s.id
            WHERE p.playlist_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $playlist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $playlist = null;
        $songs = [];

        while ($row = $result->fetch_assoc()) {
            if ($playlist === null) {
                $playlist = [
                    'id' => $row['playlist_id'],
                    'name' => $row['playlist_name']
                ];
            }

            $songs[] = $row['song_name'];
        }
        echo "<h1>{$playlist['name']}</h1>";
        echo "<p>Number of songs: " . count($songs) . "</p>";
        if (count($songs) > 0) {
            echo "<h2>Songs:</h2>";
            echo "<ul>";
            foreach ($songs as $song) {
                echo "<li>{$song}</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p>Playlist not found.</p>";
    }
    $stmt->close();
} else {
    echo "<p>No playlist ID provided.</p>";
}
$conn->close();
?>
