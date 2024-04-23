<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A1834;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        #left-image, #right-image {
            flex: 1;
            position: relative;
            height: 100%;
            overflow: hidden;
        }

        #left-image img, #right-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            filter: brightness(80%);
        }

        #login-form {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .register-link {
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #f44336;
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <div id="left-image">
        <img src="https://images.unsplash.com/photo-1614680376573-df3480f0c6ff?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bXVzaWMlMjBsb2dvfGVufDB8fDB8fHww" alt="Left Image">
    </div>
    <div id="login-form">
        <h2>User Login</h2>
        <form action="login.php" method="POST">
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required><br>

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" required><br>

            <?php
            session_start();
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $email = $_POST["Email"];
                $password = $_POST["Password"];

                $servername = "localhost";
                $username_db = "root";
                $password_db = "";
                $dbname = "music_database";

                $conn = new mysqli($servername, $username_db, $password_db, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
                $result = $conn->query($query);

                if ($result->num_rows == 1) {
                    $row = $result->fetch_assoc();

                    $_SESSION["user_id"] = $row["email"];
                    $_SESSION["user_name"] = $row["name"];
                    $_SESSION["usertype"] = $row["usertype"];
                    
                    if ($_SESSION["usertype"] === "Singer") {
                        $singerQuery = "SELECT singer_id FROM singer WHERE email='$email'";
                        $singerResult = $conn->query($singerQuery);
            
                        if ($singerResult->num_rows == 1) {
                            $singerRow = $singerResult->fetch_assoc();
                            $_SESSION["singer_id"] = $singerRow["singer_id"];
                        }
                    }
                    header("Location: http://localhost/music_database/home.php");
                    exit();
                } else {
                    echo '<div class="error-message">Invalid login credentials.</div>';
                }
                $conn->close();
            }
            ?>

            <input type="submit" value="Login">
        </form>
        <a class="register-link" href="signup.php">Don't have an account? Click here to register.</a>
    </div>
    <div id="right-image">
        <img src="https://images.unsplash.com/photo-1614680376573-df3480f0c6ff?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bXVzaWMlMjBsb2dvfGVufDB8fDB8fHww" alt="Right Image">
    </div>
</body>
</html>
