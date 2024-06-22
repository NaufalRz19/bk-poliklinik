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
    include_once("../../koneksi.php");
    if (isset($_POST['simpan'])) {
        if (isset($_POST['id'])) {
            $ubah = mysqli_query($mysqli, "UPDATE obat SET 
                                            nama_obat = '" . $_POST['nama_obat'] . "',
                                            kemasan = '" . $_POST['kemasan'] . "',
                                            harga = '" . $_POST['harga'] . "'
                                            WHERE
                                            id = '" . $_POST['id'] . "'");
        } else {
            $tambah = mysqli_query($mysqli, "INSERT INTO obat (nama_obat, kemasan, harga) 
                                            VALUES (
                                                '" . $_POST['nama_obat'] . "',
                                                '" . $_POST['kemasan'] . "',
                                                '" . $_POST['harga'] . "'
                                            )");
        }
        echo "<script> 
                document.location='index.php';
                </script>";
    }
    if (isset($_GET['aksi'])) {
        if ($_GET['aksi'] == 'hapus') {
            $hapus = mysqli_query($mysqli, "DELETE FROM obat WHERE id = '" . $_GET['id'] . "'");
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
    <a href="/bk-poliklinik/dokter/" class="brand-link">
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
                <a href="/bk-poliklinik/admin" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>Dashboard
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../dokter" class="nav-link">
                    <i class="nav-icon fas fa-user-md"></i>
                    <p>Dokter
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../pasien" class="nav-link">
                    <i class="nav-icon fas fa-user-injured"></i>
                    <p>Pasien
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../poli" class="nav-link">
                    <i class="nav-icon fas fa-hospital"></i>
                    <p>Poli
                        <span class="right badge badge-success">Admin</span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="../obat" class="nav-link active">
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
            <h1 class="m-0">Obat</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../../admin">Home</a></li>
              <li class="breadcrumb-item active">Obat</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <form class="form-horizontal" method="POST" action="" name="myForm">
            <!-- PHP code to retrieve data if ID is set -->
            <?php
            $nama_obat = '';
            $kemasan = '';
            $harga = '';
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT * FROM obat WHERE id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $nama_obat = $row['nama_obat'];
                    $kemasan = $row['kemasan'];
                    $harga = $row['harga'];
                }
            ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
            }
            ?>
            <div class="mb-3">
                <label for="inputNama" class="form-label fw-bold">Nama Obat</label>
                <input type="text" class="form-control" name="nama_obat" id="inputNama" placeholder="Nama Obat" value="<?php echo $nama_obat ?>" required>
            </div>
            <div class="mb-3">
                <label for="inputKemasan" class="form-label fw-bold">Kemasan</label>
                <input type="text" class="form-control" name="kemasan" id="inputKemasan" placeholder="kemasan" value="<?php echo $kemasan ?>" required>
            </div>
            <div class="mb-3">
                <label for="inputHarga" class="form-label fw-bold">Harga</label>
                <input type="text" class="form-control" name="harga" id="inputHarga" placeholder="harga" value="<?php echo $harga ?>" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary rounded-pill px-3 mt-auto" name="simpan">Simpan</button>
            </div>  
        </form>  
<!-- Table -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Data Obat</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Nama Obat</th>
                            <th>Kemasan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PHP code to fetch and display data -->
                        <?php
                        $result = mysqli_query($mysqli, "SELECT * FROM obat");
                        $no = 1;
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $data['nama_obat'] ?></td>
                                <td><?php echo $data['kemasan'] ?></td>
                                <td>Rp <?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <a class="btn btn-success" href="index.php?id=<?php echo $data['id'] ?>">Ubah</a>
                                    <a class="btn btn-danger" href="index.php?id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                                </td>
                            </tr>
                        <?php
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
