<?php
// 1. The data you want to send to the Python algorithm
$property_id = "REX-LOT-9942";

// 2. CRITICAL: Sanitize the input so it's safe for the Windows command line
$safe_id = escapeshellarg($property_id);

// 3. Define your paths
// ⚠️ XAMPP GOTCHA: If this script returns NULL, change "python" to your absolute path.
// Example: $python_exe = "C:\\Users\\YourName\\AppData\\Local\\Programs\\Python\\Python310\\python.exe";
$python_exe = "python"; 

// __DIR__ automatically gets the current folder path (C:\xampp\htdocs\REX)
$script_path = __DIR__ . DIRECTORY_SEPARATOR . "test_valuation.py";

// 4. Build and execute the command
$command = "$python_exe \"$script_path\" $safe_id";
$output = shell_exec($command);

// 5. Check if it worked
if ($output === null) {
    die("Error: Python script failed to execute. You likely need to put the full absolute path to python.exe in the \$python_exe variable.");
}

// 6. Decode the JSON string back into a usable PHP array
$data = json_decode($output, true);

// 7. Display the results!
if (json_last_error() === JSON_ERROR_NONE && $data['status'] === 'success') {
    echo "<h2>REX Valuation Engine Engine (Simulated)</h2>";
    echo "<strong>Target Property:</strong> " . htmlspecialchars($data['cadastral_id']) . "<br>";
    echo "<strong>Calculated Value:</strong> $" . number_format($data['estimated_value'], 2) . "<br>";
} else {
    echo "Error parsing Python output. Raw output was: <br>" . htmlspecialchars($output);
}
?>