<?php
session_start();
if (@$_SESSION["admin"] == "admin") {

if (@$_POST["hapusdudisiswa"] == "hapusdudisiswa") {
    $nis = $_POST["nis"];

    include "../koneksi.php";

    $qq = "SELECT * FROM duditerisi WHERE nis = '$nis'";
    $q = mysqli_query($konek, $qq);
    if (mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $kode = $data["kode"];
        $gander = $data["gander"];

        // hapus dari duditerisi
        $sql = "DELETE FROM duditerisi WHERE nis = '$nis'";
        $hapus = mysqli_query($konek, $sql);

        if ($hapus) {
            // cari di datadudi
            $sql = "SELECT * FROM datadudi WHERE kode = '$kode'";
            $query = mysqli_query($konek, $sql);
            $hasildudi = mysqli_fetch_assoc($query);

            $kuota = $hasildudi["kuotatoal"];
            $kuota_L = $hasildudi["kuotacow"];
            $kuota_P = $hasildudi["kuotacew"];

            if ($gander == "L") {
                $kuota_gander = "kuotacow";
                $kuota_gander_sebelumnya = $kuota_L + 1;
            } else if ($gander == "P") {
                $kuota_gander = "kuotacew";
                $kuota_gander_sebelumnya = $kuota_P + 1;
            }

            $kuota = $kuota + 1;

            // update datadudi
            $sql = "UPDATE datadudi SET $kuota_gander = $kuota_gander_sebelumnya, kuotatoal = $kuota WHERE kode = '$kode'";
            $update = mysqli_query($konek, $sql);

            if ($update) {
                $_SESSION["ok"] = "Berhasil Hapus datadudi NIS:" . $nis . " [0x10031]<br>";
            } else {
                $_SESSION["error"] = "Gagal Hapus datadudi NIS:" . $nis . " [0x10031]<br>";
            }
        } else {
            $_SESSION["error"] = "Gagal hapus dari duditerisi [0x10032]<br>";
        }
    } else {
        $_SESSION["error"] = "NIS: " . $nis . "tidak ada dalam 'duditerisi' [0x10033]<br>";
    }
} else {
    $_SESSION["error"] = "nggak ada data untuk diproses [0x10034]";
}

// redirect
header("location: datasiswa.php");
} else {
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../admin';
        </script>";
}