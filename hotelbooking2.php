<?php
include 'dbc.php'; // Include your database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hotel Booking System</title>
    <script>
        function calculatePrice() {
            var roomType = document.getElementById("rt").value;
            var price;

            if (roomType === "Single") {
                price = 1500;
            } else if (roomType === "Double") {
                price = 2000;
            } else if (roomType === "Suite") {
                price = 4500;
            } else {
                price = 0;
            }

            document.getElementById("p").value = price;
        }
    </script>
</head>
<body>
    <h1>Hotel Booking System</h1>

    <?php
    // Initialize variables
    $step = 1;
    $location = '';
    $hotel = '';

    // Check for submitted form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['location'])) {
            $location = $_POST['location'];
            $step = 2;
        }

        if (isset($_POST['hotel'])) {
            $hotel = $_POST['hotel'];
            $location = $_POST['location'];
            $step = 3;
        }

        if (isset($_POST['book'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $roomtype = $_POST['roomtype'];
            $price = $_POST['price'];
            $cin = date('Y-m-d', strtotime($_POST['cin']));
            $cout = date('Y-m-d', strtotime($_POST['cout']));

            $query = "INSERT INTO bhotal (location, hotel, name, email, roomtype, price, cin, cout) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssssssss", $location, $hotel, $name, $email, $roomtype, $price, $cin, $cout);

            if ($stmt->execute()) {
                echo "<h2>Booking Confirmed!</h2>";
                echo "Thank you for booking at $hotel in $location.<br>";
            } else {
                echo "<h2>Booking Failed</h2>";
            }
            $step = 1; // Reset to step 1 after booking
        }
    }
    ?>

    <!-- Step 1: Select Location -->
    <?php if ($step === 1): ?>
        <form method="POST">
            <h2>Step 1: Select Location</h2>
            <label for="location">Location:</label>
            <select name="location" id="location" required onchange="this.form.submit()">
                <option value="">--Select Location--</option>
                <?php
                $query = "SELECT DISTINCT location FROM hlocation";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = $location === $row['location'] ? 'selected' : '';
                    echo "<option value='{$row['location']}' $selected>{$row['location']}</option>";
                }
                ?>
            </select>
        </form>
    <?php endif; ?>

    <!-- Step 2: Select Hotel -->
    <?php if ($step === 2): ?>
        <form method="POST">
            <h2>Step 2: Select Hotel</h2>
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
            <label for="hotel">Hotel:</label>
            <select name="hotel" id="hotel" required onchange="this.form.submit()">
                <option value="">--Select Hotel--</option>
                <?php
                $query = "SELECT * FROM hlocation WHERE location = ?";
                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $location);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = $hotel === $row['name'] ? 'selected' : '';
                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>
        </form>
    <?php endif; ?>

    <!-- Step 3: Book Room -->
    <?php if ($step === 3): ?>
        <form method="POST">
            <h2>Step 3: Book a Room</h2>
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">
            <input type="hidden" name="hotel" value="<?php echo htmlspecialchars($hotel); ?>">

            <label for="name">Your Name:</label>
            <input type="text" name="name" id="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <label for="roomtype">Room Type:</label>
            <select name="roomtype" id="rt" onchange="calculatePrice()" required>
                <option value="">--Select Room Type--</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="Suite">Suite</option>
            </select><br>

            <label for="price">Price:</label>
            <input type="text" name="price" id="p" readonly required><br>

            <label for="cin">Check-In Date:</label>
            <input type="date" name="cin" id="cin" required><br>

            <label for="cout">Check-Out Date:</label>
            <input type="date" name="cout" id="cout" required><br>

            <button type="submit" name="book">Book Now</button>
        </form>
    <?php endif; ?>
</body>
</html>
