<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login_program_head.php");
    exit();
}

include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_code = trim($_POST['course_code']);
    $course_name = trim($_POST['course_name']);
    $teacher_name = trim($_POST['teacher_name']);
    $created_by = $_SESSION['username'];
    
    // Check if course code already exists
    $check_sql = "SELECT course_code FROM courses WHERE course_code = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $course_code);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        header("Location: program_head_home.php?error=Course code already exists");
        exit();
    }
    
    // Insert new course
    $sql = "INSERT INTO courses (course_code, course_name, teacher_name, created_by) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $course_code, $course_name, $teacher_name, $created_by);
    
    if ($stmt->execute()) {
        header("Location: program_head_home.php?success=Course added successfully");
    } else {
        header("Location: program_head_home.php?error=Failed to add course");
    }
    
    $stmt->close();
} else {
    header("Location: program_head_home.php");
}

$conn->close();
?>
