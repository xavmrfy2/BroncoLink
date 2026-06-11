<?php
// Disable notice errors to keep the output clean, exactly as taught in your labs
error_reporting(E_ALL & ~E_NOTICE);

// 1. Get the requested page from the URL (?page=...). Default to 'home' if empty.
$page = $_GET['page'] ?? 'home';

// If someone explicitly requests 'index', redirect them to 'home'
if ($page === 'index') {
    $page = 'home';
}

// 2. Define the exact list of allowed pages for your BroncoLink project
$allowedPages = ['home', 'planner', 'study', 'events', 'submit'];

// 3. Security Check: Prevent users from requesting files that don't exist
if (!in_array($page, $allowedPages, true)) {
    echo 'Error: requested page was not found.';
    exit;
}

// 4. Construct the path to the backend PHP file.
// Based on the folder structure outlined in Phase 1, your PHP files live in the "php/" folder.
$phpScriptPath = 'php/' . $page . '.php';

// If you decide to keep all files in one single folder (like in Lab 4), 
// you would change the above line to: $phpScriptPath = $page . '.php';

// 5. Check if the file actually exists on the server, then include it
if (file_exists($phpScriptPath)) {
    include($phpScriptPath);
} else {
    echo 'Error: requested php file (' . $phpScriptPath . ') does not exist.';
}
?>