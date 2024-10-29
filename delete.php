<?php
if (isset($_GET['id'])){
    $id = $_GET['id'];
    include('connect.php');
    $sql  = "DELETE FROM chikh_talaba3 WHERE id = $id";
    if(mysqli_query($conn , $sql)){
        session_start();
        // from where did i get this delete 
        $_SESSION["delete"] = "talib deleted successfully"; 
        // Redirect to the second page with the ID
        header("Location: table.php");
    } 
}