<?php
// Hide notices to keep the output clean
error_reporting(E_ALL & ~E_NOTICE);

// Define the path to the HTML template
// Note: We are looking for 'planner.html', which is Member 2's renamed 'index.html'
$viewPath = 'views/planner.html';

// 1. Safety check: Ensure the HTML file actually exists
if (!file_exists($viewPath)) {
    echo '<div style="padding: 20px; color: red;">';
    echo '<strong>Error:</strong> ' . $viewPath . ' was not found.<br><br>';
    echo '<em>Make sure you took <code>index.html</code> from the dormLayout folder, renamed it to <code>planner.html</code>, and moved it into the <code>views/</code> folder!</em>';
    echo '</div>';
    exit;
}

// 2. Read the raw HTML file
$htmlContent = file_get_contents($viewPath);

// 3. Send the HTML to the browser
echo $htmlContent;
?>