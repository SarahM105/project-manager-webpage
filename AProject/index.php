<?php
// Include the database connection
require_once("Aproject_connectdb.php");

// Start or resume the session
session_start();

// Check if the user is not logged in
// Redirect the user to the login page and terminate the script
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

/* Function to retrieve projects based on search criteria
    - use a join to find users projects between tables
    - make a condition where if a title or a date is inputted it searches using that 
    - execute the query and return the fetched projects */

function searchProjects($db, $username, $title = null, $start_date = null) {
    $query = "SELECT p.pid, p.title, p.start_date, p.end_date, p.phase, p.description
              FROM projects p
              JOIN users u ON p.uid = u.uid
              WHERE u.username = ?";
    $params = [$username];
    if (!empty($title)) {
        $query .= " AND p.title LIKE ?";
        $params[] = '%' . $title . '%';
    }

    if (!empty($start_date)) {
        $query .= " AND p.start_date = ?";
        $params[] = $start_date;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Retrieve search criteria from form input
$title = $_GET['title'] ?? null;
$start_date = $_GET['start_date'] ?? null;
$username = $_SESSION["username"];

// Retrieve projects based on search criteria
$projects = searchProjects($db, $username, $title, $start_date);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects List</title>
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
                    <li style="float:right"><a class="active" href="index.php">My Projects</a></li>
                    <li style="float:right"><a href="add_project.php">Add Project</a></li>
                    <li style="float:right"><a href="logout.php">Log Out</a></li>
                </li>
            </ul>
        </nav>
    </section>
    <h2><?= $username ?>'s Projects</h2>

    <!-- Search form -->
    <div class= "project-box">
    <form method="GET">
        <input type="text" name="title" placeholder="Search by title" value="<?php echo htmlspecialchars($title); ?>">
        <input type="date" name="start_date" placeholder="Search by start date" value="<?php echo htmlspecialchars($start_date); ?>">
        <input type="submit" value="Search" >
    </form>

    <!-- Display projects -->
    <ul id="projects-list">
        <?php foreach ($projects as $project) : ?>
            <li>
                <h3 onclick="toggleDetails(<?php echo $project['pid']; ?>)">
                    <?php echo htmlspecialchars($project['title']); 
                   echo "<form method='get' action='update_project.php' style='display: inline;'>";
                   // Add a hidden input field to pass the project ID (pid) to the update page
                   echo "<input type='hidden' name='pid' value='" . $project['pid'] . "'>";
                   // Add a button to submit the form
                   echo "<button type='submit'class='update-button'>Update</button>";
                   echo "</form>";
               
                   echo "</li>";
                  ?>
                    
                </h3>
                <div id="project-<?php echo $project['pid']; ?>" class="project-details">
                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($project['end_date']); ?></p>
                    <p><strong>Phase:</strong> <?php echo htmlspecialchars($project['phase']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($project['description']); ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <em><a href="add_project.php">Add new project</a> or <a href="logout.php">Log Out</a></em>
    </div>      
    <script>
        // JavaScript function to toggle the details of a project
        function toggleDetails(pid) {
            const detailsDiv = document.getElementById(`project-${pid}`);
            detailsDiv.classList.toggle('show');
        }
    </script>
</body>

</html>
