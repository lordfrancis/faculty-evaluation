<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login_student.php");
    exit();
}

include '../db.php';

// Fetch course details
$course_id = $_GET['course_id'];

// Check if already evaluated
$check_sql = "SELECT id FROM evaluations WHERE course_id = ? AND student_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("is", $course_id, $_SESSION['username']);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    header("Location: student_home.php?error=You have already evaluated this course");
    exit();
}

$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header("Location: student_home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Evaluation Form</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .evaluation-section { margin-bottom: 30px; }
        .rating-scale { display: flex; justify-content: space-between; width: 250px; }
        .question { margin-bottom: 15px; }
    </style>
    
</head>
<body>
    <h2>Course Evaluation Form</h2>
    <p>Course: <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?></p>
    <p>Teacher: <?php echo htmlspecialchars($course['teacher_name']); ?></p>

    <form action="submit_evaluation.php" method="post">
        <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">

        <div class="rating-legend">
            <p>Rating Scale:</p>
            <ul>
                <li>1 - Unsatisfactory</li>
                <li>2 - Needs Improvement</li>
                <li>3 - Meets Expectation</li>
                <li>4 - Highly Effective</li>
                <li>5 - Exceeds Expectations</li>
            </ul>
        </div>

        <!-- A. Pedagogy -->
        <div class="evaluation-section">
            <h3>A. Pedagogy</h3>
            <div class="question">
                <p>1. The teacher is prepared with his lessons or lectures.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="pedagogy_1" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="question">
                <p>2. The teacher is knowledgeable of the subject matter.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="pedagogy_2" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="question">
                <p>3. The teacher is able to integrate related subjects.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="pedagogy_3" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- B. Class Management -->
        <div class="evaluation-section">
            <h3>B. Class Management</h3>
            <div class="question">
                <p>1. The teacher utilizes the whole period with classroom activities.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="management_1" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="question">
                <p>2. The teacher regularly arrives at class on time.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="management_2" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="question">
                <p>3. The teacher is consistent in attending his/her class.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="management_3" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- C. Interpersonal Skills -->
        <div class="evaluation-section">
            <h3>C. Interpersonal Skills and Character Traits</h3>
            <div class="question">
                <p>1. The teacher has a pleasing personality.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="interpersonal_1" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="question">
                <p>2. The teacher has a well-modulated voice.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="interpersonal_2" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="question">
                <p>3. The teacher is cheerful and has a sense of humor.</p>
                <div class="rating-scale">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label>
                            <input type="radio" name="interpersonal_3" value="<?php echo $i; ?>" required>
                            <?php echo $i; ?>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="evaluation-section">
            <h3>Comments and Suggestions</h3>
            <textarea name="comments" rows="5" cols="50" placeholder="Please enter your comments and suggestions here..."></textarea>
        </div>

        <button type="submit">Submit Evaluation</button>
    </form>

    <a href="student_home.php">Back to Dashboard</a>
</body>
</html>
