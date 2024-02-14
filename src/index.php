<?php
session_start();

// Function to connect to the database
function connectDB() {
    $servername = "localhost";
    $username = "root"; // your database username
    $password = ""; // your database password
    $dbname = "user_management"; // your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to get the user's IP address
function getClientIP() {
    // Check for shared internet/ISP IP
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    // Check for IP address from proxy or load balancer
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    // Default: Remote IP address
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Function to get the current timestamp
function getCurrentTimestamp() {
    return date("Y-m-d H:i:s");
}

// Function to handle user login
function loginUser($roll_number, $password) {
    // Check if user exists
    $roll_number = $conn->real_escape_string($roll_number);
    $password = $conn->real_escape_string($password);
    $query = "SELECT * FROM users WHERE roll_number='$roll_number' AND password='$password'";
    $result = $conn->query($query);

    // Check if user exists
    $query = "SELECT * FROM users WHERE roll_number='$roll_number' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        // User exists, set session timestamp and store roll number in session
        $_SESSION['login_timestamp'] = getCurrentTimestamp();
        $_SESSION['user_roll_number'] = $roll_number;

        // Close database connection
        $conn->close();
        return true; // Indicate successful login
    } else {
        // Invalid credentials
        $conn->close();
        return false; // Indicate login failure
    }
}

// Function to save user roll numbers to text files
function saveUserRollNumber($roll_number, $filename) {
    $fileContent = file_get_contents($filename);
    $fileContent .= $roll_number . PHP_EOL;
    file_put_contents($filename, $fileContent);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = connectDB();

    // Process login
    $roll_number = $_POST["roll_number"];
    $password = $_POST["password"];

    if (loginUser($conn, $roll_number, $password)) {
        echo "Login successful!";
    } else {
        echo "Invalid roll number, password, or attempting to login from a different computer.";
    }

    // Check if user spent minimum 3 hours and save roll number accordingly
    if (isset($_SESSION['user_roll_number'])) {
        $userRollNumber = $_SESSION['user_roll_number'];
        $query = "SELECT COUNT(*) AS login_count FROM user_logs WHERE roll_number='$userRollNumber'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $loginCount = $row['login_count'];

            if ($loginCount >= 2) {
                saveUserRollNumber($userRollNumber, 'successful_users.txt');
            } else {
                saveUserRollNumber($userRollNumber, 'unsuccessful_users.txt');
            }
        }
    }

    // Close database connection
    $conn->close();
}

// echo "<table border='1'>";
// echo "<tr><th>Key</th><th>Value</th></tr>";

// foreach ($_SERVER as $key => $value) {
//     echo "<tr><td>$key</td><td>$value</td></tr>";
// }

// echo "</table>";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Roll Number: <input type="text" name="roll_number"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
