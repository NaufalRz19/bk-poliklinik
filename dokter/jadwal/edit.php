<?php
    if (!isset($_SESSION)) { 
        session_start(); 
    } 
    if (!isset($_SESSION['username']) || $_SESSION['role'] != 'dokter') {
        // Jika pengguna belum login atau bukan dokter, redirect ke halaman login
        header("Location: /bk-poliklinik/");
        exit;
    }

    include_once("../../koneksi.php");

    if (isset($_POST['simpan'])) {
        // Ambil data dari form
        $id_jadwal = $_POST['id']; // ID jadwal periksa yang diedit
        $id_dokter = $_SESSION['id']; // ID dokter yang sedang login
        $hari = $_POST['hari'];
        $jam_mulai = $_POST['jam_mulai'];
        $jam_selesai = $_POST['jam_selesai'];
        $aktif = $_POST['aktif'];

        // Update status aktif lainnya menjadi tidak aktif jika $aktif baru adalah "Y"
        if ($aktif == 'Y') {
            $queryUpdate = "UPDATE jadwal_periksa SET aktif = 'T' WHERE aktif = 'Y'";
            $stmtUpdate = $mysqli->prepare($queryUpdate);
            $stmtUpdate->execute();
        }
        // Update data jadwal periksa berdasarkan ID
        $query = "UPDATE jadwal_periksa 
                  SET id_dokter = ?, hari = ?, jam_mulai = ?, jam_selesai = ?, aktif = ? 
                  WHERE id = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("issssi", $id_dokter, $hari, $jam_mulai, $jam_selesai, $aktif, $id_jadwal);
        $stmt->execute();
        
        // Redirect kembali ke halaman index.php setelah berhasil disimpan
        header("Location: index.php");
        exit;
    }

    // Ambil data jadwal periksa yang akan diedit berdasarkan ID
    $nama_dokter = '';
    $hari = '';
    $jam_mulai = '';
    $jam_selesai = '';
    $aktif = '';

    if (isset($_GET['id'])) {
        $query = "SELECT jp.id, jp.id_dokter, jp.hari, jp.jam_mulai, jp.jam_selesai, jp.aktif, d.nama 
                  FROM jadwal_periksa jp 
                  INNER JOIN dokter d ON jp.id_dokter = d.id 
                  WHERE jp.id = ?";
        
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nama_dokter = $row['nama'];
            $hari = $row['hari'];
            $jam_mulai = $row['jam_mulai'];
            $jam_selesai = $row['jam_selesai'];
            $aktif = $row['aktif'];
        } else {
            // Handle jika ID jadwal periksa tidak ditemukan
            echo "Data tidak ditemukan.";
            exit;
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
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Sidebar -->
        <?php
        if (!isset($_SESSION)) { 
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
                        <h1 class="m-0">Edit Jadwal Periksa</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dokter">Home</a></li>
                            <li class="breadcrumb-item"><a href="../../dokter/jadwal">Jadwal Periksa</a></li>
                            <li class="breadcrumb-item active">Edit Jadwal Periksa</li>
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
                    <h2 class="card-title">Edit Jadwal Periksa</h2>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" method="POST" action="" name="myForm">
                        <div class="mb-3">
                            <label for="inputNama" class="form-label fw-bold">Nama Dokter</label>
                            <input type="text" class="form-control" name="nama_dokter" id="inputNama" placeholder="Nama Dokter" value="<?php echo $nama_dokter; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="inputHari" class="form-label fw-bold">Hari</label>
                            <input type="text" class="form-control" id="inputHari" name="hari" value="<?php echo htmlspecialchars($hari); ?>" readonly>
                        </div>
                        <!-- <div class="mb-3">
                            <label for="inputHari" class="form-label fw-bold">Hari</label>
                            <select class="form-control" name="hari" id="inputHari" readonly >
                                <option value="">Pilih Hari</option>
                                <option value="Senin" <?php if ($hari == 'Senin') echo 'selected'; ?>>Senin</option>
                                <option value="Selasa" <?php if ($hari == 'Selasa') echo 'selected'; ?>>Selasa</option>
                                <option value="Rabu" <?php if ($hari == 'Rabu') echo 'selected'; ?>>Rabu</option>
                                <option value="Kamis" <?php if ($hari == 'Kamis') echo 'selected'; ?>>Kamis</option>
                                <option value="Jumat" <?php if ($hari == 'Jumat') echo 'selected'; ?>>Jumat</option>
                                <option value="Sabtu" <?php if ($hari == 'Sabtu') echo 'selected'; ?>>Sabtu</option>
                                <option value="Minggu" <?php if ($hari == 'Minggu') echo 'selected'; ?>>Minggu</option>
                            </select>
                        </div> -->
                        <div class="mb-3">
                            <label for="inputJamMulai" class="form-label fw-bold">Jam Mulai</label>
                            <input type="time" class="form-control" name="jam_mulai" id="inputJamMulai" placeholder="Jam Mulai" value="<?php echo $jam_mulai; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="inputJamSelesai" class="form-label fw-bold">Jam Selesai</label>
                            <input type="time" class="form-control" name="jam_selesai" id="inputJamSelesai" placeholder="Jam Selesai" value="<?php echo $jam_selesai; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="inputStatus" class="form-label fw-bold">Status</label>
                            <div>
                                <input type="radio" id="statusAktif" name="aktif" value="Y" <?php if ($aktif == 'Y') echo 'checked'; ?>>
                                <label for="statusAktif">Aktif</label>
                            </div>
                            <div>
                                <input type="radio" id="statusTidakAktif" name="aktif" value="T" <?php if ($aktif == 'T') echo 'checked'; ?>>
                                <label for="statusTidakAktif">Tidak Aktif</label>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
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

    <!-- Main Footer -->
    <?php include('../../layout/footer.php');?>
    <!-- /.footer -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</body>
</html>
