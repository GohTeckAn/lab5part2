<?php
session_start(); // Start the session

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_5b";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Validate input
    if (empty($matric) || empty($password)) {
        header("Location: login_error.php");
        exit();
    }

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['matric'] = $matric;
            header("Location: display_users.php");
            exit();
        } else {
            header("Location: login_error.php");
            exit();
        }
    } else {
        header("Location: login_error.php");
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>