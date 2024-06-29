<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'pasien') {
        header("Location: /bk-poliklinik/");
        exit;
    }
    include_once("../../koneksi.php");

    if (isset($_POST['simpan'])) {
        $no_rm = $_SESSION['no_rm']; // Menggunakan nomor RM dari sesi
        $id_jadwal = $_POST['jadwal'];
        $keluhan = $_POST['keluhan'];
    
        // Mengambil nomor antrian terakhir untuk jadwal yang dipilih
        $result = mysqli_query($mysqli, "SELECT no_antrian FROM daftar_poli WHERE id_jadwal='$id_jadwal' ORDER BY no_antrian DESC LIMIT 1");
        $row = mysqli_fetch_assoc($result);
        $no_antrian = ($row) ? $row['no_antrian'] + 1 : 1; // Jika sudah ada antrian, nomor antrian baru adalah nomor terakhir + 1, jika tidak maka nomor antrian pertama adalah 1
    
        $id_pasien_result = mysqli_query($mysqli, "SELECT id FROM pasien WHERE no_rm='$no_rm'");
        $id_pasien_row = mysqli_fetch_assoc($id_pasien_result);
        $id_pasien = $id_pasien_row['id'];
    
        // Menyimpan data ke tabel daftar_poli
        $query = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian, status_periksa) VALUES ('$id_pasien', '$id_jadwal', '$keluhan', '$no_antrian', 'Belum Selesai')";
        $result_insert = mysqli_query($mysqli, $query);

        if ($result_insert) {
            // Jika penyimpanan berhasil
            $status = 'success';
        } else {
            // Jika penyimpanan gagal
            $status = 'error';
        }

        // Redirect dengan status
        header("Location: index.php?status=$status");
        exit;
    }

    $status = isset($_GET['status']) ? $_GET['status'] : '';

    // Notifikasi berdasarkan status
    if ($status == 'success') {
        $alertClass = 'alert-success';
        $message = 'Pendaftaran berhasil.';
    } elseif ($status == 'error') {
        $alertClass = 'alert-danger';
        $message = 'Pendaftaran gagal. Silakan coba lagi.';
    } else {
        // Jika tidak ada status, tampilkan halaman tanpa notifikasi
        $alertClass = '';
        $message = '';
    }
    
    if (isset($_GET['getJadwal'])) {
        $id_poli = $_GET['id_poli'];
        $result = mysqli_query($mysqli, "SELECT jp.id, d.nama AS nama_dokter, jp.hari, jp.jam_mulai, jp.jam_selesai  FROM jadwal_periksa jp JOIN dokter d ON jp.id_dokter = d.id WHERE d.id_poli = '$id_poli' AND jp.aktif = 'Y'");
        $jadwal_list = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $jadwal_list[] = $row;
        }
        echo json_encode($jadwal_list);
        exit;
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
      <?php if ($message): ?>
        <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
      <?php endif; ?>
      <div class="row">
        <div class="col-md-4">
          <div class="card card-primary">
            <div class="card-header">
              <h2 class="card-title">Daftar Poli</h2>
            </div>
            <div class="card-body">
              <form class="form-horizontal" method="POST" action="" name="myForm">
                <div class="mb-3">
                  <label for="inputNoRM" class="form-label fw-bold">Nomor Rekam Medis</label>
                  <input type="text" class="form-control" name="no_rm" id="inputNoRM" value="<?php echo $_SESSION['no_rm']; ?>" readonly>
                </div>
                <div class="mb-3">
                  <label for="inputPoli" class="form-label fw-bold">Pilih Poli</label>
                  <select class="form-control" name="poli" id="inputPoli" required>
                    <option value="" disabled selected>Select Poli</option>
                    <?php
                    $poli_result = mysqli_query($mysqli, "SELECT * FROM poli");
                    while ($poli = mysqli_fetch_assoc($poli_result)) {
                      echo "<option value='" . $poli['id'] . "'>" . $poli['nama_poli'] . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="inputJadwal" class="form-label fw-bold">Pilih Jadwal</label>
                  <select class="form-control" name="jadwal" id="inputJadwal" required>
                    <option value="" disabled selected>Select Jadwal</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="inputKeluhan" class="form-label fw-bold">Keluhan</label>
                  <textarea class="form-control" name="keluhan" id="inputKeluhan" placeholder="Tuliskan keluhan anda" required></textarea>
                </div>
                <div class="mb-3 text-right">
                  <button type="submit" class="btn btn-primary" name="simpan">
                    <i class="fas fa-save"></i> Simpan
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h2 class="card-title">Riwayat Daftar Poli</h2>
            </div>
            <div class="card-body p-0">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th style="width: 10px">No</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Hari</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Antrian</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Ambil ID pasien dari session
                  $id_pasien = $_SESSION['id'];

                  // Query untuk mendapatkan riwayat daftar poli pasien
                  $query = "SELECT dp.id, p.nama_poli, d.nama AS nama_dokter, jp.hari, jp.jam_mulai, jp.jam_selesai, dp.no_antrian, dp.status_periksa
                            FROM daftar_poli dp
                            INNER JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                            INNER JOIN dokter d ON jp.id_dokter = d.id
                            INNER JOIN poli p ON d.id_poli = p.id
                            WHERE dp.id_pasien = $id_pasien
                            ORDER BY dp.id DESC"; // Anda dapat mengurutkan berdasarkan kriteria yang sesuai

                  $result = mysqli_query($mysqli, $query);
                  $no = 1;
                  while ($row = mysqli_fetch_assoc($result)) {

                    $isLatest = ($no == 1);
                    $badgeMarkup = $isLatest ? '<span class="badge badge-info">New</span>' : '';
                
                    echo "<tr>
                            <td>" .($isLatest ? $badgeMarkup : $no) . "</td>
                            <td>" . $row['nama_poli'] . "</td>
                            <td>" . $row['nama_dokter'] . "</td>
                            <td>" . $row['hari'] . "</td>
                            <td>" . $row['jam_mulai'] . "</td>
                            <td>" . $row['jam_selesai'] . "</td>
                            <td>" . $row['no_antrian'] . "</td>
                            <td><a class='btn btn-success' href='/bk-poliklinik/pasien/poli/detail.php?id=" . $row['id'] . "'>Detail</a></td>
                          </tr>";
                    $no++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
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

<!-- Script -->
<script>
  document.getElementById('inputPoli').addEventListener('change', function() {
    var poliId = this.value;
    var jadwalSelect = document.getElementById('inputJadwal');
    jadwalSelect.innerHTML = '<option value="" disabled selected>Select Jadwal</option>';
    if (poliId) {
      fetch('index.php?getJadwal&id_poli=' + poliId)
        .then(response => response.json())
        .then(data => {
          data.forEach(jadwal => {
            var option = document.createElement('option');
            option.value = jadwal.id;
            option.textContent = `${jadwal.nama_dokter} | ${jadwal.hari} | ${jadwal.jam_mulai} - ${jadwal.jam_selesai}`;
            jadwalSelect.appendChild(option);
          });
        });
    }
  });
</script>

<!-- Bootstrap 4 -->
<script src="/bk-poliklinik/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/bk-poliklinik/dist/js/adminlte.min.js"></script>

</body>
</html>

