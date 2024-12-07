<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}

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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Delete user data
    $sql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    if ($stmt->execute()) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    header("Location: display_users.php");
    exit();
}
?>