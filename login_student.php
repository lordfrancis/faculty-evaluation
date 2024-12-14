<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <form action="login_student_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <a href="register_student.php">Register as a new student</a>
</body>
</html>
