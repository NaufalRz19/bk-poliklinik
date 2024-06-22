<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'dokter') {
        // Jika pengguna sudah login, tampilkan tombol "Logout"
        header("Location: /bk-poliklinik/");
        exit;
    }
    include_once("../../koneksi.php");

    if (isset($_GET['aksi'])) {
        if ($_GET['aksi'] == 'hapus') {
            $hapus = mysqli_query($mysqli, "DELETE FROM jadwal_periksa WHERE id = '" . $_GET['id'] . "'");
        }

        echo "<script> 
                document.location='index.php';
                </script>";
    }

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
                <a href="/bk-poliklinik/dokter" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Dashboard
                        <span class="right badge badge-primary">Dokter</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../jadwal/" class="nav-link active">
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
                <a href="../riwayat/" class="nav-link">
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
            <h1 class="m-0">Jadwal Periksa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../dokter">Home</a></li>
              <li class="breadcrumb-item active">Jadwal</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Daftar Jadwal Periksa</h2>
            <a class="btn btn-primary" href="/bk-poliklinik/dokter/jadwal/create.php" style="float: right;"><i class="fas fa-plus"></i> Tambah Jadwal Periksa</a>
        </div>
<!-- Table -->
            <div class="card-body p-0">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Nama Dokter</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PHP code to fetch and display data -->
                        <?php
                        // Pastikan $_SESSION['id'] sudah ada dan merupakan data yang valid
                        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
                            // Lakukan query dengan parameterized query atau cara aman lainnya
                            $query = "SELECT jadwal_periksa.*, dokter.nama 
                                    FROM jadwal_periksa 
                                    JOIN dokter ON jadwal_periksa.id_dokter = dokter.id 
                                    WHERE jadwal_periksa.id_dokter = ?";
                            
                            // Siapkan statement
                            $stmt = mysqli_prepare($mysqli, $query);
                            
                            // Bind parameter
                            mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
                            
                            // Eksekusi statement
                            mysqli_stmt_execute($stmt);
                            
                            // Ambil hasil query
                            $result = mysqli_stmt_get_result($stmt);
                            
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo htmlspecialchars($data['nama']) ?></td>
                                    <td><?php echo htmlspecialchars($data['hari']) ?></td>
                                    <td><?php echo htmlspecialchars($data['jam_mulai']) ?></td>
                                    <td><?php echo htmlspecialchars($data['jam_selesai']) ?></td>
                                    <td>
                                        <?php
                                        if ($data['aktif'] == 'Y') {
                                            echo 'Aktif';
                                        } elseif ($data['aktif'] == 'T') {
                                            echo 'Tidak Aktif';
                                        } else {
                                            // Handle other cases if necessary
                                            echo htmlspecialchars($data['aktif']);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-success" href="/bk-poliklinik/dokter/jadwal/edit.php?id=<?php echo $data['id'] ?>">Ubah</a>
                                        <!-- <a class="btn btn-danger" href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a> -->
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            // Jika $_SESSION['id'] tidak diatur, tampilkan pesan atau tindakan sesuai kebijakan aplikasi Anda
                            echo '<tr><td colspan="6">Data tidak tersedia.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
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
