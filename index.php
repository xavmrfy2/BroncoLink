<?php
// Disable notice errors
error_reporting(E_ALL & ~E_NOTICE);

// Get the requested page from the URL Default to home if empty
$page = $_GET['page'] ?? 'home';

if ($page === 'index') {
    $page = 'home';
}

// Allowed pages for the application
$allowedPages = ['home', 'planner', 'study', 'events', 'submit'];

// Prevent users from requesting files that don't exist
if (!in_array($page, $allowedPages, true)) {
    echo 'Error: requested page was not found.';
    exit;
}

// Construct path to the backend PHP file
$phpScriptPath = 'php/' . $page . '.php';

// Check if the file actually exists on the server then include it
if (file_exists($phpScriptPath)) {
    include($phpScriptPath);
} else {
    echo 'Error: requested php file (' . $phpScriptPath . ') does not exist.';
}
?>
