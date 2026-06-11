<?php
// Hide notices
error_reporting(E_ALL & ~E_NOTICE);

$listingsHtml = '';

try {
    // Connect to the database
    $dbPath = __DIR__ . '/../broncolink.db';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all study groups newest first
    $stmt = $pdo->query("SELECT * FROM study_groups ORDER BY id DESC");
    $studyGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through the results and build the HTML string
    if ($studyGroups && count($studyGroups) > 0) {
        foreach ($studyGroups as $group) {
            $className = htmlspecialchars($group['class_name'] ?? '');
            $meetingTime = htmlspecialchars($group['meeting_time'] ?? '');
            $location = htmlspecialchars($group['location'] ?? '');
            $description = htmlspecialchars($group['description'] ?? '');

            // Build the card
            $listingsHtml .= '<article class="listing-card">';
            $listingsHtml .= '  <h2 class="card-title">' . $className . '</h2>';
            $listingsHtml .= '  <ul class="card-details">';
            $listingsHtml .= '    <li><span class="detail-label">Meeting Time:</span> ' . $meetingTime . '</li>';
            $listingsHtml .= '    <li><span class="detail-label">Location:</span> ' . $location . '</li>';
            $listingsHtml .= '  </ul>';
            $listingsHtml .= '  <p class="card-desc">' . $description . '</p>';
            $listingsHtml .= '</article>';
        }
    } else {
        // if the database is empty
        $listingsHtml = '<p class="empty-message">No study groups have been posted yet. Click "Post a Study Group" to start one</p>';
    }

} catch (PDOException $e) {
    $listingsHtml = '<p class="error">Database Error: ' . $e->getMessage() . '</p>';
}

// Load the HTML Template
if (!file_exists('views/study.html')) {
    echo 'Error: views/study.html was not found.';
    exit;
}

$htmlContent = file_get_contents('views/study.html');

$finalHtml = str_replace('{{STUDY_LISTINGS}}', $listingsHtml, $htmlContent);

// Send the final page to the browser
echo $finalHtml;
?>
