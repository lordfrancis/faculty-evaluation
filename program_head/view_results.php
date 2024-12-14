<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../login_program_head.php");
    exit();
}

include '../db.php';
include '../utils/sentiment_helper.php';

$course_id = $_GET['course_id'];

// Fetch course details
$course_sql = "SELECT * FROM courses WHERE id = ?";
$course_stmt = $conn->prepare($course_sql);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

// Calculate averages for each criterion
$sql = "SELECT 
    AVG(pedagogy_1) as avg_pedagogy_1,
    AVG(pedagogy_2) as avg_pedagogy_2,
    AVG(pedagogy_3) as avg_pedagogy_3,
    AVG(management_1) as avg_management_1,
    AVG(management_2) as avg_management_2,
    AVG(management_3) as avg_management_3,
    AVG(interpersonal_1) as avg_interpersonal_1,
    AVG(interpersonal_2) as avg_interpersonal_2,
    AVG(interpersonal_3) as avg_interpersonal_3,
    COUNT(*) as total_evaluations,
    GROUP_CONCAT(comments) as all_comments
FROM evaluations 
WHERE course_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluation Results</title>
    <style>
        .section { margin-bottom: 30px; }
        .score { color: #007bff; font-weight: bold; }
    </style>
     <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <h2>Evaluation Results</h2>
    <h3><?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?></h3>
    <p>Teacher: <?php echo htmlspecialchars($course['teacher_name']); ?></p>
    <p>Total Evaluations: <?php echo $result['total_evaluations']; ?></p>

    <?php if ($result['total_evaluations'] > 0): ?>
        <div class="section">
            <h4>A. Pedagogy</h4>
            <p>1. Is prepared with lessons or lectures: <span class="score"><?php echo number_format($result['avg_pedagogy_1'], 2); ?></span></p>
            <p>2. Is knowledgeable of the subject matter: <span class="score"><?php echo number_format($result['avg_pedagogy_2'], 2); ?></span></p>
            <p>3. Is able to integrate related subjects: <span class="score"><?php echo number_format($result['avg_pedagogy_3'], 2); ?></span></p>
            <?php $pedagogy_avg = ($result['avg_pedagogy_1'] + $result['avg_pedagogy_2'] + $result['avg_pedagogy_3']) / 3; ?>
            <p>Average for Pedagogy: <span class="score"><?php echo number_format($pedagogy_avg, 2); ?></span></p>
        </div>

        <div class="section">
            <h4>B. Class Management</h4>
            <p>1. Utilizes the whole period with classroom activities: <span class="score"><?php echo number_format($result['avg_management_1'], 2); ?></span></p>
            <p>2. Regularly arrives at class on time: <span class="score"><?php echo number_format($result['avg_management_2'], 2); ?></span></p>
            <p>3. Is consistent in attending class: <span class="score"><?php echo number_format($result['avg_management_3'], 2); ?></span></p>
            <?php $management_avg = ($result['avg_management_1'] + $result['avg_management_2'] + $result['avg_management_3']) / 3; ?>
            <p>Average for Class Management: <span class="score"><?php echo number_format($management_avg, 2); ?></span></p>
        </div>

        <div class="section">
            <h4>C. Interpersonal Skills and Character Traits</h4>
            <p>1. Has a pleasing personality: <span class="score"><?php echo number_format($result['avg_interpersonal_1'], 2); ?></span></p>
            <p>2. Has a well-modulated voice: <span class="score"><?php echo number_format($result['avg_interpersonal_2'], 2); ?></span></p>
            <p>3. Is cheerful and has a sense of humor: <span class="score"><?php echo number_format($result['avg_interpersonal_3'], 2); ?></span></p>
            <?php $interpersonal_avg = ($result['avg_interpersonal_1'] + $result['avg_interpersonal_2'] + $result['avg_interpersonal_3']) / 3; ?>
            <p>Average for Interpersonal Skills: <span class="score"><?php echo number_format($interpersonal_avg, 2); ?></span></p>
        </div>

        <?php $overall_avg = ($pedagogy_avg + $management_avg + $interpersonal_avg) / 3; ?>
        <div class="section">
            <h4>Overall Average Score: <span class="score"><?php echo number_format($overall_avg, 2); ?></span></h4>
        </div>

        <!-- Comments Analysis Section -->
        <div class="section">
            <h4>Comments Analysis</h4>
            <?php
            $comments = !empty($result['all_comments']) ? explode(',', $result['all_comments']) : [];
            $total_sentiment = 0;
            $sentiments = ['Positive' => 0, 'Negative' => 0, 'Neutral' => 0];
            $all_key_phrases = [];
            $all_frequent_terms = [];
            $positive_comments = [];
            $neutral_comments = [];
            $negative_comments = [];
            
            foreach($comments as $comment) {
                if(trim($comment)) {
                    $sentiment = analyzeSentiment($comment);
                    if (!empty($sentiment['category'])) {
                        $sentiments[$sentiment['category']]++;
                    }
                    $total_sentiment += isset($sentiment['score']) ? $sentiment['score'] : 0;
                    
                    // Collect key phrases and frequent terms
                    if (!empty($sentiment['key_phrases'])) {
                        $all_key_phrases = array_merge($all_key_phrases, $sentiment['key_phrases']);
                    }
                    if (!empty($sentiment['frequent_terms'])) {
                        $all_frequent_terms = array_merge($all_frequent_terms, $sentiment['frequent_terms']);
                    }
                    
                    // Store segregated comments
                    if (!empty($sentiment['positive_comments'])) {
                        $positive_comments = array_merge($positive_comments, $sentiment['positive_comments']);
                    }
                    if (!empty($sentiment['neutral_comments'])) {
                        $neutral_comments = array_merge($neutral_comments, $sentiment['neutral_comments']);
                    }
                    if (!empty($sentiment['negative_comments'])) {
                        $negative_comments = array_merge($negative_comments, $sentiment['negative_comments']);
                    }
                }
            }
            
            $comment_count = count(array_filter($comments));
            if($comment_count > 0):
                // Determine overall sentiment
                $overall_sentiment = "Mostly Neutral";
                if ($sentiments['Positive'] > $sentiments['Negative'] && $sentiments['Positive'] > $sentiments['Neutral']) {
                    $overall_sentiment = "Mostly Positive";
                } elseif ($sentiments['Negative'] > $sentiments['Positive'] && $sentiments['Negative'] > $sentiments['Neutral']) {
                    $overall_sentiment = "Mostly Negative";
                }

                // Count phrase frequencies
                $phrase_freq = !empty($all_key_phrases) ? array_count_values($all_key_phrases) : [];
                $term_freq = !empty($all_frequent_terms) ? array_count_values($all_frequent_terms) : [];
                if (!empty($phrase_freq)) arsort($phrase_freq);
                if (!empty($term_freq)) arsort($term_freq);
            ?>
                <p>Overall Sentiment: <span class="score"><?php echo $overall_sentiment; ?></span></p>
                
                <?php if (!empty($phrase_freq)): ?>
                <h5>Key Themes in Student Feedback:</h5>
                <ul>
                    <?php foreach(array_slice($phrase_freq, 0, 5) as $phrase => $count): ?>
                        <li><?php echo htmlspecialchars(ucfirst($phrase)); ?> (mentioned <?php echo $count; ?> times)</li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if (!empty($positive_comments)): ?>
                <h5>Positive Comments:</h5>
                <ul>
                    <?php foreach($positive_comments as $comment): ?>
                        <li><?php echo htmlspecialchars($comment); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if (!empty($neutral_comments)): ?>
                <h5>Neutral Comments:</h5>
                <ul>
                    <?php foreach($neutral_comments as $comment): ?>
                        <li><?php echo htmlspecialchars($comment); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if (!empty($negative_comments)): ?>
                <h5>Negative Comments:</h5>
                <ul>
                    <?php foreach($negative_comments as $comment): ?>
                        <li><?php echo htmlspecialchars($comment); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <p>Sentiment Distribution:</p>
                <ul>
                    <li>Positive Comments: <?php echo $sentiments['Positive']; ?></li>
                    <li>Neutral Comments: <?php echo $sentiments['Neutral']; ?></li>
                    <li>Negative Comments: <?php echo $sentiments['Negative']; ?></li>
                </ul>
            <?php else: ?>
                <p>No comments available for analysis.</p>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p>No evaluations submitted yet for this course.</p>
    <?php endif; ?>

    <a href="program_head_home.php">Back to Dashboard</a>
</body>
</html>
