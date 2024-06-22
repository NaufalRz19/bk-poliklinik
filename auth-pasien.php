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
    // Query untuk memeriksa login dengan parameterized query
    $query = mysqli_prepare($mysqli, "SELECT id, no_rm FROM pasien WHERE nama=? AND alamat=?");
    mysqli_stmt_bind_param($query, "ss", $username, $password);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);

    if(mysqli_num_rows($result) == 1) {
        // Jika data ditemukan
        $row = mysqli_fetch_assoc($result);

        // Simpan data ke dalam session
        $_SESSION['id'] = $row['id'];
        $_SESSION['no_rm'] = $row['no_rm'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'pasien';

        // Redirect ke halaman pasien
        header('Location: /bk-poliklinik/pasien');
        exit;
    } else {
        // Jika data tidak ditemukan, redirect dengan pesan error
        header('Location: /bk-poliklinik/auth/login-pasien.php?error=1');
        exit;
    }
}

?>