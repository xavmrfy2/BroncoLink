<?php
// Hide notices
error_reporting(E_ALL & ~E_NOTICE);

$message = '';

// Check if the form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve the common form fields
    // using htmlspecialchars to prevent XSS (Cross-Site Scripting)
    $postType = htmlspecialchars($_POST['post_type'] ?? '');
    $title = htmlspecialchars($_POST['title'] ?? '');
    $location = htmlspecialchars($_POST['location'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');

    // Ensure required common fields aren't empty
    if (empty($postType) || empty($title) || empty($location) || empty($description)) {
        $message = '<div class="feedback error">Please fill in all required fields (Title, Location, Description).</div>';
    } else {
        try {
            // Connect to the database
            // the path to the DB is just 'broncolink.db'
            $dbPath = __DIR__ . '/../broncolink.db';
            $pdo = new PDO('sqlite:' . $dbPath);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Event Submission
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
            // Study Group Submission
            elseif ($postType === 'study') {
                $meetingTime = htmlspecialchars($_POST['meeting_time'] ?? '');

                if (empty($meetingTime)) {
                    $message = '<div class="feedback error">Please provide a Meeting Time.</div>';
                } else {
                    // form uses title for both but DB uses class_name for study groups
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

// Load the HTML Template
if (!file_exists('views/submit.html')) {
    echo 'Error: views/submit.html was not found.';
    exit;
}

$htmlContent = file_get_contents('views/submit.html');

// Inject the feedback message into the HTML
$injectionPoint = '</p>';
$replacement = '</p>' . $message;
$finalHtml = str_replace($injectionPoint, $replacement, $htmlContent);

echo $finalHtml;
?>
