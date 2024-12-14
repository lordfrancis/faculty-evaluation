<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Faculty our Faculty Evaluation Tool!</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header>
        <h1>HomePage</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="login_student.php">Student Login</a></li>
            <li><a href="login_program_head.php">Program Head Login</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <h2>About Me</h2>
            <p><?php echo "Hello! My name is Lord Francis Navarro. This project is a result from my requirement for my Doctor in Information Technology Course in Cebu Institute of Technology University. This website is a basic student evaluation system that uses NLP to analyze students' comments. "; ?></p>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Faculty Evaluation</p>
    </footer>
</body>
</html>
