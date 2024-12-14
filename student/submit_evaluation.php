<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login_student.php");
    exit();
}

include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $course_id = $_POST['course_id'];
    $student_id = $_SESSION['username'];
    
    // Validate that all ratings are submitted
    $required_fields = [
        'pedagogy_1', 'pedagogy_2', 'pedagogy_3',
        'management_1', 'management_2', 'management_3',
        'interpersonal_1', 'interpersonal_2', 'interpersonal_3'
    ];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            header("Location: evaluation_form.php?course_id=$course_id&error=Please complete all ratings");
            exit();
        }
    }

    // Check if student has already submitted an evaluation for this course
    $check_sql = "SELECT id FROM evaluations WHERE course_id = ? AND student_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("is", $course_id, $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        header("Location: student_home.php?error=You have already evaluated this course");
        exit();
    }

    // Prepare and execute the insert query
    $sql = "INSERT INTO evaluations (
        course_id, student_id,
        pedagogy_1, pedagogy_2, pedagogy_3,
        management_1, management_2, management_3,
        interpersonal_1, interpersonal_2, interpersonal_3,
        comments
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isiiiiiiiiis", 
        $course_id,
        $student_id,
        $_POST['pedagogy_1'],
        $_POST['pedagogy_2'],
        $_POST['pedagogy_3'],
        $_POST['management_1'],
        $_POST['management_2'],
        $_POST['management_3'],
        $_POST['interpersonal_1'],
        $_POST['interpersonal_2'],
        $_POST['interpersonal_3'],
        $_POST['comments']
    );

    if ($stmt->execute()) {
        header("Location: student_home.php?success=Evaluation submitted successfully");
    } else {
        header("Location: evaluation_form.php?course_id=$course_id&error=Failed to submit evaluation");
    }

    $stmt->close();
} else {
    header("Location: student_home.php");
}

$conn->close();
?>
