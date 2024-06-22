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
    if (isset($_POST['simpan'])) {
        $nama_dokter = $_SESSION['username']; // Nama dokter yang sedang login
        $id_dokter = $_SESSION['id']; // Asumsikan ada variabel session untuk ID dokter

        $hari = $_POST['hari'];
        $jam_mulai = $_POST['jam_mulai'];
        $jam_selesai = $_POST['jam_selesai'];
        $aktif = $_POST['aktif'];
 
        $tambah = mysqli_query($mysqli, "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, aktif) 
                                            VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai', '$aktif')");
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
            <h1 class="m-0">Tambah Jadwal Periksa</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../dokter">Home</a></li>
              <li class="breadcrumb-item"><a href="../../dokter/jadwal">Jadwal Periksa</a></li>
              <li class="breadcrumb-item active">Tambah Jadwal Periksa</li>
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
            <h2 class="card-title">Tambah Jadwal Periksa</h2>
        </div>
        <div class="card-body">
            <form class="form-horizontal" method="POST" action="" name="myForm">
                <div class="mb-3">
                    <label for="inputNama" class="form-label fw-bold">Nama Dokter</label>
                    <input type="text" class="form-control" name="nama_dokter" id="inputNama" placeholder="Nama Dokter" value="<?php echo $_SESSION['username']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="inputHari" class="form-label fw-bold">Hari</label>
                    <select class="form-control" name="hari" id="inputHari" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="inputJamMulai" class="form-label fw-bold">Jam Mulai</label>
                    <input type="time" class="form-control" name="jam_mulai" id="inputJamMulai" placeholder="Jam Mulai" required>
                </div>
                <div class="mb-3">
                    <label for="inputJamSelesai" class="form-label fw-bold">Jam Selesai</label>
                    <input type="time" class="form-control" name="jam_selesai" id="inputJamSelesai" placeholder="Jam Selesai" required>
                </div>
                <div class="mb-3">
                    <label for="inputStatus" class="form-label fw-bold">Status</label><br>
                    <input type="radio" id="aktif" name="aktif" value="Y" required>
                    <label for="aktif">Aktif</label><br>
                    <input type="radio" id="tidak_aktif" name="aktif" value="T">
                    <label for="tidak_aktif">Tidak Aktif</label><br>
                </div>
                <div class="mb-3 text-right">
                    <button type="submit" class="btn btn-primary" name="simpan">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>  
            </form>
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
