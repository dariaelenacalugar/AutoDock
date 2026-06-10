<?php 
    include 'conexiune.php';
    $id=$_GET['id'];
    $query="DELETE FROM cars WHERE id=$id";
    mysqli_query($conn,$query);
    header("Location: cars-m.php");
    exit();
?>