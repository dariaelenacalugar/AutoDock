<?php
ession_start();
include'conexiune.php';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=$_POST['email'];
    $password=MD5($_POST['password']);

    $query="SELECT*FROM users WHERE email='$email' AND password='$password'";
    $result=myspli_query($conn,$query);
    $user=mysqli_fetch_assoc($result);

    if($user){
        $_SESSION['user_id']=$user['id'];
        $_SESSION['username']=$user['username'];
        $_SESSION['role']=$user['role'];
        header('Location: .php');
        exit();
    }else{
        $error="Invalid email or password!";
    }
}
?>
<?php
echo "Serverul functioneaza!";
?>