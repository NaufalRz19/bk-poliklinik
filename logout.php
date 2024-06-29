<?php
session_start();
if (isset($_SESSION['username'])) {
    // Hapus session
    session_unset();
    session_destroy();
}

header("Location: /bk-poliklinik/");
exit();
?>
