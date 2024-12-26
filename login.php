<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookEasy - Login</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #ffbe58;
            margin-bottom: 10px;
        }

        h4 {
            color: #333333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-group {
            width: 100%;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ffbe58;
        }

        .btn-submit {
            background-color: #ffbe58;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #e0a947;
        }

        .form-footer {
            margin-top: 15px;
            font-size: 14px;
        }

        .form-footer a {
            color: #ffbe58;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>BookEasy</h1>
        <h4>Login</h4>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="example@gmail.com" required>
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" name="pass" id="pass" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="submit" class="btn-submit">Login</button>
        </form>
        <div class="form-footer">
            Don't have an account? <a href="signup.php">Sign Up Here</a>
        </div>
    </div>
</body>
</html>

<?php
session_start();
include 'dbc.php';

if (isset($_POST['submit'])) {
    $username = $_POST['email'];
    $passw = $_POST['pass'];

    $sql = "SELECT * FROM users WHERE email = '$username'";
    $query = mysqli_query($con, $sql);

    $uname_count = mysqli_num_rows($query);
    if ($uname_count) {
        $user_data = mysqli_fetch_assoc($query);
        $_SESSION['name'] = $user_data['email'];
        $stored_pass = $user_data['pass'];

        if ($stored_pass === $passw) {
            echo "<script>alert('Login successful');</script>";
            echo "<script>window.location.href='home2.php';</script>";
        } else {
            echo '<div class="error">Incorrect password.</div>';
        }
    } else {
        echo '<div class="error">Invalid username.</div>';
    }
}
?>
