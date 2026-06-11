<?php
// Hide notices
error_reporting(E_ALL & ~E_NOTICE);

// Define path to the HTML template
// looking for planner.html index/html
$viewPath = 'views/planner.html';

// Check if the HTML file actually exists
if (!file_exists($viewPath)) {
    echo '<div style="padding: 20px; color: red;">';
    echo '<strong>Error:</strong> ' . $viewPath . ' was not found.<br><br>';
    echo '</div>';
    exit;
}

// Read the raw HTML file
$htmlContent = file_get_contents($viewPath);

// Send the HTML to the browser
echo $htmlContent;
?>
