<?php
// Hide notices to keep the output clean
error_reporting(E_ALL & ~E_NOTICE);

$listingsHtml = '';

try {
    // 1. Connect to the database (Path is just 'broncolink.db' because this runs from index.php)
    // __DIR__ gets the current folder (/php), and '/../' goes up one level to the root!
    $dbPath = __DIR__ . '/../broncolink.db';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Fetch all study groups, newest first (ORDER BY id DESC)
    $stmt = $pdo->query("SELECT * FROM study_groups ORDER BY id DESC");
    $studyGroups = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Loop through the results and build the HTML string
    if ($studyGroups && count($studyGroups) > 0) {
        foreach ($studyGroups as $group) {
            // ALWAYS sanitize data coming out of the database to prevent XSS
            $className = htmlspecialchars($group['class_name'] ?? '');
            $meetingTime = htmlspecialchars($group['meeting_time'] ?? '');
            $location = htmlspecialchars($group['location'] ?? '');
            $description = htmlspecialchars($group['description'] ?? '');

            // Build the card exactly as Member 1 specified in the HTML comments
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
        // Fallback message if the database is empty
        $listingsHtml = '<p class="empty-message">No study groups have been posted yet. Click "Post a Study Group" to start one!</p>';
    }

} catch (PDOException $e) {
    $listingsHtml = '<p class="error">Database Error: ' . $e->getMessage() . '</p>';
}

// 4. Load the HTML Template
if (!file_exists('views/study.html')) {
    echo 'Error: views/study.html was not found.';
    exit;
}

$htmlContent = file_get_contents('views/study.html');

// 5. Inject our generated list of cards into the placeholder
$finalHtml = str_replace('{{STUDY_LISTINGS}}', $listingsHtml, $htmlContent);

// 6. Send the final page to the browser
echo $finalHtml;
?>