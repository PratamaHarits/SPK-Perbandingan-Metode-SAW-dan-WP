<?php
include '../tools/connection.php';

if (isset($_POST['save'])) {
    $altKode = $_POST['altKode'];
    $altNama = $_POST['altNama'];

    $altTanggal = $_POST['altTanggal'];

    $query = $conn->query("INSERT INTO ta_alternatif(alternatif_kode,alternatif_nama,alternatif_tanggal)VALUES('$altKode','$altNama','$altTanggal')");;

    if ($query == True) {
        echo "<script>
                alert('Data Berhasil Disimpan');
                window.location='alternatifView.php'
                </script>";
    } else {
        die('MySQL error : ' . mysqli_errno($conn));
    }
}
