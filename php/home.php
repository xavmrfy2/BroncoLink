<?php
// Hide notices to keep the output clean
error_reporting(E_ALL & ~E_NOTICE);

// Define the path to the HTML template
$viewPath = 'views/home.html';

// 1. Safety check: Ensure the HTML file actually exists before trying to load it
if (!file_exists($viewPath)) {
    echo 'Error: ' . $viewPath . ' was not found.';
    exit;
}

// 2. Read the raw HTML file
$htmlContent = file_get_contents($viewPath);

// 3. Send the HTML to the browser
echo $htmlContent;
?>