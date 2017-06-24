<?php
SESSION_START();
include "config.php";
if(isset($_POST["username"])){
    $username = addslashes($_POST['username']);
    $password = addslashes($_POST['password']);
	$password = ($password);
    $check=mysqli_query($con, 'select * from admin where username="'.$username.'" AND password="'.$password.'" ');
    if(mysqli_num_rows($check)==0){
        echo 'Username atau Password Salah !';
    }
    else{
        $_SESSION['sidik']['usn'] = $username;
        $_SESSION['sidik']['pwd'] = $password;
        echo 'ok';
    }
}
?>