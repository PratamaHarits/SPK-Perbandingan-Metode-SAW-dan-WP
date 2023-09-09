<?php
// koneksi
include '../tools/connection.php';

// header
include '../blade/header.php';

// ambil data dari perhitungan.php
if (isset($_POST['filter'])) {
    $perhitunganTanggal = $_POST['perhitunganTanggal'];
}
?>

<div class="container">
    <div class="card">
        <div class="card-header bg-info">
            <?php include '../blade/namaProgram.php'; ?>
        </div>
        <!-- nav -->
        <?php include '../blade/nav.php' ?>
        <!-- body -->
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10 shadow py-3">

                    <!-- button trigger modal filter -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-1">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFilter">
                            Filter Data
                        </button>
                    </div>

                    <!-- ====================================================================================================== -->
                    <!-- SAW -->
                    <!-- ====================================================================================================== -->

                    <hr>
                    <p class="text-center fw-bold">Proses Metode SAW</p>
                    <hr>

                    <!-- tabel normalisasi  -->
                    <div class="row mt-3">
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Hasil Normalisasi Metode SAW</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($data);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>

                                    </tr>
                                    <tr class="table-primary">
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $data->fetch_assoc()) { ?>
                                            <td><?= $kriteria['kriteria_nama']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perhitunganTanggal' ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $data->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_nilai = $sql->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_nilai['kriteria_kode'];
                                                $sqli = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $sqli->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                                        ?>
                                                            <td><?= number_format($nilaiNormalisasi = $nilai_Min['min'] / $data_nilai['nilai_faktor'], 2); ?></td>
                                                        <?php } ?>


                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                                        ?>
                                                            <td><?= number_format($nilaiNormalisasi = $data_nilai['nilai_faktor'] / $nilai_Max['max'], 2); ?></td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- tabel preferensi  -->
                    <div class="row mt-3">
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Hasil Preferensi Metode SAW</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($data);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                                        <th rowspan="2">Total Preferensi</th>

                                    </tr>
                                    <tr class="table-primary">
                                        <?php
                                        $data = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $data->fetch_assoc()) { ?>
                                            <td><?= $kriteria['kriteria_nama']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perhitunganTanggal' ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $data->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php $totalPreferensi = 0; //variabel totalPreferensi untuk proses sum nanti
                                            ?>

                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $sql = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_nilai = $sql->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_nilai['kriteria_kode'];
                                                $sqli = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $sqli->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sqlMin =  $conn->query("SELECT kriteria_kode, MIN(nilai_faktor) AS min FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Min = $sqlMin->fetch_assoc()) {
                                                        ?>
                                                            <?php $hasil = $nilai_Min['min'] / $data_nilai['nilai_faktor']; ?>

                                                            <td><?= number_format($min_dikali_kriteria = $hasil * $kriteria['kriteria_bobot'], 2); ?></td>

                                                            <?php $totalPreferensi = $totalPreferensi + $min_dikali_kriteria; ?>

                                                        <?php } ?>


                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sqlMax =  $conn->query("SELECT kriteria_kode, MAX(nilai_faktor) AS max FROM tb_nilai WHERE kriteria_kode='$kriteriaKode' GROUP BY kriteria_kode");
                                                        while ($nilai_Max = $sqlMax->fetch_assoc()) {
                                                        ?>
                                                            <?php $hasil = $data_nilai['nilai_faktor'] / $nilai_Max['max']; ?>

                                                            <td><?= number_format($max_dikali_kriteria = $hasil * $kriteria['kriteria_bobot'], 2); ?></td>

                                                            <?php $totalPreferensi = $totalPreferensi + $max_dikali_kriteria; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>

                                            <td><?= number_format($totalPreferensi, 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>

                    <?php $array_vector_si = array(); ?>
                    <?php $ranks = array(); ?>

                    <!-- ================================================================================================== -->
                    <!-- WP -->
                    <!-- ================================================================================================== -->

                    <!-- judul -->
                    <hr>
                    <p class="text-center fw-bold">Proses Metode WP</p>
                    <hr>

                    <!-- tabel perubahaan bobot -->
                    <div class="row mt-3">
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Perubahan Kriteria</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th>No</th>
                                        <th>Nama Kriteria</th>
                                        <th>Kode Kriteria</th>
                                        <th>Kategori Kriteria</th>
                                        <th>Bobot Awal</th>
                                        <th>Hasil Perbaikan Bobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_kriteria = $conn->query("SELECT * FROM ta_kriteria");
                                    $no = 1;
                                    while ($kriteria = $query_kriteria->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $kriteria['kriteria_nama'] ?></td>
                                            <td><?= $kriteria['kriteria_kode'] ?></td>
                                            <td><?= $kriteria['kriteria_kategori'] ?></td>
                                            <td><?= $kriteria['kriteria_bobot'] ?></td>
                                            <?php
                                            $sql_sum = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                            while ($kriteriaBobot_total = $sql_sum->fetch_assoc()) { ?>
                                                <td><?= number_format($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot'], 4) ?></td>
                                            <?php } ?>
                                        <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- tabel nilai vektor Si  -->
                    <div class="row mt-3">
                        <!-- <div class="col-1"></div> -->
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Nilai Vector Si</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Alternatif</th>
                                        <?php
                                        $query_kriteria = $conn->query("SELECT * FROM ta_kriteria");
                                        $kriteriaRows = mysqli_num_rows($query_kriteria);
                                        ?>
                                        <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                                        <th rowspan="2">Nilai Vektor Si</th>

                                    </tr>
                                    <tr class="table-primary">
                                        <?php
                                        $query_alternatif = $conn->query("SELECT * FROM ta_kriteria");
                                        while ($kriteria = $query_alternatif->fetch_assoc()) { ?>
                                            <td><?= $kriteria['kriteria_nama']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_alternatif = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perhitunganTanggal' ORDER BY alternatif_kode");
                                    $no = 1;
                                    $nilai_vector_si = 0;
                                    while ($alternatif = $query_alternatif->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php
                                            $total_nilai_vektor = 1;
                                            ?>

                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $query_faktor = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_faktor = $query_faktor->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_faktor['kriteria_kode'];
                                                $query_kriteria_faktor = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $query_kriteria_faktor->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                        while ($kriteriaBobot_total = $sql->fetch_assoc()) { ?>
                                                            <?php $nilai_vektor = $data_faktor['nilai_faktor'] ** ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot']) ?>

                                                            <td><?= number_format($nilai_vektor, 4); ?></td>

                                                            <?php $total_nilai_vektor = $total_nilai_vektor * $nilai_vektor; ?>

                                                        <?php } ?>

                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                        while ($kriteriaBobot_total = $sql->fetch_assoc()) { ?>
                                                            <?php $nilai_vektor = $data_faktor['nilai_faktor'] ** (-1 * ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot'])) ?>

                                                            <td><?= number_format($nilai_vektor, 4); ?></td>

                                                            <?php $total_nilai_vektor = $total_nilai_vektor * $nilai_vektor; ?>

                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>

                                            <td><?= number_format($total_nilai_vektor, 2); ?></td>

                                            <?php

                                            // mencari total nilai vektor
                                            $nilai_vector_si += $total_nilai_vektor;
                                            // masukan total nilai vektor ke array
                                            $vector_si['jumlah_semua_vector'] = $nilai_vector_si;
                                            array_push($array_vector_si, $vector_si);
                                            ?>

                                        </tr>
                                    <?php } ?>
                                    <?php
                                    // ambil nilai array terakhir dan masukan kedalam array
                                    $array_vector_total = array();
                                    array_push($array_vector_total, end($array_vector_si[count($array_vector_si) - 1]));
                                    // hasil total nilai vektor dimasukan kedalam variabel
                                    $jumlah_vektor_total = end($array_vector_total);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="col-1"></div> -->
                    </div>

                    <!-- tabel nilai vektor Vi   -->
                    <div class="row mt-3">
                        <div class="col">
                            <p class="text-center fw-bold">Tabel Nilai Vector Vi</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr class="table-primary">
                                        <th>No</th>
                                        <th>Nama Alternatif</th>
                                        <th>Nilai Vektor Si</th>
                                        <th>Total Nilai Vektor Si</th>
                                        <th>Nilai Vektor Vi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query_alternatif = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perhitunganTanggal' ORDER BY alternatif_kode");
                                    $no = 1;
                                    while ($alternatif = $query_alternatif->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $alternatif['alternatif_nama'] ?></td>
                                            <?php $total_nilai_vektor = 1; ?>

                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $query_faktor = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_faktor = $query_faktor->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_faktor['kriteria_kode'];
                                                $query_kriteria_faktor = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $query_kriteria_faktor->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                        while ($kriteriaBobot_total = $sql->fetch_assoc()) { ?>
                                                            <?php $nilai_vektor = $data_faktor['nilai_faktor'] ** ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot']) ?>

                                                            <?php number_format($nilai_vektor, 2); ?>

                                                            <?php $total_nilai_vektor = $total_nilai_vektor * $nilai_vektor; ?>

                                                        <?php } ?>

                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                        while ($kriteriaBobot_total = $sql->fetch_assoc()) { ?>
                                                            <?php $nilai_vektor = $data_faktor['nilai_faktor'] ** (-1 * ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot'])) ?>

                                                            <?php number_format($nilai_vektor, 2); ?>

                                                            <?php $total_nilai_vektor = $total_nilai_vektor * $nilai_vektor; ?>

                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>

                                            <td><?= number_format($total_nilai_vektor, 2); ?></td>
                                            <td><?= number_format($jumlah_vektor_total, 2); ?></td>

                                            <!-- hasil vektor -->

                                            <?php
                                            $alternatifKode = $alternatif['alternatif_kode'];
                                            $query_faktor = $conn->query("SELECT * FROM tb_nilai WHERE alternatif_kode='$alternatifKode' ORDER BY kriteria_kode");
                                            while ($data_faktor = $query_faktor->fetch_assoc()) { ?>
                                                <?php
                                                $kriteriaKode = $data_faktor['kriteria_kode'];
                                                $query_kriteria_faktor = $conn->query("SELECT * FROM ta_kriteria WHERE kriteria_kode='$kriteriaKode' ORDER BY kriteria_kode");
                                                while ($kriteria = $query_kriteria_faktor->fetch_assoc()) {
                                                ?>
                                                    <?php if ($kriteria['kriteria_kategori'] == "benefit") { ?>
                                                        <?php
                                                        $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                        while ($kriteriaBobot_total = $sql->fetch_assoc()) { ?>
                                                            <?php $nilai_vektor = $data_faktor['nilai_faktor'] ** ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot']) ?>

                                                            <?php number_format($nilai_vektor, 2); ?>

                                                            <?php
                                                            $nilai_wp = $total_nilai_vektor / $jumlah_vektor_total;
                                                            ?>

                                                        <?php } ?>

                                                    <?php } elseif ($kriteria['kriteria_kategori'] == "cost") { ?>
                                                        <?php
                                                        $sql = $conn->query("SELECT SUM(kriteria_bobot) as total_kriteria_bobot FROM ta_kriteria");
                                                        while ($kriteriaBobot_total = $sql->fetch_assoc()) { ?>
                                                            <?php $nilai_vektor = $data_faktor['nilai_faktor'] ** (-1 * ($kriteria['kriteria_bobot'] / $kriteriaBobot_total['total_kriteria_bobot'])) ?>

                                                            <?php number_format($nilai_vektor, 2); ?>

                                                            <?php
                                                            $nilai_wp = $total_nilai_vektor / $jumlah_vektor_total;
                                                            ?>

                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                            <td><?= number_format($nilai_wp, 2); ?></td>

                                            <?php
                                            //masukan  nilai hasil-sum, nama-alternatif, kode-alternatif ke dalam variabel $ranks(baris 24)
                                            $rank['nilaiWP'] = $nilai_wp;
                                            $rank['alternatifNama'] = $alternatif['alternatif_nama'];
                                            $rank['alternatifKode'] = $alternatif['alternatif_kode'];
                                            array_push($ranks, $rank);
                                            ?>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="modalFilter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Filter Data Perhitungan</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form disini -->
                <form method="post" action="perhitunganFilter.php">
                    <div class="row mb-3">
                        <label for="perhitunganTanggal" class="col-sm-2 col-form-label">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="perhitunganTanggal" name="perhitunganTanggal" required>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary" name="filter">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- footer -->
<?php include '../blade/footer.php' ?>