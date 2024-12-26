<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookEasy - Sign Up</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h1>BookEasy</h1>
        <h4>Create an Account</h4>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="form-group">
                <label for="firstn">First Name</label>
                <input type="text" name="firstn" id="firstn" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="lastn">Last Name</label>
                <input type="text" name="lastn" id="lastn" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="pno">Phone Number</label>
                <input type="number" name="pno" id="pno" placeholder="1234567890" required>
            </div>
            <div class="form-group">
                <label for="mail">Email</label>
                <input type="email" name="mail" id="mail" placeholder="example@gmail.com" required>
            </div>
            <div class="form-group">
                <label for="pword">Password</label>
                <input type="password" name="pword" id="pword" placeholder="Enter your password" required>
            </div>
            <button type="submit" name="submit" class="btn-submit">Sign Up</button>
        </form>
        <div class="form-footer">
            Already have an account? <a href="login.php">Login Here</a>
        </div>
    </div>
</body>
</html>

<?php
include 'dbc.php';
if (isset($_POST['submit'])) {
    $fn = $_POST["firstn"];
    $ln = $_POST["lastn"];
    $em = $_POST["mail"];
    $pn = $_POST["pno"];
    $ps = $_POST["pword"];

    $inq = "INSERT INTO users (fname, lname, email, phn, pass)
            VALUES ('$fn', '$ln', '$em', '$pn', '$ps')";

    $qu = mysqli_query($con, $inq);
    if ($qu) {
        echo '<script>alert("Submitted successfully");</script>';
    } else {
        echo '<script>alert("Error");</script>';
    }
}
?>
