<?php
include("navbar.html");
?>

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

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT id, name, singer, genre, audio_url FROM songs WHERE name LIKE '%$searchTerm%' OR singer LIKE '%$searchTerm%'";
$result = mysqli_query($conn, $sql);

$songs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $songs[] = $row;
}

$query_set = "SELECT id, name, singer, genre, audio_url FROM songs";
$result_songs = mysqli_query($conn, $query_set);
$songs_file = [];
while ($rows = mysqli_fetch_assoc($result_songs)) {
    $songs_file[] = $rows;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }

        .table {
            background-color: #fff;
        }

        .audio-player {
            width: 100%;
        }
    </style>
</head>
<body>
    <section class="intro">
        <div class="bg-image h-100" style="background-color: #f5f7fa;">
            <div class="mask d-flex align-items-center h-100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card shadow-2-strong">
                                <div class="card-body p-0">
                                    <div class="table-responsive table-scroll" style="position: relative; height: 700px;">
                                        <table class="table table-dark mb-0">
                                            <thead style="background-color: #393939;">
                                                <tr class="text-uppercase text-success">
                                                    <th class="ps-4" scope="col">ID</th>
                                                    <th scope="col">Song Name</th>
                                                    <th scope="col">Singer</th>
                                                    <th scope="col">Genre</th>
                                                    <th scope="col">Audio</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($songs as $song) : ?>
                                                    <tr>
                                                        <td class="ps-4"><?php echo $song['id']; ?></td>
                                                        <td><?php echo $song['name']; ?></td>
                                                        <td><?php echo $song['singer']; ?></td>
                                                        <td><?php echo $song['genre']; ?></td>
                                                        <td>
                                                            <audio class="audio-player" controls>
                                                                <source src="<?php echo $song['audio_url']; ?>" type="audio/mpeg">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
