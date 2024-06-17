<?php
if (@$_GET['token'] == '!234') {
    include "../koneksi.php";

    // ambil data siswa 
    $sql_data_siswa = mysqli_query($konek, "SELECT * FROM datasiswa");

    foreach ($sql_data_siswa as $dts) {
        $data_siswa[] = $dts;

        $nis = $dts['nis'];

        // ambil data dudi
        $sql_dudi_terisi = mysqli_query($konek, "SELECT * FROM duditerisi WHERE nis = '$nis'");

        foreach ($sql_dudi_terisi as $dtdt) {
            $kode_dudi = $dtdt['kode'];
        }

        // masukkan ke presensi
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $timestamp = date('Y-m-d H:i:s');

        $tanggalSaatIni = "2023-09-18"; // Tanggal awal dalam format Y-m-d
        $jumlahHariDitambahkan = 5; // Jumlah hari yang ingin ditambahkan

        for ($i = 0; $i < $jumlahHariDitambahkan; $i++) {
            $tanggalHasil = date("Y-m-d", strtotime("$tanggalSaatIni +$i day"));
            echo "Hari ke-$i: $tanggalHasil<br>";

            $timestamp = date('Y-m-d H:i:s', strtotime($tanggalHasil . " 07:30:00"));

            echo "Timestamp : $timestamp<br>";
            echo "NIS: $nis<br>";
            echo "KODE: $kode_dudi<br>";

            // cek udah ada presensi di tanggal itu belum?

            $sql_cek = mysqli_query($konek, "SELECT * FROM presensi WHERE nis = '$nis' AND timestamp LIKE '%$tanggalHasil%'");

            if (mysqli_num_rows($sql_cek) > 0) {
                echo "Eh sudah presensi<br>";
            } else {
                $sql_presensi = mysqli_query($konek, "INSERT INTO `presensi`(`timestamp`, `nis`, `kode`, `file`, `type`, `size`, `ket`, `jurnal`, `status`, `status2`) VALUES ('$timestamp','$nis','$kode_dudi','','image/jpeg','1023','Masuk','Mengikuti Tes Sumatif Tengah Semester','','')");

                if ($sql_presensi) {
                    echo "Berhasil<br>";
                } else {
                    echo "ERROR: <br>" . mysqli_error($konek);
                }

                // echo "Belum<br>";
            }
            echo "<br>";
        }

        echo "SELESAI<br>";
        echo "<br>";
    }

    // echo "<pre>";
    // print_r($data_siswa);
    // echo "</pre>";
} else {
    echo "wkwkwkwkwkwk ora iso<br>";
}
