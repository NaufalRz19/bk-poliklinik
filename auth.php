<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include ('koneksi.php');
$username = $_POST['username'];
$password = $_POST['password'];

if($username == 'admin'){
    if($password == 'admin'){
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header('Location: /bk-poliklinik/admin');
    }else{
        header('Location: /bk-poliklinik/auth/login-pasien.php?error=1');
    }
}else{
    $query = mysqli_query($mysqli,"SELECT * FROM dokter WHERE nama='$username' AND alamat='$password'");
    if(mysqli_num_rows($query)==1){
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'dokter';
        header('Location: /bk-poliklinik/dokter');
    }else{
        header('Location: /bk-poliklinik/auth/login.php?error=1');
    }
}

?>