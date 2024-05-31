<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $alamat = $_POST['alamat'];
    $ktp = $_POST['ktp'];
    $hp = $_POST['hp'];

    $query_check_pasien = "SELECT id, nama, no_rm FROM pasien WHERE no_ktp = '$ktp'";
    $result_check_pasien = mysqli_query($mysqli, $query_check_pasien);

    if (mysqli_num_rows($result_check_pasien) > 0){
        $row = mysqli_fetch_assoc($result_check_pasien);

        if($row['nama'] != $username){
            echo "<script>alert('Nama pasien tidak sesuai dengan nomor KTP terdaftar');</script>";
            echo "<meta http-equiv='refresh' content='0; url=/bk-poliklinik/auth/register.php'>";
            die();
        }
        // $_SESSION['signup'] = true;
        // $_SESSION['id'] = $row['id'];
        // $_SESSION['username'] = $nama;
        // $_SESSION['no_rm'] = $row['no_rm'];
        // $_SESSION['role'] = 'pasien';

        // echo "<meta http-equiv='refresh' content='0; url=/bk-poliklinik/pasien>";
        // die();
    }
    //ambil no rm
    $queryGetRm = "SELECT MAX(SUBSTRING(no_rm, 8)) as last_queue_number FROM pasien";
    $resultRm = mysqli_query($mysqli, $queryGetRm);

    if(!$resultRm){
        die("Query gagal: ". mysqli_error($mysqli));
    }

    $rowRm = mysqli_fetch_assoc($resultRm);
    $lastQueueNumber = $rowRm['last_queue_number'];
    
    $lastQueueNumber = $lastQueueNumber ? $lastQueueNumber : 0;

    $tahun_bulan = date("Ym");

    $newQueueNumber = $lastQueueNumber + 1;

    $no_rm = $tahun_bulan. "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);

    $query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$username', '$alamat', '$ktp', '$hp', '$no_rm')";

    if(mysqli_query($mysqli, $query)){
        echo "<meta http-equiv='refresh' content='0; url=/bk-poliklinik/auth/login-pasien.php'>";
        die();
    }else{
        echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
    }
    mysqli_close($mysqli);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik | Registration</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/bk-poliklinik/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/bk-poliklinik/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/bk-poliklinik/dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../" class="h1"><b>Poli</b>klinik</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Full name" name="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Alamat" name="alamat">
          <div class="input-group-append">
            <div class="input-group-text">
            <span class="fas fa-map-marked-alt"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="number" class="form-control" placeholder="No KTP" name="ktp">
          <div class="input-group-append">
            <div class="input-group-text">
            <span class="fas fa-id-card"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="number" class="form-control" placeholder="No HP" name="hp">
          <div class="input-group-append">
            <div class="input-group-text">
            <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="login-pasien.php" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="/bk-poliklinik/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/bk-poliklinik/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/bk-poliklinik/dist/js/adminlte.min.js"></script>
</body>
</html>
