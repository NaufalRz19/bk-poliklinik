<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    } 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'pasien') {
        header("Location: /bk-poliklinik/");
        exit;
    }
    include_once("../../koneksi.php");

    // Pastikan $_POST['id'] telah di-set dan merupakan angka yang valid
    if(isset($_GET['id'])){
        $id_pasien = $_SESSION['id'];
        $id_daftar_poli = $_GET['id'];

        $query = "SELECT dp.id, p.nama_poli, d.nama AS nama_dokter, jp.hari, jp.jam_mulai, jp.jam_selesai, dp.no_antrian, dp.status_periksa
                  FROM daftar_poli dp
                  INNER JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                  INNER JOIN dokter d ON jp.id_dokter = d.id
                  INNER JOIN poli p ON d.id_poli = p.id
                  WHERE dp.id_pasien = $id_pasien AND dp.id = $id_daftar_poli";
                  
        $result = mysqli_query($mysqli, $query);
        if (!$result) {
            die('Query Error: ' . mysqli_error($mysqli));
        }
        
        // Pastikan ada hasil dari query sebelum mengambil data
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            die("Data poli tidak ditemukan.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../../layout/header.php');?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('../../layout/navbar.php');?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/bk-poliklinik/dokter/" class="brand-link">
      <img src="/bk-poliklinik/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Poliklinik</span>
    </a>

    <!-- Sidebar -->
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
            <a href="/bk-poliklinik/pasien" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>Dashboard
                <span class="right badge badge-danger">Pasien</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../poli/" class="nav-link active">
              <i class="nav-icon fas fa-hospital"></i>
              <p>Poli
                <span class="right badge badge-danger">Pasien</span>
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
            <h1 class="m-0">Detail Poli</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../pasien">Home</a></li>
              <li class="breadcrumb-item active"><a href="../../pasien/poli">Poli</a></li>
              <li class="breadcrumb-item active">Detail Poli</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h2 class="card-title">Detail poli</h2>
            </div>

            <div class="card-body">
                <?php if(isset($row)) : ?>
                    <div class="row">
                        <div class="col-md-12 border-bottom mb-3">
                            <div class="text-center">
                                <label class="fs-5 font-weight-normal" style="font-size: 1.25rem;">Nama Poli</label>
                                <br>
                                <label class="fs-4 font-weight-normal"><?php echo $row['nama_poli']; ?></label>
                            </div>
                        </div>
                        <div class="col-md-12 border-bottom mb-3">
                            <div class="text-center">
                                <label class="fs-5 font-weight-normal" style="font-size: 1.25rem;">Nama Dokter</label>
                                <br>
                                <label class="fs-4 font-weight-normal"><?php echo $row['nama_dokter']; ?></label>
                            </div>
                        </div>
                        <div class="col-md-12 border-bottom mb-3">
                            <div class="text-center">
                                <label class="fs-5 font-weight-normal" style="font-size: 1.25rem;">Hari</label>
                                <br>
                                <label class="fs-4 font-weight-normal"><?php echo $row['hari']; ?></label>
                            </div>
                        </div>
                        <div class="col-md-12 border-bottom mb-3">
                            <div class="text-center">
                                <label class="fs-5 font-weight-normal" style="font-size: 1.25rem;">Mulai</label>
                                <br>
                                <label class="fs-4 font-weight-normal"><?php echo $row['jam_mulai']; ?></label>
                            </div>
                        </div>
                        <div class="col-md-12 border-bottom mb-3">
                            <div class="text-center">
                                <label class="fs-5 font-weight-normal" style="font-size: 1.25rem;">Selesai</label>
                                <br>
                                <label class="fs-4 font-weight-normal"><?php echo $row['jam_selesai']; ?></label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-center">
                                <label class="fs-5 font-weight-normal" style="font-size: 1.25rem;">Nomor Antrian</label>
                                <br>
                                <label class="btn btn-success"><?php echo $row['no_antrian']; ?></label>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <p>Data tidak ditemukan.</p>
                <?php endif; ?>
            </div>


        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Footer -->
  <?php include('../../layout/footer.php');?>
  <!-- /.footer -->

</div>
<!-- ./wrapper -->
<!-- Bootstrap 4 -->
<script src="/bk-poliklinik/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/bk-poliklinik/dist/js/adminlte.min.js"></script>

</body>
</html>
