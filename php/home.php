<?php
// Hide notices
error_reporting(E_ALL & ~E_NOTICE);

// Define path to the HTML template
$viewPath = 'views/home.html';

// Check if the HTML file actually exists before trying to load it
if (!file_exists($viewPath)) {
    echo 'Error: ' . $viewPath . ' was not found.';
    exit;
}

// Read the raw HTML file
$htmlContent = file_get_contents($viewPath);

// Send the HTML to the browser
echo $htmlContent;
?>
