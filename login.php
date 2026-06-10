<?php
session_start();
include 'conexiune.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0)
    {
        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password']))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'manager')
            {
                header("Location: dashboard_manager.php");
            }
            else
            {
                header("Location: cars-u.php");
            }

            exit();
        }
    }

    echo "Email sau parola incorecta!";
}
?>