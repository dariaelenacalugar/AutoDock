<?php

include 'conexiune.php';

$id = $_GET['id'];

$query = "DELETE FROM drivers WHERE id=$id";

mysqli_query($conn,$query);

header("Location: drivers.php");
exit();

?>