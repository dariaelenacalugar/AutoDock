<?php

include 'conexiune.php';

$id = $_POST['id'];

$query = "UPDATE drivers SET
    first_name='".$_POST['first_name']."',
    last_name='".$_POST['last_name']."',
    phone='".$_POST['phone']."',
    email='".$_POST['email']."',
    license_category='".$_POST['license_category']."'
    WHERE id=$id";

mysqli_query($conn, $query);

header("Location: drivers.php");
exit();

?>