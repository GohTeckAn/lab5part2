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
    // Fetch user data based on matric
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['matric'])) {
    $original_matric = $_POST['original_matric'];
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update user data
    $sql = "UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $matric, $name, $role, $original_matric);
    if ($stmt->execute()) {
        echo "User updated successfully";
    } else {
        echo "Error updating user: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    header("Location: display_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .link {
            margin-top: 10px;
        }
        .link a {
            color: #4CAF50;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update User</h2>
        <form action="update_user.php" method="POST">
            <input type="hidden" name="original_matric" value="<?php echo $user['matric']; ?>">
            <label for="matric">Matric:</label>
            <input type="text" id="matric" name="matric" value="<?php echo $user['matric']; ?>" required><br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required><br>
            <label for="role">Role:</label>
            <input type="text" id="role" name="role" value="<?php echo $user['role']; ?>" required><br>
            <input type="submit" value="Update">
            <div class="link">
                <a href="display_users.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>