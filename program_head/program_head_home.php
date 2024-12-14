<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login_program_head.php");
    exit();
}

include '../db.php';

// Fetch existing courses
$sql = "SELECT * FROM courses ORDER BY created_at DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Head Home</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>Program Head Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <!-- Add Course Form -->
    <h3>Add New Course</h3>
    <form action="add_course.php" method="post">
        <label for="course_code">Course Code:</label>
        <input type="text" id="course_code" name="course_code" required><br><br>
        
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" required><br><br>
        
        <label for="teacher_name">Teacher Name:</label>
        <input type="text" id="teacher_name" name="teacher_name" required><br><br>
        
        <input type="submit" value="Add Course">
    </form>

    <!-- Display Existing Courses -->
    <h3>Existing Courses</h3>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Teacher Name</th>
                <th>Added By</th>
                <th>Date Added</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="view_results.php?course_id=<?php echo $row['id']; ?>">View Results</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No courses added yet.</p>
    <?php endif; ?>

    <br>
    <a href="../logout.php">Logout</a>
</body>
</html>