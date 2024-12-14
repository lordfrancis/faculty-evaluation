<?php
function analyzeSentiment($text) {
    if (empty(trim($text))) {
        return defaultResponse();
    }

    // Get Python path - try multiple possible locations
    $python_paths = array(
        'C:/Python311/python.exe',  // Adjust this to your Python installation path
        'C:/Python39/python.exe',
        'python',
        'py'
    );

    $python_executable = null;
    foreach ($python_paths as $path) {
        $test_command = sprintf('%s -V 2>&1', $path);
        $output = shell_exec($test_command);
        if ($output !== null && strpos($output, 'Python') !== false) {
            $python_executable = $path;
            break;
        }
    }

    if ($python_executable === null) {
        error_log("Python executable not found");
        return defaultResponse();
    }

    $python_script = __DIR__ . '/sentiment_analysis.py';
    $escaped_text = escapeshellarg($text);
    
    // Add debug logging
    $log_file = __DIR__ . '/sentiment.log';
    $command = sprintf('"%s" "%s" %s 2>> "%s"', 
        $python_executable, 
        $python_script, 
        $escaped_text,
        $log_file
    );
    
    // Log the command being executed
    error_log("Executing command: " . $command);
    
    $output = shell_exec($command);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " Command: " . $command . "\n", FILE_APPEND);
    file_put_contents($log_file, date('Y-m-d H:i:s') . " Output: " . $output . "\n", FILE_APPEND);
    
    if ($output === null || empty($output)) {
        error_log("Sentiment analysis failed for text: " . substr($text, 0, 100));
        return defaultResponse();
    }
    
    $result = json_decode($output, true);
    
    if ($result === null) {
        error_log("JSON decode failed for output: " . $output);
        return defaultResponse();
    }
    
    return $result;
}

function defaultResponse() {
    return [
        'score' => 0,
        'category' => 'Neutral',
        'frequent_terms' => [],
        'key_phrases' => [],
        'summary' => ''
    ];
}
?>
