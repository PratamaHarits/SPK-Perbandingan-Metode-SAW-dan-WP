<?php
// Koneksi
$conn = mysqli_connect("localhost", "root", "", "db_212321021");
// Cek
if (!$conn) {
    die("Gagal terkoneksi : " . mysqli_connect_error());
}
