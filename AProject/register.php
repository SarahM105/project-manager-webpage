<?php

//to check if the form has been submitted 
if (isset($_POST["submitted"])) {
    
    // connects to the database
    require_once("Aproject_connectdb.php");

    $username = isset($_POST["username"]) ? $_POST["username"] : false;
    $email = isset($_POST["email"]) ? $_POST["email"] : false;

    /* isset($_POST["username"]) - this part checks if the username field was submitted through a post request
        ? $_POST["username"] : false - the ternary operator '?:', this is short hand for an if/else operator
        
        this means that if the condition isset($_POST["username"]) is true (meaning the "username" field was submitted), then $username will be assigned the value of $_POST["username"].
        If the condition is false (meaning the "username" field was not submitted), then $username will be assigned the value false.*/
    
    $password = isset($_POST["password"]) ? password_hash( $_POST["password"],PASSWORD_DEFAULT) : false;

    /* password_hash($_POST["password"], PASSWORD_DEFAULT) - This function takes the submitted password ($_POST["password"]) and generates a cryptographically secure hash representation of it. 
    The constant PASSWORD_DEFAULT indicates that PHP should use the currently recommended algorithm (as of PHP version) for hashing passwords. */


    /* if the username, passord or email is empty, then the program should not allow the user to sign up  */
    if (!$username || !$password || !$email) {
        echo "User has not provided the necessary details, check that all the provided inputs have been filled out";
        exit;
    }
    try{
    $stat = $db->prepare("insert into users values(default,?,?,?)");
    $stat->execute(array($username, $password,$email));

    /* $db->prepare("insert into user values(default,?,?) - this line prepares an SQLstatement for execution
        the two placeholders, represented by a question mark(?) are replaces by actual values when the line is executed
        $stat->execute(array($username, $password - executes the statement; replaces the placeholders with the actual values */

    $id = $db->lastInsertId();
    echo "you are now registered, your id is $id";
    }catch(PDOException $e){
        echo $e->getMessage();
    }

}


?>

<!DOCTYPE html>
<html lang = 'en'>
    <head>
        <meta charset="utf-8" />
        <title>Registration Form</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Allison&family=Archivo+Black&family=Monsieur+La+Doulaise&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Stylesheet.css">
    </head>
    <body>
        <header>
            <h1> <b>APROJECTS</b></h1>
            <h5>  project manager webpage </h5>
        </header>
        <section>
            <nav>
                <ul>
                    <li>
                        <li style="float:right"><a class="active" href="register.php">Register</a></li>
                        <li style="float:right"><a href="login.php">Login</a></li>
                    </li>
                </ul>
            </nav>
        </section>
        <br/>
        <div class = auth-box>
        <form action = 'register.php' method="post"> <!-- The form and action will be in the same file as stated by this line-->
            <p> Email <input type = "email" name = "email"/></p>
            <p> Username <input type = "text" name = "username"/></p>
            <p> Password <input type = "text" name = "password"/></p>

            <input type="submit" value="Register" class = "auth-button">

            <!-- Because the form and action are placed in the same file, there needs to be a hidden field which serves the purpose of signaling to the PHP script whether the form has been submitted or not, enabling appropriate handling of the form data within the register.php file.-->
            <input type="hidden" name = "submitted" value="true">


            <em> or <a href="login.php">Login</a></em>
        </form>
        </div>
    </body>
</html>