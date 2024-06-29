<?php
    // Memastikan session dimulai
    if(!isset($_SESSION)) {
        session_start(); 
    }

    // Memeriksa apakah pengguna sudah login sebagai dokter
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'dokter') {
        header("Location: /bk-poliklinik/");
        exit;
    }

    // Menghubungkan ke database
    include_once("../../koneksi.php");

    // Query untuk mengambil riwayat pasien yang ditangani oleh dokter yang sedang login
    $query = "SELECT px.id AS id_periksa, p.nama AS nama_pasien, p.alamat, p.no_ktp, p.no_hp, p.no_rm, px.tgl_periksa, d.nama AS nama_dokter, dp.keluhan, px.catatan, GROUP_CONCAT(o.nama_obat SEPARATOR ', ') AS obat_diberikan, px.biaya_periksa
              FROM daftar_poli dp
              JOIN pasien p ON dp.id_pasien = p.id
              JOIN periksa px ON dp.id = px.id_daftar_poli
              JOIN detail_periksa dx ON px.id = dx.id_periksa
              JOIN obat o ON dx.id_obat = o.id
              JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
              JOIN dokter d ON jp.id_dokter = d.id
              WHERE d.id = '{$_SESSION['id']}' AND dp.status_periksa = 'Selesai'
              GROUP BY px.id";  // Group by periksa untuk menggabungkan data obat per periksa
    $result = mysqli_query($mysqli, $query);
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../../layout/header.php');?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/bk-poliklinik/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include('../../layout/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/bk-poliklinik/" class="brand-link">
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
                <a href="/bk-poliklinik/dokter" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Dashboard
                        <span class="right badge badge-primary">Dokter</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../jadwal/" class="nav-link">
                <i class="nav-icon fas fa-clipboard"></i>
                    <p>Jadwal Periksa
                        <span class="right badge badge-primary">Dokter</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../periksa/" class="nav-link">
                <i class="nav-icon fas fa-stethoscope"></i>
                    <p>Memeriksa Pasien
                        <span class="right badge badge-primary">Dokter</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../riwayat/" class="nav-link active">
                <i class="nav-icon fas fa-notes-medical"></i>
                    <p>Riwayat Pasien
                        <span class="right badge badge-primary">Dokter</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../profil/" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                    <p>Profile
                        <span class="right badge badge-primary">Dokter</span>
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
            <h1 class="m-0">Riwayat Pasien</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../dokter">Home</a></li>
              <li class="breadcrumb-item active">Riwayat</li>
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
            <h2 class="card-title">Riwayat Pasien</h2>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px;">No</th>
                        <th>Nama Pasien</th>
                        <th>Alamat</th>
                        <th>No. KTP</th>
                        <th>No. Telepon</th>
                        <th>No. RM</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        while ($data = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($data['nama_pasien']); ?></td>
                            <td><?php echo htmlspecialchars($data['alamat']); ?></td>
                            <td><?php echo htmlspecialchars($data['no_ktp']); ?></td>
                            <td><?php echo htmlspecialchars($data['no_hp']); ?></td>
                            <td><?php echo htmlspecialchars($data['no_rm']); ?></td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalDetail<?php echo $data['id_periksa']; ?>">
                                    <i class="fa fa-eye"></i> Detail Riwayat Periksa
                                </button>
                            </td>
                        </tr>
                    <?php 
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals -->
    <?php
        mysqli_data_seek($result, 0); // Mengembalikan kursor data ke awal
        while ($data = mysqli_fetch_assoc($result)) {
    ?>
    <div class="modal fade" id="modalDetail<?php echo $data['id_periksa']; ?>" tabindex="-1" aria-labelledby="modalDetailLabel<?php echo $data['id_periksa']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel<?php echo $data['id_periksa']; ?>">Riwayat Periksa <?php echo htmlspecialchars($data['nama_pasien']); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Tanggal Periksa</th>
                                <th scope="col">Nama Pasien</th>
                                <th scope="col">Nama Dokter</th>
                                <th scope="col">Keluhan</th>
                                <th scope="col">Catatan</th>
                                <th scope="col">Obat</th>
                                <th scope="col">Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d F Y', strtotime($data['tgl_periksa'])); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_pasien']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_dokter']); ?></td>
                                <td><?php echo htmlspecialchars($data['keluhan']); ?></td>
                                <td><?php echo htmlspecialchars($data['catatan']); ?></td>
                                <td><?php echo htmlspecialchars($data['obat_diberikan']); ?></td>
                                <td>Rp <?php echo number_format($data['biaya_periksa'], 0, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php 
        }
    ?>
</section>



    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include('../../layout/footer.php');?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
</html>

