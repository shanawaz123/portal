<?php

//session start
session_start();
define("SITEURL", 'http://localhost/portal/');


$host="localhost";
$dbname="portal";
$username="root";
$password=""; 

$conn=new PDO("mysql:host=$host; dbname=$dbname", $username, $password);


?>