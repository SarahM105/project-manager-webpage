<?php

//variables 
$host = 'https://cs2410-web01pvm.aston.ac.uk/phpmyadmin';
$db_name = ' u_230083703_aproject';
$db_username = 'u-230083703';
$password = '6iR55BDjEjJJ0Y3';

//try to create a new connection to the 'aproject' data base
try{
    $db = new PDO("mysql:host=$host;dbname=$db_name", $db_username, $password);
    //echo("connected");
    //users will be able to be notfied that they are connected to the database server i.e they will be able to use the website
}catch(PDOException $e){
    echo("failed" . $e->getMessage());
    //if the connection fails somehow, user will be able to get an error message without the whole program failing on them
}


?>