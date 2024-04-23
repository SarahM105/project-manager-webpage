<?php
// Include necessary files and initialize the session
require_once("Aproject_connectdb.php");
session_start();

// Check if the form has been submitted and make sure that the form is not empty 
if (isset($_POST["submitted"])) {
    if (empty($_POST["username"]) || empty($_POST["password"])) {
        exit("Please fill in both username and password.");
    }
    
    // Sanitise input
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    try {
        // Prepare the statement to fetch the hashed password from the database and compare the users password to the hashed password in the data base
        // if password is the same, redirect to index.php (the home page for logged in users) 
        // if not produce an error message for the user to read, do not exit and allow the user to do the form again if so
        //only exit if we are not able to carry out the process of verifying the user 
        $stat = $db->prepare('SELECT password FROM users WHERE username = ?');
        $stat->execute([$username]);

        if ($stat->rowCount() > 0) {
            $row = $stat->fetch();
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                header('Location: index.php');
                exit;
            } else {
                echo "Error logging in: Incorrect password.";
            }
        } else {
            echo "Error logging in: Incorrect usernam e";
        }
    } catch (PDOException $e) {
        echo "An error occurred. Please try again later.";
        error_log($e->getMessage());
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
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
                    <li style="float:right"><a href="register.php">Register</a></li>
                    <li style="float:right"><a class="active" href="login.php">Login</a></li>
                </li>
            </ul>
        </nav>
    </section>
    <br/>
    <div class = "auth-box">
    <form method="post" action="login.php">
        <p>Username: <input type="text" name="username" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <input type="submit" value="Login">
        <input type="hidden" name="submitted" value="true">
        <em> or <a href="register.php">Sign Up</a></em>
    </form>
    </div>
</body>
</html>
