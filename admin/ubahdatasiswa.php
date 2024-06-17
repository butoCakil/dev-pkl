<?php
session_start();

if (@$_SESSION["admin"]) {

    if (@$_GET["akses"] == "ubahdatasiswa") {
        // echo "masuk ubahdatasiswa<br>";
        // printf("<pre>" . print_r(@$_GET) . "</pre>");

        $nis = @$_GET["nis"];
        $namasiswa = @$_GET["nama"];
        $kodedudi = @$_GET["kodedudi"];

        // print_r("NIS : " . $nis . "<br>");
        // print_r("Nama Siswa : " . $namasiswa . "<br>");
        // print_r("Kode DU/DI : " . $kodedudi . "<br>");

        // cek ke duditerisi
        include "../koneksi.php";

        $sql = "SELECT * FROM duditerisi WHERE nis = '$nis'";
        $query = mysqli_query($konek, $sql);
        $hasildata = mysqli_fetch_assoc($query);

        if ($hasildata) {
            // echo "Ada data <br>";

            // update duditerisi
            $sql = "SELECT * FROM datadudi WHERE kode = '$kodedudi'";
            $query = mysqli_query($konek, $sql);
            $hasildudi = mysqli_fetch_assoc($query);

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

            // echo "Nama DU/DI: " . $nama_dudi_pilih . "<br>";
            // echo "Kode DU/DI: " . $kode_dudi_pilih . "<br>";
            // echo "Alamat DU/DI: " . $alamat_dudi_pilih . "<br>";
            // echo "Pembimbing: " . $pembimbing . "<br>";
            // echo "<br>";
            // echo "Nama dudi sebelumnya: " . $nama_dudi_sebelumnya . "<br>";
            // echo "Kode Dudi sebelumnya: " . $kode_dudi_sebelumnya . "<br>";
            // echo "Gander: " . $gander . "<br>";

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
            // echo "<br>";
            // echo "Kuota gander siswa: " . $kuota_gander . "<br>";
            // echo "Kuota dudi sebelumnya: " . $kuota_dudi_sebelumnya . "<br>";
            // echo "Kuota gander sebelumnya: " . $kuota_gander_sebelumnya . "<br>";
            // echo "Kuota: " . $kuota . "<br>";
            // echo "Kuota L: " . $kuota_L . "<br>";
            // echo "Kuota P: " . $kuota_P . "<br>";
            // echo "Kuota pilih: " . $kuota_pilih . "<br>";

            // update duditerisi
            $sql0 = "UPDATE duditerisi SET namadudi = '$nama_dudi_pilih', kode = '$kode_dudi_pilih', alamat = '$alamat_dudi_pilih', pembimbing = '$pembimbing', jur = '$jur_dudi_pilih' WHERE nis = '$nis'";
            $query0 = mysqli_query($konek, $sql0);

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
            $sql2 = "SELECT * FROM datasiswa WHERE nis = '$nis'";
            $query = mysqli_query($konek, $sql2);
            $hasilsiswa = mysqli_fetch_assoc($query);

            $sql = "SELECT * FROM datadudi WHERE kode = '$kodedudi'";
            $query = mysqli_query($konek, $sql);
            $hasildudi = mysqli_fetch_assoc($query);

            $jur_dudi_pilih = @$hasildudi["jur"];
            // $status_dudi_pilih = @$hasildudi["status"];
            $nama_dudi_pilih = @$hasildudi["namadudi"];
            $kode_dudi_pilih = @$hasildudi["kode"];
            $alamat_dudi_pilih = @$hasildudi["alamat"];
            $pembimbing = @$hasildudi["pembimbing"];
            $kuota = @$hasildudi["kuotatoal"];
            $kuota_L = @$hasildudi["kuotacow"];
            $kuota_P = @$hasildudi["kuotacew"];

            $namasiswa = @$hasilsiswa["nama"];
            $kelas = @$hasilsiswa["kelas"];
            $gander = @$hasilsiswa["gander"];

            // echo "Nama DU/DI: " . $nama_dudi_pilih . "<br>";
            // echo "Kode DU/DI: " . $kode_dudi_pilih . "<br>";
            // echo "Alamat DU/DI: " . $alamat_dudi_pilih . "<br>";
            // echo "Pembimbing: " . $pembimbing . "<br>";

            // echo "Nama SIswa: " . $namasiswa . "<br>";
            // echo "Kelas: " . $kelas . "<br>";
            // echo "Gander: " . $gander . "<br>";

            // insert duditerisi
            $sql = "INSERT INTO duditerisi (`namadudi`, `alamat`, `kode`, `nis`, `namasiswa`, `kelas`, `gander`, `pembimbing`, `jur`) VALUES ('$nama_dudi_pilih', '$alamat_dudi_pilih', '$kode_dudi_pilih', '$nis', '$namasiswa', '$kelas', '$gander', '$pembimbing', '$jur_dudi_pilih')";
            $insert = mysqli_query($konek, $sql);

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
