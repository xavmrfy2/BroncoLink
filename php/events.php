<?php
// Hide notices
error_reporting(E_ALL & ~E_NOTICE);

$eventsHtml = '';

try {
    // Connect to the database
    // __DIR__ gets the current folder /php. /../ goes up one level to root
    $dbPath = __DIR__ . '/../broncolink.db';
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all events with newest first
    $stmt = $pdo->query("SELECT * FROM events ORDER BY id DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Loop through the results and build the HTML string
    if ($events && count($events) > 0) {
        foreach ($events as $event) {
            // Didn't really need this but why not. Prevents XSS security with scripting
            $title = htmlspecialchars($event['title'] ?? '');
            $date = htmlspecialchars($event['date'] ?? '');
            $location = htmlspecialchars($event['location'] ?? '');
            $description = htmlspecialchars($event['description'] ?? '');

            // Build the card with HTML
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
        // if the database is empty
        $eventsHtml = '<p class="empty-message">No events have been posted yet. Click "Post an Event" to add one</p>';
    }

} catch (PDOException $e) {
    $eventsHtml = '<p class="error">Database Error: ' . $e->getMessage() . '</p>';
}

// Load the HTML Template
if (!file_exists('views/events.html')) {
    echo 'Error: views/events.html was not found.';
    exit;
}

$htmlContent = file_get_contents('views/events.html');

// Inject our generated list of cards into the placeholder
$finalHtml = str_replace('{{EVENT_LISTINGS}}', $eventsHtml, $htmlContent);

// Send the final page to the browser
echo $finalHtml;
?>
