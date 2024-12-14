<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO program_heads (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
        echo "Registration successful.";
        echo "<a href='login_program_head.php'>Login</a>";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}
$conn->close();
?>
