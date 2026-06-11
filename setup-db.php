<?php
error_reporting(E_ALL & ~E_NOTICE);

$databaseFile = 'broncolink.db';

try {
    // Create PDO object and connect to SQLite
    $pdo = new PDO('sqlite:' . $databaseFile);
    
    // For dewbugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the study_groups table
    $createStudyTable = "CREATE TABLE IF NOT EXISTS study_groups (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        class_name TEXT NOT NULL,
        meeting_time TEXT NOT NULL,
        location TEXT NOT NULL,
        description TEXT NOT NULL
    )";
    $pdo->exec($createStudyTable);
    echo "Table 'study_groups' created successfully<br>";

    // Create events table
    $createEventsTable = "CREATE TABLE IF NOT EXISTS events (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        date TEXT NOT NULL,
        location TEXT NOT NULL,
        description TEXT NOT NULL
    )";
    $pdo->exec($createEventsTable);
    echo "Table 'events' created successfully<br><br>";

    $studyGroups = [
        ['class_name' => 'CSEN 161', 'meeting_time' => 'Tuesdays 5:00 PM', 'location' => 'Library 2nd Floor', 'description' => 'Working on the final project and reviewing PHP concepts.'],
        ['class_name' => 'MATH 11', 'meeting_time' => 'Wednesdays 3:30 PM', 'location' => 'Benson Center', 'description' => 'Calc 1 homework review and quiz prep.'],
        ['class_name' => 'CTW 1', 'meeting_time' => 'Mondays 7:00 PM', 'location' => 'Graham Commons', 'description' => 'Peer reviewing essays before submission.']
    ];

    // PDO for inserting study groups
    $stmtStudy = $pdo->prepare("INSERT INTO study_groups (class_name, meeting_time, location, description) VALUES (:class_name, :meeting_time, :location, :description)");
    foreach ($studyGroups as $group) {
        $stmtStudy->execute($group);
    }
    echo "Test study groups inserted successfully<br>";

    // Prepare Test Data for Events
    $events = [
        ['title' => 'Welcome Week BBQ', 'date' => 'Sept 20, 2026', 'location' => 'Locatelli Plaza', 'description' => 'Free food and games for all incoming first-year students!'],
        ['title' => 'Engineering Club Fair', 'date' => 'Sept 22, 2026', 'location' => 'Heafey Lawn', 'description' => 'Meet the engineering clubs, including ACM, IEEE, and Theta Tau.'],
        ['title' => 'Campus Tour for New Students', 'date' => 'Sept 18, 2026', 'location' => 'Admissions Building', 'description' => 'Guided tour to find all your classrooms before the first day.']
    ];

    // Insert Events using prepared Statements
    $stmtEvents = $pdo->prepare("INSERT INTO events (title, date, location, description) VALUES (:title, :date, :location, :description)");
    foreach ($events as $event) {
        $stmtEvents->execute($event);
    }
    echo "Test events inserted successfully<br><br>";

    // Test Query if data is actuall there
    echo "<b>Testing Database - Fetching Events:</b><br>";
    $testQuery = $pdo->query("SELECT * FROM events");
    $result = $testQuery->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($result);
    echo "</pre>";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>
