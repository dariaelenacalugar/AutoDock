<?php
    include 'conexiune.php';
  
    
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $invite_code=$_POST['invite_code'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password != $confirm_password){
        die("Passwords do not match!");
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    if($invite_code != "AUTODOCK2026"){
        die("Invalid invite code!");
    }
    $checkUser = mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE username='$username'
        OR email='$email'"
    );

    if(mysqli_num_rows($checkUser) > 0){
        die("Username or email already exists!");
    }

    $sql = "
    INSERT INTO users
    (
    first_name,
    last_name,
    username,
    email,
    password,
    role
    )
    VALUES
    (
    '$first_name',
    '$last_name',
    '$username',
    '$email',
    '$passwordHash',
    'user'
    )
    ";

    if(mysqli_query($conn,$sql)){
        header("Location: welcome.html");
        exit();
    }
    else{
        echo mysqli_error($conn);
    }
?>