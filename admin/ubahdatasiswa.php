<?php
session_start();

if (@$_SESSION["admin"]) {

    if (@$_GET["akses"] == "ubahdatasiswa") {
        // echo "masuk ubahdatasiswa<br>";
        // printf("<pre>" . print_r(@$_GET) . "</pre>");

        $nis = isset($_GET["nis"]) ? $_GET["nis"] : null;
        $namasiswa = isset($_GET["nama"]) ? $_GET["nama"] : null;
        $kodedudi = isset($_GET["kodedudi"]) ? $_GET["kodedudi"] : null;

        include "../koneksi.php";

        $sql = "SELECT * FROM duditerisi WHERE nis = ?";
        $stmt = mysqli_prepare($konek, $sql);
        mysqli_stmt_bind_param($stmt, "s", $nis);
        mysqli_stmt_execute($stmt);

        $query = mysqli_stmt_get_result($stmt);
        $hasildata = mysqli_fetch_assoc($query);

        mysqli_stmt_close($stmt);

        if ($hasildata) {
            // echo "Ada data <br>";

            $sql = "SELECT * FROM datadudi WHERE kode = ?";
            $stmt = mysqli_prepare($konek, $sql);

            mysqli_stmt_bind_param($stmt, "s", $kodedudi);
            mysqli_stmt_execute($stmt);

            $query = mysqli_stmt_get_result($stmt);
            $hasildudi = mysqli_fetch_assoc($query);

            mysqli_stmt_close($stmt);


            $jur_dudi_pilih = $hasildudi["jur"];
            // $status_dudi_pilih = $hasildudi["status"];
            $nama_dudi_pilih = $hasildudi["namadudi"];
            $kode_dudi_pilih = $hasildudi["kode"];
            $alamat_dudi_pilih = $hasildudi["alamat"];
            $pembimbing = $hasildudi["pembimbing"];
            $kuota = $hasildudi["kuotatoal"];
            $kuota_L = $hasildudi["kuotacow"];
            $kuota_P = $hasildudi["kuotacew"];

            $nama_dudi_sebelumnya = $hasildata["namadudi"];
            $kode_dudi_sebelumnya = $hasildata["kode"];
            $gander = $hasildata["gander"];

            // hasil dudi sebelumnya
            $sql2 = "SELECT * FROM datadudi WHERE kode = '$kode_dudi_sebelumnya'";
            $query2 = mysqli_query($konek, $sql2);
            $hasildudi_sebelumnya = mysqli_fetch_assoc($query2);

            $kuota_dudi_sebelumnya = $hasildudi_sebelumnya["kuotatoal"];
            $kuota_L_sebelumnya = $hasildudi_sebelumnya["kuotacow"];
            $kuota_P_sebelumnya = $hasildudi_sebelumnya["kuotacew"];

            // cek gander siswa
            $kuota_gander = "";

            if (@$gander == "L") {
                $kuota_gander = "kuotacow";
                // kuota dudi sebelumnya + 1
                $kuota_dudi_sebelumnya = $kuota_dudi_sebelumnya + 1;
                $kuota_gander_sebelumnya = $kuota_L_sebelumnya + 1;

                $kuota = $kuota - 1;
                if ($kuota_L > 0) {
                    $kuota_pilih = $kuota_L - 1;
                }
            } else if (@$gander == "P") {
                $kuota_gander = "kuotacew";
                $kuota_dudi_sebelumnya = $kuota_dudi_sebelumnya + 1;
                $kuota_gander_sebelumnya = $kuota_P_sebelumnya + 1;

                $kuota = $kuota - 1;
                if ($kuota_P > 0) {
                    $kuota_pilih = $kuota_P - 1;
                }
            } else {
                $kuota_gander = "kuotatoal";
                $kuota_gander_sebelumnya = $kuota_dudi_sebelumnya + 1;

                $kuota = $kuota - 1;
            }

            // cek tok

            // update duditerisi
            $sql0 = "UPDATE duditerisi SET namadudi = ?, kode = ?, alamat = ?, pembimbing = ?, jur = ? WHERE nis = ?";
            $stmt0 = mysqli_prepare($konek, $sql0);

            // Bind parameter ke statement prepared
            mysqli_stmt_bind_param($stmt0, "ssssss", $nama_dudi_pilih, $kode_dudi_pilih, $alamat_dudi_pilih, $pembimbing, $jur_dudi_pilih, $nis);

            // Eksekusi statement prepared
            $result0 = mysqli_stmt_execute($stmt0);
            mysqli_stmt_close($stmt0);

            if ($query0) {
                // echo "Data duditerisi berhasil diubah <br>";

                // update kuota DU/DI sebelumnya +1
                $sql1 = "UPDATE `datadudi` SET `$kuota_gander`= '$kuota_gander_sebelumnya', `kuotatoal` = '$kuota_dudi_sebelumnya' WHERE kode = '$kode_dudi_sebelumnya'";
                $update_datadudi1 = mysqli_query($konek, $sql1);

                if ($update_datadudi1) {
                    $_SESSION["ok"] = "Data datadudi " . $namasiswa . " sebelumnya berhasil diubah" . " [0x00228]" . "<br>";
                } else {
                    $_SESSION["error"] = "Data datadudi " . $namasiswa . " gagal diubah" . " [0x00228]" . "<br>" . mysqli_error($konek);
                }

                // Kurangi Kuota DU/DI terpilih -1
                $sql2 = "UPDATE `datadudi` SET `$kuota_gander`= '$kuota_pilih', `kuotatoal` = '$kuota' WHERE kode = '$kode_dudi_pilih'";
                $update_datadudi2 = mysqli_query($konek, $sql2);

                if ($update_datadudi2) {
                    $_SESSION["ok"] = "Data datadudi '" . $namasiswa . "' terpilih berhasil diubah " . " [0x00229]" . "<br>";
                } else {
                    $_SESSION["error"] = "Data datadudi '" . $namasiswa . "' terpilih gagal diubah" . " [0x00229]" . "<br>" . mysqli_error($konek);
                }
            } else {
                $_SESSION["error"] = "Data duditerisi " . $namasiswa . " gagal diubah" . " [0x00230]" . "<br>" . mysqli_error($konek);
            }
        } else {
            // cek gander siswa
            // echo "NIS : " . $nis . "<br>";
// Pastikan parameter GET tersedia dan tidak kosong
            $nis = isset($_GET["nis"]) ? $_GET["nis"] : null;
            $kodedudi = isset($_GET["kodedudi"]) ? $_GET["kodedudi"] : null;

            // Ambil data dari tabel datasiswa
            if (!empty($nis)) {
                $sql2 = "SELECT * FROM datasiswa WHERE nis = ?";
                $stmt2 = mysqli_prepare($konek, $sql2);
                mysqli_stmt_bind_param($stmt2, "s", $nis);
                mysqli_stmt_execute($stmt2);
                $query2 = mysqli_stmt_get_result($stmt2);
                $hasilsiswa = mysqli_fetch_assoc($query2);
                mysqli_stmt_close($stmt2);
            }

            // Ambil data dari tabel datadudi
            if (!empty($kodedudi)) {
                $sql = "SELECT * FROM datadudi WHERE kode = ?";
                $stmt = mysqli_prepare($konek, $sql);
                mysqli_stmt_bind_param($stmt, "s", $kodedudi);
                mysqli_stmt_execute($stmt);
                $query = mysqli_stmt_get_result($stmt);
                $hasildudi = mysqli_fetch_assoc($query);
                mysqli_stmt_close($stmt);
            }

            // Assign variabel dari hasil query
            $jur_dudi_pilih = isset($hasildudi["jur"]) ? $hasildudi["jur"] : null;
            $nama_dudi_pilih = isset($hasildudi["namadudi"]) ? $hasildudi["namadudi"] : null;
            $kode_dudi_pilih = isset($hasildudi["kode"]) ? $hasildudi["kode"] : null;
            $alamat_dudi_pilih = isset($hasildudi["alamat"]) ? $hasildudi["alamat"] : null;
            $pembimbing = isset($hasildudi["pembimbing"]) ? $hasildudi["pembimbing"] : null;
            $kuota = isset($hasildudi["kuotatoal"]) ? $hasildudi["kuotatoal"] : null;
            $kuota_L = isset($hasildudi["kuotacow"]) ? $hasildudi["kuotacow"] : null;
            $kuota_P = isset($hasildudi["kuotacew"]) ? $hasildudi["kuotacew"] : null;

            $namasiswa = isset($hasilsiswa["nama"]) ? $hasilsiswa["nama"] : null;
            $kelas = isset($hasilsiswa["kelas"]) ? $hasilsiswa["kelas"] : null;
            $gander = isset($hasilsiswa["gander"]) ? $hasilsiswa["gander"] : null;

            // echo "Nama DU/DI: " . $nama_dudi_pilih . "<br>";
            // echo "Kode DU/DI: " . $kode_dudi_pilih . "<br>";
            // echo "Alamat DU/DI: " . $alamat_dudi_pilih . "<br>";
            // echo "Pembimbing: " . $pembimbing . "<br>";

            // echo "Nama SIswa: " . $namasiswa . "<br>";
            // echo "Kelas: " . $kelas . "<br>";
            // echo "Gander: " . $gander . "<br>";

            // Kueri SQL menggunakan prepared statement
            $sql = "INSERT INTO duditerisi (`namadudi`, `alamat`, `kode`, `nis`, `namasiswa`, `kelas`, `gander`, `pembimbing`, `jur`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($konek, $sql);

            // Bind parameter ke statement prepared
            mysqli_stmt_bind_param($stmt, "sssssssss", $nama_dudi_pilih, $alamat_dudi_pilih, $kode_dudi_pilih, $nis, $namasiswa, $kelas, $gander, $pembimbing, $jur_dudi_pilih);

            // Eksekusi statement prepared
            $insert = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($insert) {
                // Kurangi Kuota DU/DI terpilih -1 
                $kuota = $kuota - 1;

                if ($gander == "L") {
                    if ($kuota_L > 0) {
                        $kuota_L = $kuota_L - 1;
                    }
                } else if ($gander == "P") {
                    if ($kuota_P > 0) {
                        $kuota_P = $kuota_P - 1;
                    }
                }

                $sql = "UPDATE datadudi SET kuotatoal = '$kuota', kuotacow = '$kuota_L', kuotacew = '$kuota_P' WHERE kode = '$kode_dudi_pilih'";
                $update = mysqli_query($konek, $sql);

                if ($update) {
                    $_SESSION["ok"] = "Berhasil Update datadudi '" . $namasiswa . "' NIS:" . $nis . " [0x00231]";
                } else {
                    $_SESSION["error"] = "Gagal Update datadudi '" . $namasiswa . "' NIS:" . $nis . " [0x00231]<br>[" . mysqli_error($konek) . "]";
                }
            } else {
                $_SESSION["error"] = "Gagal Tambah isian pilih DU/DI" . " [0x00233] - " . mysqli_error($konek);
                // echo "Gagal Tambah isian pilih DU/DI";
            }
        }

        mysqli_close($konek);
    } else {
        $_SESSION["error"] = "Gagal memproses data. Refresh / ulangi lagi!" . " [0x00234]";
    }

    header("Location: datasiswa.php");
} else {
    $_SESSION["error"] = "Tidak ada hak akses untuk membuka halaman yang anda minta.<br>Hubungi Admin!" . " [0x00235]";

    // header("location: ../");

    // alert 
    echo "<script>
            alert('Tidak ada hak akses untuk membuka halaman yang anda minta. Hubungi Admin!" . " [0x00235]');
            window.location.href='../';
        </script>";
}
