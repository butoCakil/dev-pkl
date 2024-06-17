<?php
if(@$_GET['pass'] == "!234"){
include "../koneksi.php";

$sql_datadudi = mysqli_query($konek, "SELECT * FROM datadudi");

$datadudi = array();

foreach ($sql_datadudi as $data) {
    $kode = $data['kode'];
    $pembimbing = $data['pembimbing'];

    $insert = mysqli_query($konek, "UPDATE `duditerisi` SET `pembimbing`= '$pembimbing' WHERE kode = '$kode'");

    if ($insert) {
        echo "Berhasil memperbarui Pembimbing " . $kode . " menjadi " . $pembimbing;
    } else {
        echo "Gagal memperbagarui<br>" . mysqli_error($konek);
    }

    echo "<br>";
}
} else{
    echo "password ditolak";
}