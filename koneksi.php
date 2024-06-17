<?php

$host = "localhost";
// $user = "u0360177_esepro";
// $pass = "zg+dHrx69o8R";
// $dbs = "u0360177_dudi";
$user = "root";
$pass = "";
$dbs = "dudi";

$konek = mysqli_connect($host, $user, $pass, $dbs);

if (!$konek) {
    echo ("Gagal Konek database bossku!");
    $pesan = "Gagal Konek database bossku!";
    // include('../errors/error500.php');
    die;
} else {
    // echo "konek db";
}
