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
    $query = mysqli_query($mysqli,"SELECT * FROM pasien WHERE nama='$username' AND alamat='$password'");
    if(mysqli_num_rows($query)==1){
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'pasien';
        header('Location: /bk-poliklinik/pasien');
    }else{
        header('Location: /bk-poliklinik/auth/login-pasien.php?error=1');
    }
}

?>