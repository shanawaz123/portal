<?php
//Authorization Access control
//check whether user is logged in or not 
if(!isset($_SESSION['username']))    //if user session is not set
{
    //if user not logged in redirect to login page with message 
    header('location: index.php');
}
?>