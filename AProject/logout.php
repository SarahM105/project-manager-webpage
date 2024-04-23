<?php
    session_start();
    unset($_SESSION["username"]);
    session_destroy();

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="Stylesheet.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Allison&family=Archivo+Black&family=Monsieur+La+Doulaise&display=swap" rel="stylesheet">
</head>
    <title>Logout</title>
</head>
<body>
    <div class="logout">
        <h4><strong>You are now logged out</strong></h4>
        <em><a href="login.php" >Login</a></em>
    </div>
</body>
</html>
