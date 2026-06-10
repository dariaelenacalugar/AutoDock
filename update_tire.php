<?php

include 'conexiune.php';

$id = $_POST['id'];

$query = "UPDATE tires SET
    brand='".$_POST['brand']."',
    size='".$_POST['size']."',
    tire_type='".$_POST['tire_type']."',
    wear_level='".$_POST['wear_level']."',
    condition_status='".$_POST['condition_status']."'
    WHERE id=$id";

mysqli_query($conn, $query);

header("Location: cars-m.php");
exit();

?>