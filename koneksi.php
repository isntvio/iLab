<?php
$host = "localhost";
$user = "root";      // default XAMPP
$pass = "";          // default kosong (kalau kamu pakai password MySQL, isi di sini)
$db   = "labjadwal";  // sesuaikan dengan nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
