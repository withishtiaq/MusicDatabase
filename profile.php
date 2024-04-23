<?php
session_start();
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: http://localhost/music_database/login.php");
    exit();
}
if (isset($_POST['delete'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "music_database";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];

        $delete_query = "DELETE FROM user WHERE email = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("s", $user_id);

        if ($stmt->execute()) {
            session_unset();
            session_destroy();
            header("Location: http://localhost/music_database/login.php");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "User session not available.";
    }
    $conn->close();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_database";

$name = $email = $phone = $usertype = '';

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT name, phone, email, usertype FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $email = $row["email"];
        $phone = $row["phone"];
        $usertype = $row["usertype"];
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "User session not available.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    padding: 20px;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #4CAF50;
    text-align: center;
    margin-bottom: 20px;
}

table {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: collapse;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #4CAF50;
    color: #fff;
}

.btn-container {
    text-align: center;
    margin-top: 20px;
}

.btn {
    padding: 12px 20px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

.logout-btn {
    background-color: #f44336;
    color: #fff;
}

.delete-btn {
    background-color: #f44336;
    color: #fff;
}

.btn:hover {
    opacity: 0.8;
}

.logout-btn:hover,
.delete-btn:hover {
    background-color: #d32f2f;
}
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>
    <div class="container">
        <h2>User Profile</h2>
        <table>
            <tr>
                <td><b>Name:</b></td>
                <td><?php echo htmlspecialchars($name); ?></td>
            </tr>
            <tr>
                <td><b>Email:</b></td>
                <td><?php echo htmlspecialchars($email); ?></td>
            </tr>
            <tr>
                <td><b>Phone:</b></td>
                <td><?php echo htmlspecialchars($phone); ?></td>
            </tr>
            <tr>
                <td><b>User Type:</b></td>
                <td><?php echo htmlspecialchars($usertype); ?></td>
            </tr>
        </table>
        
        <div class="btn-container">
            <form method="post" onsubmit="return confirm('Are you sure you want to delete your account?');">
                <button type="submit" name="delete" class="btn delete-btn">Delete Account</button>
            </form>
            
            <form method="post">
                <button type="submit" name="logout" class="btn logout-btn">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
