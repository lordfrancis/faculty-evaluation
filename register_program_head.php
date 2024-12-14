<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Head Registration</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h2>Program Head Registration</h2>
    <form action="register_program_head_process.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Register">
    </form>
    <a href="login_program_head.php">Login as a program head</a>
</body>
</html>
