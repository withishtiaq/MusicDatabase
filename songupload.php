<?php
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'Singer') {
    echo "<p style='color: red; font-weight: bold;'>Error: You are not authorized to upload songs.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $genre = $_POST['genre'];
    $audio_url = $_POST['audio_url'];
    $selected_album = $_POST['album'];



    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "music_database";


    $conn = new mysqli($servername, $username, $password, $dbname);


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $user_id = $_SESSION['user_id'];
    $user = $_SESSION['user_name'];
    $singer_id =$_SESSION['singer_id'];


    $insert_query = "INSERT INTO songs (name, singer, genre, audio_url, singer_id_songs, album_name_fk) 
                     VALUES ('$name', '$user', '$genre', '$audio_url', '$singer_id', '$selected_album')";

    if ($conn->query($insert_query) === TRUE) {
        echo "<p style='color: green; font-weight: bold;'>Song uploaded successfully!</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>Error uploading song: " . $conn->error . "</p>";
    }

    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload New Song</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A1834;
            color: #fff;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        h2 {
            color: #4CAF50;
        }

        form {
            background-color: #2C3E50;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            color: #fff;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="url"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <div class="container">
        <h2>Upload New Song</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="name">Song Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" required>

            <label for="audio_url">Audio URL:</label>
            <input type="url" id="audio_url" name="audio_url" required>

            <label for="album">Select Album:</label>
            <select id="album" name="album">
                <?php

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "music_database";
                $singer_id =$_SESSION['singer_id'];

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $user_id = $_SESSION['user_id'];
                $album_query = "SELECT album_name FROM album WHERE singer_id_fk = '$singer_id'";
                $result = $conn->query($album_query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['album_name'] . "'>" . $row['album_name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No albums found</option>";
                }

                $conn->close();
                ?>
            </select>

            <input type="submit" value="Upload Song">
        </form>
    </div>
</body>
</html>
