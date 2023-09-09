<?php
//login
session_start();

if (!isset($_SESSION["login_admin"])) {
    header("location: ../login/adminLogin.php");
    exit();
}
?>

<?php
// koneksi
include '../tools/connection.php';
// header
include '../blade/header.php';
?>

<div class="container">
    <div class="card">
        <div class="card-header bg-info">
            <?php include '../blade/namaProgram.php'; ?>
        </div>
        <!-- nav -->
        <?php include '../blade/navAdmin.php' ?>
        <!-- body -->
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10 shadow py-3">
                    <!-- judul -->
                    <p class="text-center fw-bold">Halaman Admin</p>
                    <hr>
                    <div class="row">
                        <!-- gambar -->
                        <div class="gambar bg-light bg-gradient">
                            <div class="text-center">
                                <img src="../img/foto_pt.jpg" class="rounded" alt="...">
                            </div>
                        </div>

                        <!-- Profil Perusahaan -->
                        <br>
                        <p>PT Family Raya CRF Padang adalah perusahaan yang bergerak dalam produksi dan pengelolaan Crumb Rubber, berlokasi di Jl. Gurun Laweh Nan XX, Kec. Lubuk Begalung, Kota Padang, Sumatera Barat, dapat dihubungi melalui telepon di (0751) 22644.</p>

                        <p>Sistem ini menggunakan metode WP dan SAW dalam menghasilkan Pemilihan Kinerja Karyawan Terbaik PT Family Raya CRF. Diharapkan sistem ini dapat menjadi referensi bagi pengambil keputusan dalam memilih karyawan terbaik.</p>

                    </div>
                </div>
                <div class="col-lg-1"></div>
            </div>
        </div>
    </div>
</div>

<?php include '../blade/footer.php' ?>