<?php
// koneksi
include '../tools/connection.php';

// header
include '../blade/header.php';

// Ambil data dari rankingFilter.php
if (isset($_POST['cetak'])) {
    $pimpinan = $_POST['pimpinan'];
    $perbandinganTanggal = $_POST['perbandinganTanggal'];

    // periode tahun ini dan berikutnya
    $tanggalBaru_ = new DateTime($perbandinganTanggal);
    $rankingTanggalSekarang = $tanggalBaru_->format('d-m-Y');


    $tanggalBaru = new DateTime($perbandinganTanggal);
    $tanggalBaru->modify('+1 year');
    $rankingTanggalNanti = $tanggalBaru->format('d-m-Y');
}
?>

<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-10">

        <!-- kop surat -->
        <p class="text-center fw-bold m-0">PT FAMILY RAYA CRF</p>
        <p class="text-center m-0">Jl. Gurun Laweh No.17, Kec. Lubuk Begalung, Kota Padang, Sumatera Barat</p>
        <p class="text-center m-0">Telepon 0751-22644</p>
        <hr>

        <!-- isi surat -->
        <p class="text-center fw-bold">Laporan Perangkingan Kinerja Pegawai</p>
        <p class="text-center"> Periode <?= $rankingTanggalSekarang; ?> s/d <?= $rankingTanggalNanti; ?></p>
        <p class="text-justify">Berdasarkan hasil pengolahan data dengan menggunakan beberapa kriteria yang sudah ditentukan dan dengan mengimplementasikan metode Simple Additive Weighting (SAW) dan metode Weighted Product (WP), maka menghasilkan perangkingan sebagai berikut : </p>

        <?php
        $rankSAW = array();
        $array_vector_si = array();
        $rankWP = array();
        ?>

        <!-- SAW -->

        <!-- <p class="text-center fw-bold">Tabel Hasil Normalisasi Metode SAW</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Alternatif</th>
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    $kriteriaRows = mysqli_num_rows($data);
                    ?>
                    <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>

                </tr>
                <tr class="table-info">
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    while ($kriteria = $data->fetch_assoc()) { ?>
                        <td><?= $kriteria['kriteria_nama']; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perbandinganTanggal' ORDER BY alternatif_kode");
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
        </table> -->

        <!-- <p class="text-center fw-bold">Hasil Preferensi</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Alternatif</th>
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    $kriteriaRows = mysqli_num_rows($data);
                    ?>
                    <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                    <th rowspan="2">Total Preferensi</th>

                </tr>
                <tr class="table-info">
                    <?php
                    $data = $conn->query("SELECT * FROM ta_kriteria");
                    while ($kriteria = $data->fetch_assoc()) { ?>
                        <td><?= $kriteria['kriteria_nama']; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $data = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perbandinganTanggal' ORDER BY alternatif_kode");
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

                        <?php
                        // masukan  nilai totalPreferensi, nama_alternatif, kode_alternatif ke dalam array $rankSAW
                        $rank_saw['totalPreferensi'] = $totalPreferensi;
                        $rank_saw['alternatifNama'] = $alternatif['alternatif_nama'];
                        $rank_saw['alternatifKode'] = $alternatif['alternatif_kode'];
                        array_push($rankSAW, $rank_saw);
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table> -->

        <div class="row mt-3">
            <div class="col-1"></div>
            <div class="col-10">
                <p class="text-center fw-bold">Tabel Hasil Metode SAW</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Kode Alternatif</th>
                            <th>Nama Alternatif</th>
                            <th>Nilai SAW</th>
                            <th>Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rangking = 1;
                        $total_nilai_saw = 0;
                        $looping = 1;
                        $total_looping = 0;
                        rsort($rankSAW);
                        foreach ($rankSAW as $saw) {
                        ?>
                            <tr>
                                <?php $looping ?>
                                <td><?= $rangking++; ?></td>
                                <td><?= $saw['alternatifKode']; ?></td>
                                <td><?= $saw['alternatifNama']; ?></td>
                                <td><?= number_format($saw['totalPreferensi'], 2); ?></td>
                                <td><?= ($rangking <= 2) ? 'Direkomendasikan' : 'Tidak Direkomendasikan'; ?></td>
                            </tr>
                            <?php
                            $total_looping = $total_looping + $looping;
                            $total_nilai_saw = $total_nilai_saw + $saw['totalPreferensi'];
                            $nilaiTSI_saw = 100 - ($total_nilai_saw / $total_looping) / 100;
                            ?>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-1"></div>
        </div>


        <!-- WP -->

        <!-- <p class="text-center fw-bold">Tabel Perubahan Kriteria</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
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
        </table> -->

        <!-- <p class="text-center fw-bold">Tabel Nilai Vector Si</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Alternatif</th>
                    <?php
                    $query_kriteria = $conn->query("SELECT * FROM ta_kriteria");
                    $kriteriaRows = mysqli_num_rows($query_kriteria);
                    ?>
                    <th colspan="<?= $kriteriaRows; ?>">Nama Kriteria</th>
                    <th rowspan="2">Nilai Vektor Si</th>

                </tr>
                <tr class="table-info">
                    <?php
                    $query_alternatif = $conn->query("SELECT * FROM ta_kriteria");
                    while ($kriteria = $query_alternatif->fetch_assoc()) { ?>
                        <td><?= $kriteria['kriteria_nama']; ?></td>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $query_alternatif = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perbandinganTanggal' ORDER BY alternatif_kode");
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
        </table> -->

        <!-- <p class="text-center fw-bold">Tabel Nilai Vector Vi</p>
        <table class="table table-striped table-bordered">
            <thead>
                <tr class="table-info">
                    <th>No</th>
                    <th>Nama Alternatif</th>
                    <th>Nilai Vektor Si</th>
                    <th>Total Nilai Vektor Si</th>
                    <th>Nilai Vektor Vi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query_alternatif = $conn->query("SELECT * FROM ta_alternatif WHERE alternatif_tanggal = '$perbandinganTanggal' ORDER BY alternatif_kode");
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
                        array_push($rankWP, $rank);
                        ?>

                    </tr>
                <?php } ?>
            </tbody>
        </table> -->

        <div class="row mt-3">
            <div class="col-1"></div>
            <div class="col-10">
                <p class="text-center fw-bold">Tabel Hasil Metode WP</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ranking</th>
                            <th>Kode Alternatif</th>
                            <th>Nama Alternatif</th>
                            <th>Nilai WP</th>
                            <th>Keputusan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ranking = 1;
                        $total_nilai_wp = 0;
                        $looping = 1;
                        $total_looping = 0;
                        rsort($rankWP);
                        foreach ($rankWP as $wp) {
                        ?>
                            <tr>
                                <?php $looping ?>
                                <td><?= $ranking++; ?></td>
                                <td><?= $wp['alternatifKode']; ?></td>
                                <td><?= $wp['alternatifNama']; ?></td>
                                <td><?= number_format($wp['nilaiWP'], 2); ?></td>
                                <td><?= ($ranking <= 2) ? 'Direkomendasikan' : 'Tidak Direkomendasikan'; ?></td>
                            </tr>
                            <?php
                            $total_looping = $total_looping + $looping;
                            $total_nilai_wp = $total_nilai_wp + $wp['nilaiWP'];
                            $nilaiTSI_wp = 100 - ($total_nilai_wp / $total_looping) / 100;
                            ?>
                        <?php
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-1"></div>
        </div>
        <p class="text-left fw-bold">Kesimpulan :</p>
        <?php
        if ($nilaiTSI_saw > $nilaiTSI_wp) {
            echo 'Berdasarkan perhitungan tingkat akurasi didapat nilai presentase metode SAW yaitu ' . $nilaiTSI_saw . '% dan metode WP yaitu ' . $nilaiTSI_wp . '%. Maka dapat disimpulkan metode SAW lebih baik dari metode WP';
        } else {
            echo 'Berdasarkan perhitungan tingkat akurasi didapat nilai presentase metode SAW yaitu ' . $nilaiTSI_saw . '% dan metode WP yaitu ' . $nilaiTSI_wp . '%. Maka dapat disimpulkan metode WP lebih baik dari metode SAW';
        }
        ?>

        <br><br>

        <p style=" text-align: right;">Padang, <?php echo date("d/m/Y") ?></p><br><br>
        <p style=" text-align: right;">( <?= $pimpinan; ?> )</p>

    </div>
    <div class="col-lg-1"></div>
</div>

<script>
    window.print();
</script>