<?php
// Include necessary files
require_once("Aproject_connectdb.php");

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input and sanitize it
    $title = htmlspecialchars($_POST["title"]);
    $start_date = htmlspecialchars($_POST["start_date"]);
    $end_date = htmlspecialchars($_POST["end_date"]);
    $phase = htmlspecialchars($_POST["phase"]);
    $description = htmlspecialchars($_POST["description"]);

    // Check if the username is in the session
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        
        // Fetch user ID (uid) from username
        $query = 'SELECT uid FROM users WHERE username = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$username]);

        // Check if user is found
        if ($row = $stmt->fetch()) {
            $uid = $row['uid'];

            // Insert project into the database
            $insert_query = 'INSERT INTO projects ( title, start_date, end_date, phase, description, uid) VALUES (?, ?, ?, ?, ?, ?)';
            $insert_stmt = $db->prepare($insert_query);
            
            // Execute the query and check the result - make sure to report whether or nor the project was added to the database 
            if ($insert_stmt->execute([$title, $start_date, $end_date, $phase, $description, $uid])) {
                echo 'Project added successfully!';
                header("Location: index.php");
                exit();
            } else {
                // Log the error message
                $errorInfo = $insert_stmt->errorInfo();
                echo "Error: Failed to add the project. Error message: " . $errorInfo[2];
            }
        } else {
            echo "Error: User not found.";
        }
    } else {
        echo "Error: Username not found in session.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Project</title>
    <link rel="stylesheet" href="Stylesheet.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Allison&family=Archivo+Black&family=Monsieur+La+Doulaise&display=swap" rel="stylesheet">

</head>
<body>
    <header>
        <h1> <b>APROJECTS</b></h1>
        <h5>  project manager webpage </h5>
    </header>
<section>
        <!-- navigation and header should look the same in nearly every file  -->
        <nav>
            <ul>
                <li>
                    <li style="float:right"><a href="index.php">My Projects</a></li>
                    <li style="float:right"><a class="active" href="add_project.php">Add Project</a></li>
                    <li style="float:right"><a href="logout.php">Log Out</a></li>
                </li>
            </ul>
        </nav>
    </section>
    <h2>Add New Project</h2>
    <div class="container">
        <!-- form which should take in user input-->
        <form method="post" action="add_project.php">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date">
            </div>
            <div class="form-group">
                <label for="phase">Phase:</label>
                <select id="phase" name="phase">
                    <option value="design">Design</option>
                    <option value="development">Development</option>
                    <option value="testing">Testing</option>
                    <option value="deployment">Deployment</option>
                    <option value="complete">Complete</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <input type="submit" value="Add Project">
        </form>
    </div>
</body>
</html>
