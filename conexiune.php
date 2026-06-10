<?php
    $host="localhost";
    $utilizator="root";
    $parola="";
    $baza_date="parc_auto";
    $conn=mysqli_connect($host,$utilizator,$parola,$baza_date);

    if(!$conn){
        die("Eroare conexiune: " . mysqli_connect_error());
    }
?>