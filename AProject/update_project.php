<?php
// Include the database connection
require_once("Aproject_connectdb.php");

// Start or resume the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect the user to the login page and terminate the script
    header("Location: login.php");
    exit();
}

// Get the project ID (pid) from the URL
$pid = isset($_GET['pid']) ? intval($_GET['pid']) : null;

// Check if a valid project ID is provided
if ($pid === null) {
    echo "Invalid project ID.";
    exit();
}

// Fetch the project details from the database
$query = $db->prepare("SELECT * FROM projects WHERE pid = ?");
$query->execute([$pid]);
$project = $query->fetch(PDO::FETCH_ASSOC);

// Check if the project exists
if (!$project) {
    echo "Project not found.";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve updated values from the form
    $title = isset($_POST['title']) ? $_POST['title'] : null;
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
    $phase = isset($_POST['phase']) ? $_POST['phase'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;

    // Update the project in the database
    $query = "UPDATE projects SET title = ?, start_date = ?, end_date = ?, phase = ?, description = ? WHERE pid = ?";
    $stmt = $db->prepare($query);
    $update_successful = $stmt->execute([$title, $start_date, $end_date, $phase, $description, $pid]);

    // Provide feedback to the user
    if ($update_successful) {
        echo "Project updated successfully.";
    } else {
        echo "Failed to update the project.";
    }
}

// Display the form with the existing project values pre-filled
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Project</title>
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
                    <li style="float:right"><a href="add_project.php">Add Project</a></li>
                    <li style="float:right"><a href="logout.php">Log Out</a></li>
                </li>
            </ul>
        </nav>
    </section>
    <h2>Update Project</h2>
    <div class = "update_box">
    <!-- Update form -->
    <form method="POST">

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>">

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($project['start_date']); ?>">

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($project['end_date']); ?>">

        <label for="phase">Phase:</label>
        <select id="phase" name="phase">
            <option value="design" <?php if ($project['phase'] === 'design') echo 'selected'; ?>>Design</option>
            <option value="development" <?php if ($project['phase'] === 'development') echo 'selected'; ?>>Development</option>
            <option value="testing" <?php if ($project['phase'] === 'testing') echo 'selected'; ?>>Testing</option>
            <option value="deployment" <?php if ($project['phase'] === 'deployment') echo 'selected'; ?>>Deployment</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description"><?php echo htmlspecialchars($project['description']); ?></textarea>

        <input type="submit" value="Update Project">
    </form>

    <em><a href="index.php">Back to Projects List</a></em>
    </div>
</body>

</html>
