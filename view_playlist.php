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

$query = "SELECT playlist_id, name, num_songs FROM playlists";
$result = $conn->query($query);

$playlists = [];
while ($row = $result->fetch_assoc()) {
    $playlists[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Playlists</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 30px;
        }

        .playlist {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .playlist h3 {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .playlist-name {
            font-size: 18px;
        }

        .playlist-details {
            margin-top: 10px;
            color: #666;
            font-style: italic;
        }

        .playlist-actions {
            margin-top: 10px;
        }

        button {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>My Playlists</h1>
        
        <?php foreach ($playlists as $playlist): ?>
            <div class="playlist">
                <h3><?php echo htmlspecialchars($playlist['name']); ?></h3>
                <p class="playlist-name">
                    Number of Songs: <?php echo htmlspecialchars($playlist['num_songs']); ?> songs
                </p>
                <div class="playlist-details" style="display: none;">
                    Extra details for <?php echo htmlspecialchars($playlist['name']); ?>
                </div>
                <div class="playlist-actions">
                    <form method="post" action="delete_playlist.php" onsubmit="return confirm('Are you sure you want to delete this playlist?')">
                        <input type="hidden" name="playlist_id" value="<?php echo $playlist['playlist_id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const playlistNames = document.querySelectorAll('.playlist-name');
            playlistNames.forEach(name => {
                name.addEventListener('click', () => {
                    const details = name.nextElementSibling;
                    details.style.display = details.style.display === 'block' ? 'none' : 'block';
                });
            });
        });
    </script>
</body>
</html>
