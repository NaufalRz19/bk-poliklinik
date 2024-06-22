<?php
if (!isset($_SESSION)) { 
    session_start(); 
}

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'dokter') {
    header("Location: /bk-poliklinik/");
    exit;
}

include_once("../../koneksi.php");

// Ambil id dari URL
$id_periksa = isset($_GET['id']) ? $_GET['id'] : '';

if ($id_periksa) {
    // Query untuk mengambil data periksa berdasarkan id
    $query_periksa = "SELECT p.id, dp.id_pasien, p.tgl_periksa, p.catatan, p.biaya_periksa, pasien.nama AS nama_pasien
                      FROM periksa p
                      INNER JOIN daftar_poli dp ON p.id_daftar_poli = dp.id
                      INNER JOIN pasien ON dp.id_pasien = pasien.id
                      WHERE p.id = '$id_periksa'";
    $result_periksa = mysqli_query($mysqli, $query_periksa);

    if (!$result_periksa) {
        echo "Error retrieving data: " . mysqli_error($mysqli);
        exit;
    }

    $data_periksa = mysqli_fetch_assoc($result_periksa);

    if (!$data_periksa) {
        echo "Data Periksa dengan ID $id_periksa tidak ditemukan.";
        exit;
    }

    // Fetch list of medicines
    $obat_query = "SELECT id, nama_obat, harga FROM obat";
    $obat_result = mysqli_query($mysqli, $obat_query);
} else {
    echo "ID Periksa tidak ditemukan.";
    exit;
}

if (isset($_POST['simpan'])) {
    $id_periksa = mysqli_real_escape_string($mysqli, $_POST['id_periksa']);
    $tanggal_periksa = mysqli_real_escape_string($mysqli, $_POST['tanggal_periksa']);
    $catatan = mysqli_real_escape_string($mysqli, $_POST['catatan']);
    $id_obat = isset($_POST['id_obat']) ? $_POST['id_obat'] : [];

    // Hitung total biaya obat
    $total_biaya_obat = 0;
    foreach ($id_obat as $selected_obat_id) {
        $obat_query = "SELECT harga FROM obat WHERE id = '$selected_obat_id'";
        $obat_result = mysqli_query($mysqli, $obat_query);
        $obat = mysqli_fetch_assoc($obat_result);
        if ($obat) {
            $total_biaya_obat += $obat['harga'];
        }
    }

    // Biaya periksa tetap sebesar 150000
    $biaya_periksa = 150000 + $total_biaya_obat;

    // Update data ke tabel periksa
    $update = mysqli_query($mysqli, "UPDATE periksa 
                                    SET tgl_periksa = '$tanggal_periksa', 
                                        catatan = '$catatan', 
                                        biaya_periksa = '$biaya_periksa'
                                    WHERE id = '$id_periksa'");

    if ($update) {
        // Hapus detail periksa yang lama
        $hapus_detail = mysqli_query($mysqli, "DELETE FROM detail_periksa WHERE id_periksa = '$id_periksa'");

        // Masukkan kembali detail periksa yang baru
        foreach ($id_obat as $selected_obat_id) {
            $tambah_detail = mysqli_query($mysqli, "INSERT INTO detail_periksa (id_periksa, id_obat)
                                                    VALUES ('$id_periksa', '$selected_obat_id')");

            if ($tambah_detail) {
                echo "<script>alert('Data berhasil diperbarui.');</script>";
                echo "<script>document.location='/bk-poliklinik/dokter/periksa';</script>";
                
            } else{
                echo "Error: " . mysqli_error($mysqli);
                break;
            }
        }

        // Kembalikan status periksa ke "Selesai" jika sudah ada id_daftar_poli
        // $update_status = mysqli_query($mysqli, "UPDATE daftar_poli SET status_periksa = 'Selesai' WHERE id = '" . $data_periksa['id_daftar_poli'] . "'");

        // if ($update_status) {
        //     echo "<script>alert('Data berhasil diperbarui.');</script>";
        //     echo "<script>document.location='/bk-poliklinik/dokter/periksa';</script>";
        // } else {
        //     echo "Error: " . mysqli_error($mysqli);
        // }
    } else {
        echo "Error: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('../../layout/header.php'); ?>
<head>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/bk-poliklinik/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <?php include('../../layout/navbar.php'); ?>
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
                <a href="../periksa/" class="nav-link active">
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
            <h1 class="m-0">Edit Periksa Pasien</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../dokter">Home</a></li>
              <li class="breadcrumb-item"> <a href="../../dokter/periksa">Memeriksa Pasien</a></li>
              <li class="breadcrumb-item active">Edit Periksa</li>
            </ol>
          </div><!-- /.col-->
        </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
    <div class="card card-primary">
        <div class="card-header">
            <h2 class="card-title">Edit Periksa Pasien</h2>
        </div>
        <form method="POST" action="" name="myForm">
            <div class="card-body">
                <div class="form-group">
                    <label for="namaPasien">Nama Pasien</label>
                    <input type="text" class="form-control" name="nama_pasien" id="namaPasien" value="<?php echo isset($data_periksa['nama_pasien']) ? $data_periksa['nama_pasien'] : ''; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="tanggalPeriksa">Tanggal Periksa</label>
                    <input type="date" class="form-control" name="tanggal_periksa" id="tanggalPeriksa" value="<?php echo isset($data_periksa['tgl_periksa']) ? $data_periksa['tgl_periksa'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="catatan">Catatan</label>
                    <input type="text" class="form-control" name="catatan" id="catatan" value="<?php echo isset($data_periksa['catatan']) ? $data_periksa['catatan'] : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="obat">Obat</label>
                    <select class="form-control" name="id_obat[]" multiple="multiple" id="id_obat">
                        <option hidden>Pilih Obat</option>
                        <?php
                        $selected_obat_query = mysqli_query($mysqli, "SELECT id_obat FROM detail_periksa WHERE id_periksa = '$id_periksa'");
                        $selected_obat_ids = [];
                        while ($selected_obat = mysqli_fetch_assoc($selected_obat_query)) {
                            $selected_obat_ids[] = $selected_obat['id_obat'];
                        }

                        $obat_query = mysqli_query($mysqli, "SELECT * FROM obat");
                        while ($data_obat = mysqli_fetch_array($obat_query)) {
                            $selected = (in_array($data_obat['id'], $selected_obat_ids)) ? 'selected="selected"' : '';
                        ?>
                            <option value="<?php echo $data_obat['id']; ?>" <?php echo $selected; ?>><?php echo $data_obat['nama_obat']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="card-body text-center">
                <input type="hidden" name="id_periksa" value="<?php echo $id_periksa; ?>">
                <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
            </div>
        </form>
    </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include('../../layout/footer.php'); ?>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/bk-poliklinik/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/bk-poliklinik/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- AdminLTE App -->
<script src="/bk-poliklinik/dist/js/adminlte.min.js"></script>

<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        $('#id_obat').select2();
    });
</script>

</body>
</html>

