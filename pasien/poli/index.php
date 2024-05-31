<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'pasien') {
        // Jika pengguna sudah login, tampilkan tombol "Logout"
        header("Location: /bk-poliklinik/");
        exit;
    }
    include_once("../../koneksi.php");

  $id_pasien = $_SESSION['id'];
  $no_rm = $_SESSION['no_rm'];
  $nama = $_SESSION['username'];
  $role = $_SESSION['role'];

  if(isset($_POST['submit'])) {
    if($_POST['id_jadwal'] == "900") {
      echo "
          <script>
            alert('Jadwal tidak boleh kosong');
          </script>
      ";
      echo "<meta http-equiv='refresh' content='0>";
    }

    if(daftarPoli($_POST) > 0) {
      echo "
          <script>
            alert('Berhasil mendaftar poli');
          </script>
      ";
    } else {
      echo "
          <script>
            alert('Gagal mendaftar poli');
          </script>
      ";
    }
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
    <a href="/bk-poliklinik/dokter/" class="brand-link">
      <img src="/bk-poliklinik/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
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
            <h1 class="m-0">Poli</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../pasien">Home</a></li>
              <li class="breadcrumb-item active">Poli</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-4">
            <!-- Registrasi Poli -->
            <div class="card">
              <h5 class="card-header bg-primary">Daftar Poli</h5>
              <div class="card-body">
                <form action="" method="POST">
                  <input type="hidden" value="<?= $id_pasien ?>" name="id_pasien">
                  <div class="mb-3">
                    <label for="no_rm" class="form-label">Nomor Rekam Medis</label>
                    <input type="text" class="form-control" id="no_rm" placeholder="nomor rekam medis" name="no_rm" value="<?= $no_rm ?>">
                  </div>
                  <div class="mb-3">
                    <label for="inputPoli" class="form-label">Pilih Poli</label>
                    <select class="form-control" id="inputPoli">
                      <option>Open this select menu</option>
                      <?php
                      $data = $pdo->prepare("SELECT * FROM poli");
                      $data->execute();
                      if($data->rowCount() == 0){
                        echo "<option>Tidak ada poli</option>";
                      } else {
                        while($d = $data->fetch()){
                      ?>
                        <option value="<?= $d['id'] ?>"><?= $d['nama_poli'] ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="inputJadwal" class="form-label">Pilih Jadwal</label>
                    <select class="form-control" id="inputJadwal" name="id_jadwal">
                      <option value="900">Open this select menu</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="keluhan" class="form-label">Keluhan</label>
                    <textarea class="form-control" id="keluhan" rows="3" name="keluhan"></textarea>
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary">Daftar</button>
                </form>
              </div>
            </div>
            <!-- End Registrasi poli -->
          </div>
          <div class="col">
            <!-- Registrasi poli Histori -->
            <div class="card">
              <h5 class="card-header bg-primary">Riwayat Daftar Poli</h5>
              <div class="card-body">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th scope="col">No.</th>
                      <th scope="col">Poli</th>
                      <th scope="col">Dokter</th>
                      <th scope="col">Hari</th>
                      <th scope="col">Mulai</th>
                      <th scope="col">Selesai</th>
                      <th scope="col">Antrian</th>
                      <th scope="col">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
                                                  c.nama as dokter_nama,
                                                  b.hari as jadwal_hari,
                                                  b.jam_mulai as jadwal_mulai,
                                                  b.jam_selesai as jadwal_selesai,
                                                  a.no_antrian as antrian,
                                                  a.id as poli_id
                                                  FROM daftar_poli as a
                                                  INNER JOIN jadwal_periksa as b ON a.id_jadwal = b.id
                                                  INNER JOIN dokter as c ON b.id_dokter = c.id
                                                  INNER JOIN poli as d ON c.id_poli = d.id
                                                  WHERE a.id_pasien = $id_pasien
                                                  ORDER BY a.id desc");
                    $poli->execute();
                    $no = 0;
                    if($poli->rowCount() == 0){
                      echo "Tidak ada data";
                    } else {
                      while($p = $poli->fetch()){
                    ?>
                    <tr>
                      <th scope="row">
                        <?php
                        echo ++$no;
                        if($no == 1) {
                          echo "<span class='badge badge-info'>NEW</span>";
                        } else {
                          echo $no;
                        }
                        ?>
                      </th>
                      <td><?= $p['poli_nama'] ?></td>
                      <td><?= $p['dokter_nama'] ?></td>
                      <td><?= $p['jadwal_hari'] ?></td>
                      <td><?= $p['jadwal_mulai'] ?></td>
                      <td><?= $p['jadwal_selesai'] ?></td>
                      <td><?= $p['antrian'] ?></td>
                      <td>
                        <a href="detail_poli.php/<?= $p['poli_id'] ?>">
                          <button class="btn btn-success btn-sm">Detail</button>
                        </a>
                      </td>
                    </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- End Registration poli history -->
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