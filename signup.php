<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["Name"];
    $phone = $_POST["Phone"];
    $email = $_POST["Email"];
    $dob = $_POST["DOB"];
    $usertype = $_POST["Usertype"];
    $password = $_POST["Password"];
    $confirmPassword = $_POST["ConfirmPassword"];
    if ($password !== $confirmPassword) {
        $error_message = "Passwords do not match.";
    } else {
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "music_database";
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            $check_email_sql = "SELECT * FROM user WHERE email='$email'";
            $result = $conn->query($check_email_sql);

            if ($result->num_rows > 0) {
                $error_message = "This email is already in use. Please use a different email.";
            } else {
                $insert_user_sql = "INSERT INTO user (name, phone, email, dob, usertype, password) 
                                    VALUES ('$name', '$phone', '$email', '$dob', '$usertype', '$password')";
                if ($conn->query($insert_user_sql) === TRUE) {
                    if ($usertype === "Singer") {
    
                        $insert_singer_sql = "INSERT INTO singer (s_name, phone, email) 
                                              VALUES ('$name', '$phone', '$email')";
                        if ($conn->query($insert_singer_sql) !== TRUE) {
                            echo "Error inserting singer info: " . $conn->error;
                        }
                    }
                    header("Location: http://localhost/music_database/login.php");
                    exit();
                } else {
                    echo "Error inserting user info: " . $conn->error;
                }
            }
        }
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A1832;
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

        #signup-form {
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

        input, select {
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
    <div id="signup-form">
        <h2>User Registration</h2>
        <form action="signup.php" method="POST">
            <?php if (isset($error_message)): ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <label for="Name">Name:</label>
            <input type="text" id="Name" name="Name" required><br>

            <label for="Phone">Phone:</label>
            <input type="text" id="Phone" name="Phone" required><br>

            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required><br>

            <label for="DOB">Date of Birth:</label>
            <input type="date" id="DOB" name="DOB" required><br>

            <label for="Usertype">User Type:</label>
            <select id="Usertype" name="Usertype" required>
                <option value="Listener">Listener</option>
                <option value="Singer">Singer</option>
            </select><br>

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" required><br>

            <label for="ConfirmPassword">Confirm Password:</label>
            <input type="password" id="ConfirmPassword" name="ConfirmPassword" required><br>

            <input type="submit" value="Register">
        </form>
    </div>
    <div id="right-image">
        <img src="https://images.unsplash.com/photo-1614680376573-df3480f0c6ff?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bXVzaWMlMjBsb2dvfGVufDB8fDB8fHww" alt="Right Image">
    </div>
</body>
</html>
