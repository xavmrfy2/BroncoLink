<?php
// Hide notices to keep the output clean
error_reporting(E_ALL & ~E_NOTICE);

$message = ''; // We will use this to show success/error messages to the user

// 1. Check if the form was actually submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 2. Sanitize and retrieve the common form fields
    // using htmlspecialchars to prevent XSS (Cross-Site Scripting)
    $postType = htmlspecialchars($_POST['post_type'] ?? '');
    $title = htmlspecialchars($_POST['title'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');

    // 3. Basic validation: Ensure required common fields aren't empty
    if (empty($postType) || empty($title) || empty($location) || empty($description)) {
        $message = '<div class="feedback error">Please fill in all required fields (Title, Location, Description).</div>';
    } else {
        try {
            // 4. Connect to the database
            // Note: Since index.php is including this file from the root directory, 
            // the path to the DB is just 'broncolink.db'
            // __DIR__ gets the current folder (/php), and '/../' goes up one level to the root!
            $dbPath = __DIR__ . '/../broncolink.db';
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 5. Handle Event Submission
            if ($postType === 'event') {
                $date = htmlspecialchars($_POST['date'] ?? '');

                if (empty($date)) {
                    $message = '<div class="feedback error">Please provide an Event Date.</div>';
                } else {
                    $stmt = $pdo->prepare("INSERT INTO events (title, date, location, description) VALUES (:title, :date, :location, :description)");
                    $stmt->execute([
                        ':title' => $title,
                        ':date' => $date,
                        ':location' => $location,
                        ':description' => $description
                    ]);
                    $message = '<div class="feedback success">Event posted successfully!</div>';
                }
            }
            // 6. Handle Study Group Submission
            elseif ($postType === 'study') {
                $meetingTime = htmlspecialchars($_POST['meeting_time'] ?? '');

                if (empty($meetingTime)) {
                    $message = '<div class="feedback error">Please provide a Meeting Time.</div>';
                } else {
                    // Note: The form uses 'title' for both, but our DB uses 'class_name' for study groups
                    $stmt = $pdo->prepare("INSERT INTO study_groups (class_name, meeting_time, location, description) VALUES (:class_name, :meeting_time, :location, :description)");
                    $stmt->execute([
                        ':class_name' => $title,
                        ':meeting_time' => $meetingTime,
                        ':location' => $location,
                        ':description' => $description
                    ]);
                    $message = '<div class="feedback success">Study Group posted successfully!</div>';
                }
            } else {
                $message = '<div class="feedback error">Invalid post type selected.</div>';
            }

        } catch (PDOException $e) {
            $message = '<div class="feedback error">Database Error: ' . $e->getMessage() . '</div>';
        }
    }
}

// 7. Load the HTML Template
if (!file_exists('views/submit.html')) {
    echo 'Error: views/submit.html was not found.';
    exit;
}

$htmlContent = file_get_contents('views/submit.html');

// 8. Inject the feedback message into the HTML
// We look for a placeholder or inject it just below the page description
$injectionPoint = '</p>';
$replacement = '</p>' . $message;
$finalHtml = str_replace($injectionPoint, $replacement, $htmlContent);

echo $finalHtml;
?>