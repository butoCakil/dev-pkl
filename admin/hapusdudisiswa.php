<?php
session_start();

// Pastikan user memiliki akses sebagai admin
if ($_SESSION["admin"] == "admin") {
    // Jika form untuk menghapus data di-post
    if (isset($_POST["hapusdudisiswa"]) && $_POST["hapusdudisiswa"] == "hapusdudisiswa") {
        $nis = $_POST["nis"];

        include "../koneksi.php";

        // Prepared statement untuk mengambil data dari duditerisi
        $sql_select_duditerisi = "SELECT kode, gander FROM duditerisi WHERE nis = ?";
        $stmt_select_duditerisi = mysqli_prepare($konek, $sql_select_duditerisi);
        mysqli_stmt_bind_param($stmt_select_duditerisi, "s", $nis);
        mysqli_stmt_execute($stmt_select_duditerisi);
        $result_select_duditerisi = mysqli_stmt_get_result($stmt_select_duditerisi);

        if (mysqli_num_rows($result_select_duditerisi) > 0) {
            $data = mysqli_fetch_assoc($result_select_duditerisi);
            $kode = $data["kode"];
            $gander = $data["gander"];

            // Prepared statement untuk menghapus dari duditerisi
            $sql_delete_duditerisi = "DELETE FROM duditerisi WHERE nis = ?";
            $stmt_delete_duditerisi = mysqli_prepare($konek, $sql_delete_duditerisi);
            mysqli_stmt_bind_param($stmt_delete_duditerisi, "s", $nis);
            $hapus = mysqli_stmt_execute($stmt_delete_duditerisi);

            if ($hapus) {
                // Prepared statement untuk mencari di datadudi
                $sql_select_datadudi = "SELECT kuotatoal, kuotacow, kuotacew FROM datadudi WHERE kode = ?";
                $stmt_select_datadudi = mysqli_prepare($konek, $sql_select_datadudi);
                mysqli_stmt_bind_param($stmt_select_datadudi, "s", $kode);
                mysqli_stmt_execute($stmt_select_datadudi);
                $result_datadudi = mysqli_stmt_get_result($stmt_select_datadudi);
                $hasildudi = mysqli_fetch_assoc($result_datadudi);

                $kuota = $hasildudi["kuotatoal"];
                $kuota_L = $hasildudi["kuotacow"];
                $kuota_P = $hasildudi["kuotacew"];

                // Update kuota berdasarkan jenis kelamin
                if ($gander == "L") {
                    $kuota_gander = "kuotacow";
                    $kuota_gander_sebelumnya = $kuota_L + 1;
                } else if ($gander == "P") {
                    $kuota_gander = "kuotacew";
                    $kuota_gander_sebelumnya = $kuota_P + 1;
                }

                $kuota = $kuota + 1;

                // Prepared statement untuk update datadudi
                $sql_update_datadudi = "UPDATE datadudi SET $kuota_gander = ?, kuotatoal = ? WHERE kode = ?";
                $stmt_update_datadudi = mysqli_prepare($konek, $sql_update_datadudi);
                mysqli_stmt_bind_param($stmt_update_datadudi, "dds", $kuota_gander_sebelumnya, $kuota, $kode);
                $update = mysqli_stmt_execute($stmt_update_datadudi);

                if ($update) {
                    $_SESSION["ok"] = "Berhasil Hapus datadudi NIS: $nis [0x10031]<br>";
                } else {
                    $_SESSION["error"] = "Gagal Hapus datadudi NIS: $nis [0x10031]<br>";
                }
            } else {
                $_SESSION["error"] = "Gagal hapus dari duditerisi [0x10032]<br>";
            }
        } else {
            $_SESSION["error"] = "NIS: $nis tidak ada dalam 'duditerisi' [0x10033]<br>";
        }

        // Tutup prepared statement
        mysqli_stmt_close($stmt_select_duditerisi);
        mysqli_stmt_close($stmt_delete_duditerisi);
        mysqli_stmt_close($stmt_select_datadudi);
        mysqli_stmt_close($stmt_update_datadudi);

        mysqli_close($konek);
    } else {
        $_SESSION["error"] = "Tidak ada data untuk diproses [0x10034]";
    }

    // Redirect ke halaman datasiswa.php
    header("location: datasiswa.php");
    exit(); // Pastikan tidak ada output lain sebelum header redirect
} else {
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../admin';
          </script>";
}
