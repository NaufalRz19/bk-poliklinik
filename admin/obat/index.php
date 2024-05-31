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
  
  $nama = $_SESSION['username'];
  $role = $_SESSION['role'];
    
  if($role != 'admin'){
    echo "<meta http-equiv='refresh' content='0; url=../..'>";
    die();
  }
    
  if(isset($_POST['simpan'])){
    if(isset($_POST['id'])){
      $stmt = $pdo->prepare("UPDATE obat SET nama_obat = :nama_obat, kemasan = :kemasan, harga = :harga WHERE id = :id");
      $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
      $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
      $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);
      $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
      $stmt->execute();
    
      header('Location:index.php');
    } else {
      $stmt = $pdo->prepare("INSERT INTO obat (nama_obat, kemasan, harga) VALUES (:nama_obat, :kemasan, :harga)");
      $stmt->bindParam(':nama_obat', $_POST['nama_obat'], PDO::PARAM_STR);
      $stmt->bindParam(':kemasan', $_POST['kemasan'], PDO::PARAM_STR);
      $stmt->bindParam(':harga', $_POST['harga'], PDO::PARAM_INT);
      $stmt->execute();
      header('Location:index.php');
    }
  }
  if(isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $stmt = $pdo->prepare("DELETE FROM obat WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    
    header('Location:index.php');
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
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <form method="POST" action="#" class="form col" name="myform" onsubmit="return(validate());">
                    <?php
                      $nama_obat = '';
                      $kemasan = '';
                      $harga = '';
                      if(isset($_GET['id'])){
                        try {
                          $stmt = $pdo->prepare("SELECT * FROM obat WHERE id = :id");
                          $stmt->bindParam(':id', $_GET['id']);
                          $stmt->execute();

                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                            $nama_obat = $row['nama_obat'];
                            $kemasan = $row['kemasan'];
                            $harga = $row['harga'];
                          }
                        } catch (PDOException $e){
                          echo "Error: " . $e->getMessage();
                        }
                    ?>
                    <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                    <?php
                    }
                    ?>
                    <div class="row mt-3">
                      <label for="nama_obat" class="form-label fw-bold">Nama Obat</label>
                      <input type="text" class="form-control" name="nama_obat" id="nama_obat" placeholder="Nama Obat" value="<?php echo $nama_obat ?>">
                    </div>
                    <div class="row mt-3">
                      <label for="kemasan" class="form-label fw-bold">Kemasan</label>
                      <input type="text" class="form-control" name="kemasan" id="kemasan" placeholder="Nama Obat" value="<?php echo $kemasan ?>">
                    </div>
                    <div class="row mt-3">
                      <label for="harga" class="form-label fw-bold">Harga</label>
                      <input type="number" class="form-control" name="harga" id="harga" placeholder="Nama Obat" value="<?php echo $harga ?>">
                    </div>
                    <div class="row d-flex mt-3 mb-3">
                      <button type="submit" class="btn btn-primary rounded-pill" style="width: 3cm;" name="simpan">Simpan</button>
                    </div>
                </form>
            </div>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Obat</th>
                        <th>Kemasan</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $pdo->query("SELECT * FROM obat");
                    $no = 1;
                    while($data = $result->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <tr>
                      <td><?php echo $no++ ?></td>
                      <td><?php echo $data['nama_obat'] ?></td>
                      <td><?php echo $data['kemasan'] ?></td>
                      <td>Rp. <?php echo $data['harga'] ?></td>
                      <td>
                        <a href="index.php?page=obat.php&id=<?php echo $data['id'] ?>" class="btn btn-success rounded-pill px-3">Edit</a>
                        <a href="index.php?page=obat.php&id=<?php echo $data['id'] ?>&aksi=hapus" class="btn btn-danger rounded-pill px-3">Hapus</a>
                      </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
            ?>
        </div>
      </div><!-- /.container-fluid -->
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
