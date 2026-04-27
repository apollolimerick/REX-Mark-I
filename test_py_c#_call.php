<?php

/**
 * 1. The Python Engine Wrapper
 */
function getPythonValuation($property_id) {
    // Sanitize input
    $safe_id = escapeshellarg($property_id);

    // Define paths
    $python_exe = "python"; 
    $script_path = __DIR__ . DIRECTORY_SEPARATOR . "Py_test_call/test_valuation.py";

    // Execute
    $command = "$python_exe \"$script_path\" $safe_id";
    $output = shell_exec($command);

    if ($output === null) {
        return ["error" => "Python script failed to execute."];
    }

    // Decode and return
    $data = json_decode($output, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($data['status']) && $data['status'] === 'success') {
        return $data;
    } else {
        return ["error" => "Error parsing Python output. Raw output: " . htmlspecialchars($output)];
    }
}


/**
 * 2. The C# Engine Wrapper
 */
function getCSharpValuation($property_id) {
    // Sanitize input
    $safe_id = escapeshellarg($property_id);

    // Point directly to the compiled C# executable (no interpreter needed)
    $executable_path = __DIR__ . DIRECTORY_SEPARATOR . "CS_test_call/test_valuation.exe";

    // Execute
    $command = "\"$executable_path\" $safe_id";
    $output = shell_exec($command);

    if ($output === null) {
        return ["error" => "C# executable failed to run."];
    }

    // Decode and return
    $data = json_decode($output, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($data['status']) && $data['status'] === 'success') {
        return $data;
    } else {
        return ["error" => "Error parsing C# output. Raw output: " . htmlspecialchars($output)];
    }
}


// ==========================================
// TEST EXECUTIONS
// ==========================================

$property_id = "REX-LOT-9942";

// --- Test Python ---
echo "<h2>REX Valuation Engine (Python Simulated)</h2>";
$pyResult = getPythonValuation($property_id);

if (isset($pyResult['error'])) {
    echo $pyResult['error'] . "<br>";
} else {
    echo "<strong>Target Property:</strong> " . htmlspecialchars($pyResult['cadastral_id']) . "<br>";
    echo "<strong>Calculated Value:</strong> $" . number_format($pyResult['estimated_value'], 2) . "<br>";
}

// --- Test C# ---
echo "<h2>REX Valuation Engine (C# Simulated)</h2>";
$csResult = getCSharpValuation($property_id);

if (isset($csResult['error'])) {
    echo $csResult['error'] . "<br>";
} else {
    echo "<strong>Target Property:</strong> " . htmlspecialchars($csResult['cadastral_id']) . "<br>";
    echo "<strong>Calculated Value:</strong> $" . number_format($csResult['estimated_value'], 2) . "<br>";
}

?>