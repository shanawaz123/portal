<?php include('constants.php');
    //Get id of student to be deleted from url
    if(isset($_GET['id'])) {
    $id=$_GET['id'];
    
    //sql query
    $sql = "DELETE FROM students WHERE stu_id=:id";

    //prepare sql statement
    $statement = $conn->prepare($sql);

    //bind parameter
    $statement->bindParam(':id', $id);

    //execute the query
    if($statement->execute())
    {
        
        $_SESSION["update-cat"]="<div class='update-msg'>Deleted Successfully</div>";
        header("Location:".SITEURL.'list.php');
    }
    else
    {
        $_SESSION["update-cat"]="<div class='error'>Not Deleted Try Again..</div>";
        header("Location:".SITEURL.'list.php');
    }
}
?>