<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login_student.php");
    exit();
}

include '../db.php';

// Fetch courses with evaluation status
$sql = "SELECT c.*, 
        CASE WHEN e.id IS NOT NULL THEN 1 ELSE 0 END as is_evaluated 
        FROM courses c 
        LEFT JOIN evaluations e ON c.id = e.course_id 
        AND e.student_id = ? 
        ORDER BY c.course_code";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>Student Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

    <h3>Available Courses for Evaluation</h3>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Teacher Name</th>
                <th>Action</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                    <td>
                        <?php if ($row['is_evaluated']): ?>
                            <span style="color: green;">Evaluated</span>
                        <?php else: ?>
                            <a href="evaluation_form.php?course_id=<?php echo $row['id']; ?>">
                                Evaluate Course
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No courses available for evaluation.</p>
    <?php endif; ?>

    <br>
    <a href="../logout.php">Logout</a>
</body>
</html>
