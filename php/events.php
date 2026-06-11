<?php
// Hide notices to keep the output clean
error_reporting(E_ALL & ~E_NOTICE);

$eventsHtml = '';

try {
    // 1. Connect to the database
    // __DIR__ gets the current folder (/php), and '/../' goes up one level to the root!
    $dbPath = __DIR__ . '/../broncolink.db';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Fetch all events, newest first (ORDER BY id DESC)
    $stmt = $pdo->query("SELECT * FROM events ORDER BY id DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Loop through the results and build the HTML string
    if ($events && count($events) > 0) {
        foreach ($events as $event) {
            // ALWAYS sanitize data coming out of the database to prevent XSS
            $title = htmlspecialchars($event['title'] ?? '');
            $date = htmlspecialchars($event['date'] ?? '');
            $location = htmlspecialchars($event['location'] ?? '');
            $description = htmlspecialchars($event['description'] ?? '');

            // Build the card exactly as Member 1 specified in the HTML comments
            $eventsHtml .= '<article class="listing-card">';
            $eventsHtml .= '  <h2 class="card-title">' . $title . '</h2>';
            $eventsHtml .= '  <ul class="card-details">';
            $eventsHtml .= '    <li><span class="detail-label">Date:</span> ' . $date . '</li>';
            $eventsHtml .= '    <li><span class="detail-label">Location:</span> ' . $location . '</li>';
            $eventsHtml .= '  </ul>';
            $eventsHtml .= '  <p class="card-desc">' . $description . '</p>';
            $eventsHtml .= '</article>';
        }
    } else {
        // Fallback message if the database is empty
        $eventsHtml = '<p class="empty-message">No events have been posted yet. Click "Post an Event" to add one!</p>';
    }

} catch (PDOException $e) {
    $eventsHtml = '<p class="error">Database Error: ' . $e->getMessage() . '</p>';
}

// 4. Load the HTML Template
if (!file_exists('views/events.html')) {
    echo 'Error: views/events.html was not found.';
    exit;
}

$htmlContent = file_get_contents('views/events.html');

// 5. Inject our generated list of cards into the placeholder
$finalHtml = str_replace('{{EVENT_LISTINGS}}', $eventsHtml, $htmlContent);

// 6. Send the final page to the browser
echo $finalHtml;
?>