<?php 
$databaseHost = 'localhost';
$databaseName = 'bk_poliklinik';
$databaseUsername = 'root';
$databasePassword = '';
 
$mysqli = mysqli_connect($databaseHost, 
    $databaseUsername, $databasePassword, $databaseName);
 
    //cek koneksi
    // if(!$mysqli){
    //     die("Koneksi gagal: ". mysqli_connect_error());
    // }
    // else{
    //     echo "Koneksi berhasil";
    // }
?>