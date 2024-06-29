<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
      // Jika pengguna sudah login, tampilkan tombol "Logout"
      header("Location: /bk-poliklinik/");
      exit;
  }
    include_once("../koneksi.php");

    // Query untuk menghitung jumlah pasien
    $query_pasien = "SELECT COUNT(*) as jumlah_pasien FROM pasien";
    $result_pasien = mysqli_query($mysqli, $query_pasien);
    $row_pasien = mysqli_fetch_assoc($result_pasien);
    $jumlah_pasien = $row_pasien['jumlah_pasien'];

    // Query untuk menghitung jumlah dokter
    $query_dokter = "SELECT COUNT(*) as jumlah_dokter FROM dokter";
    $result_dokter = mysqli_query($mysqli, $query_dokter);
    $row_dokter = mysqli_fetch_assoc($result_dokter);
    $jumlah_dokter = $row_dokter['jumlah_dokter'];

    // Query untuk menghitung jumlah poli
    $query_poli = "SELECT COUNT(*) as jumlah_poli FROM poli";
    $result_poli = mysqli_query($mysqli, $query_poli);
    $row_poli = mysqli_fetch_assoc($result_poli);
    $jumlah_poli = $row_poli['jumlah_poli'];

    // Query untuk menghitung jumlah obat
    $query_obat = "SELECT COUNT(*) as jumlah_obat FROM obat";
    $result_obat = mysqli_query($mysqli, $query_obat);
    $row_obat = mysqli_fetch_assoc($result_obat);
    $jumlah_obat = $row_obat['jumlah_obat'];

?>

<!DOCTYPE html>
<html lang="en">
<?php include('../layout/header.php');?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/bk-poliklinik/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include('../layout/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/bk-poliklinik/" class="brand-link">
      <img src="/bk-poliklinik/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Poliklinik</span>
    </a>

    <!-- Sidebar -->
    <?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
?>
<div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/bk-poliklinik/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
            <?php echo $_SESSION['username']; ?>
          </a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="/bk-poliklinik/admin" class="nav-link active">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Dashboard
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/dokter/" class="nav-link">
                    <i class="nav-icon fas fa-user-md"></i>
                    <p>Dokter
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/pasien/" class="nav-link">
                    <i class="nav-icon fas fa-user-injured"></i>
                    <p>Pasien
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/poli/" class="nav-link">
                    <i class="nav-icon fas fa-hospital"></i>
                    <p>Poli
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../admin/obat" class="nav-link">
                    <i class="nav-icon fas fa-pills"></i>
                    <p>Obat
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
        </ul>
    </nav>

      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../admin">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $jumlah_dokter; ?></h3>
                <p>Dokter</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-md"></i>
              </div>
              <a href="../admin/dokter/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $jumlah_pasien; ?></h3>
                <p>Pasien</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-injured"></i>
              </div>
              <a href="../admin/pasien/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $jumlah_poli; ?></h3>
                <p>Poli</p>
              </div>
              <div class="icon">
                <i class="fas fa-hospital"></i>
              </div>
              <a href="../admin/poli/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $jumlah_obat; ?></h3>
                <p>Jenis Obat</p>
              </div>
              <div class="icon">
                <i class="fas fa-pills"></i>
              </div>
              <a href="../admin/obat/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
      </div> <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include('../layout/footer.php');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
</html>
