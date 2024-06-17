<?php
if (@$_GET["token"]) {
    $token = @$_GET["token"];
    $admin = @$_GET["admin"];
    $aksi = @$_GET["aksi"];

    if ($token == "!2345" && $admin == "bennysurahman" && $aksi == "reset") {
        include "koneksi.php";

        // $delete = "DELETE FROM datadudi";
        $delete = "TRUNCATE TABLE datadudi";
        $kosongkan = mysqli_query($konek, $delete);

        // update DB dudi 25/07/2022
        $data = 'PT ASTANA INDRA KARYA;Sleman, DI Yogyakarta;Produksi Perangkat Jam Digital Internet;3;;Rekomendasi Bidang Assembly#RUMAH AKRILIK MAGELANG;magelang kota;Produksi Kerajinan Akrilik;3;;Rekomendasi Bidang Router, Bengkel dan Assembly#CV KAYUKI;temanggung;Produksi Kerajinan Kayu;2;2;Rekomendasi Siswa Bidang RND, Router, dan Bengkel#DTECH ENGINEERING;salatiga;Mesin CNC dan sparepart motor;2;;Bidang RND dan Milling#CV. EWOOD;Parakan, Temanggung;Service Komputer dan Printer;1;1;Rekomendasi Siswa Bidang Counter dan Service#GADGETS CELL & COM;Parakan, Temanggung;Service Smartphone dan Komputer;;4;Rekomendasi Siswa Bidang Counter dan Service#RASYA CELL & ACCESORIES;Parakan, Temanggung;Service dan Penjualan Smartphone;1;;Rekomendasi Siswa Bidang Counter, Service dan Pemasaran#GARASI AUDIO;Parakan, Temanggung;Audio dan asesoris mobil;2;2;Rekomendasi Siswa Bidang Service dan Pemasaran#TELKOM TEMANGGUNG CAB, PARAKAN;Parakan, Temanggung;Internet dan komunikasi;4;;Rekomendasi Siswa Bidang Network dan Pemasaran#GAVRIL CELL;Bansari, Temanggung;Service Smartphone dan Komputer;1;1;Rekomendasi Siswa Bidang Counter dan Service#ARZAM CELL;Parakan, Temanggung;Service Smartphone;2;;Rekomendasi Siswa Bidang Counter dan Pemasaran#TULUS SERVICE;Parakan, Temanggung;Penjualan dan Perbaikan Home App;2;2;Rekomendasi Siswa Bidang Service dan Pemasaran#ELCO JAYA ELEKTRO;Candiroto, Temanggung;Perbaikan Home App TV dan Sewa Audio;3;;Rekomendasi Siswa Bidang Service dan Audio#HERU ELEKTRONIK;Candiroto, Temanggung;Penjualan dan Perbaikan Home App;2;;Rekomendasi Siswa Bidang Service dan Pemasaran#COBRA TEKNIK;Bulu, Temanggung;Perbaikan Home App;2;;Rekomendasi Bidang Service#KELANA RIA SOUND SYSTEM DAN SERVICE;Kedu, Temanggung;Perbaikan Home App TV dan Sewa Audio;5;5;Rekomendasi Siswa Bidang Service dan Audio#VOLCANO AUDIO & KACA FILM;Temanggung, Temanggung;Audio Mobil dan Kaca Film;2;1;Rekomendasi Siswa Bidang Service dan Pemasaran#RND AUDIO VARIASI;Temanggung, Temanggung;Audio Mobil dan Kaca Film;2;1;Rekomendasi Siswa Bidang Service dan Pemasaran#IWAN SERVICE;Jumo, Temanggung;Perbaikan Home App;2;2;Rekomendasi Siswa Bidang Service#BIMA MUSIC;Wonosobo;Penjualan, Instalasi, dan Sewa Sound System;3;2;Rekomendasi Siswa Bidang Service dan Audio#ELBASS;Wonosobo;Penjualan, Instalasi, dan Elektronik dan Sewa Sound System;2;3;Rekomendasi Siswa Bidang Service dan Audio#ARKAN SERVICE ELEKTRONIK;Wonosobo;Penjualan dan Perbaikan Home App;1;2;Rekomendasi Siswa Bidang Service#PT. SUTANTO ARIFCHANDRA ELEKTRONIK (KITANI);Banyumas;Produsen Kabel dan Pertanian;5;;Segala Bidang Elektronika (Menunggu Konfirmasi Pihak KITANI)#RADJA AC;Purwokerto, Banyumas;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#CV BINTANG AIR CONDITIONER;Pekalongan;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#AGUNG ELEKTRO;Purwokerto, Banyumas;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#UD ROHMAN;Purwokerto, Banyumas;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#CV PERTAMA AC;Cilacap;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#CV BINTANG AIR CONDITIONER;Pekalongan;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#RIZKI BAROKAH TEKNIK;Pekalongan;Instalasi, Perawatan, dan Perbaikan AC;2;;Rekomendasi Siswa Bidang Service#PANDAWA JAYA ELEKTRO;Bantul, DI Yogyakarta;Perbaikan Home APP;3;2;Rekomendasi Siswa Bidang Service#UKSW;Salatiga;Jurusan Elektronika;1;2;Rekomendasi Bidang Assembly#PIPHO CELL;Ngadirejo;Service Smartphone;1;;Rekomendasi Siswa Bidang Counter';

        // isian DB
        // #NAMA DU/DI;Kuota;L;P;Alamat;Maps;Info Kos;Biaya Bimbingan; Biaya Hidup;Keterangan;Pembimbing

        // parsing data

        $pecahdata = explode("#", $data);

        // hitung data
        $jumlahdata = count($pecahdata);

        for ($i = 0; $i < $jumlahdata; $i++) {
            $simpandata = explode(";", $pecahdata[$i]);

            // print_r("<pre> " . $simpandata[0] . "</pre>");
            // print_r("<pre> " . $simpandata[1] . "</pre>");
            // print_r("<pre> " . $simpandata[2] . "</pre>");
            // print_r("<pre> " . $simpandata[3] . "</pre>");
            // print_r("<pre> " . $simpandata[4] . "</pre>");
            // print_r("<pre> " . $simpandata[5] . "</pre>");
            // print_r("<pre> " . $simpandata[6] . "</pre>");
            // print_r("<pre> " . $simpandata[7] . "</pre>");
            // print_r("<pre> " . $simpandata[8] . "</pre>");
            // print_r("<pre> " . $simpandata[9] . "</pre>");
            // print_r("<pre> " . $simpandata[10] . "</pre>");

            // NAMA DUDI	WILAYAH	BIDANG KERJA	"PERKIRAAN JUMLAH SISWA  YANG DAPAT DITEMPATKAN L/P"		KETERANGAN

            $namadudi = $simpandata[0];
            $alamat = $simpandata[1];
            // $kuota = $simpandata[1];
            $kuota_L = $simpandata[3];
            $kuota_P = $simpandata[4];
            $ket = $simpandata[2] . " - " . $simpandata[5];

            $kuota = (int)$kuota_L + (int)$kuota_P;

            // $map = $simpandata[5];
            // $kos = $simpandata[6];
            // $beabim = $simpandata[7];
            // $beahidup = $simpandata[8];
            // $pembimbing = $simpandata[10];
            // $nowa = $simpandata[11];

            // print_r("nama dudi : " . $namadudi . "<br>");
            // print_r("kuota : " . @$kuota . "<br>");
            // print_r("kuota L : " . @$kuota_L . "<br>");
            // print_r("kuota P : " . @$kuota_P . "<br>");
            // print_r("alamat : " . @$alamat . "<br>");
            // print_r("map : " . @$map . "<br>");
            // print_r("kos : " . @$kos . "<br>");
            // print_r("beabim : " . @$beabim . "<br>");
            // print_r("beahidup : " . @$beahidup . "<br>");
            // print_r("ket : " . @$ket . "<br>");
            // print_r("pembimbing : " . @$pembimbing . "<br>");


            // menghilangkan spasi di nama dudi
            $namadudi_temp = str_replace(" ", "", $namadudi);
            // jagikan huruf kapital
            $namadudi_temp = strtoupper($namadudi_temp);
            // mengambil 6 karakter huruger dari nama dudi
            $kode = substr($namadudi_temp, 0, 6);
            $kode = $kode . ($i + 1);


            // Prepared statement
            $stmt = mysqli_prepare($konek, "INSERT INTO `datadudi` (`namadudi`, `alamat`, `kuotacow`, `kuotacew`, `kuotatoal`, `kode`, `ket`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, 'prakerin')");

            // Bind parameter ke prepared statement
            mysqli_stmt_bind_param($stmt, "ssiiiss", $namadudi, $alamat, $kuota_L, $kuota_P, $kuota, $kode, $ket);

            // Eksekusi statement
            if (mysqli_stmt_execute($stmt)) {
                echo "<br>Data berhasil disimpan: " . htmlspecialchars($namadudi) . " " . htmlspecialchars($alamat) . " " . $kuota_L . " " . $kuota_P . " " . $kuota . " kode: " . htmlspecialchars($kode) . "<br>";
            } else {
                echo "<br>Data gagal disimpan: " . htmlspecialchars($namadudi) . " " . htmlspecialchars($alamat) . " " . $kuota_L . " " . $kuota_P . " " . $kuota . " kode: " . htmlspecialchars($kode) . "<br>";
                echo " - " . mysqli_stmt_error($stmt) . "<br>";
            }

            // Tutup statement
            mysqli_stmt_close($stmt);

            // Tutup koneksi
            mysqli_close($konek);
        }

        include "koneksi.php";

        $delete = "TRUNCATE TABLE duditerisi";
        $kosongkan = mysqli_query($konek, $delete);

        if ($kosongkan) {
            echo "<br>Berhasil Reset DB<br>";
        } else {
            echo "<br>Gagal reset data<br>";
        }

        mysqli_close($konek);
    } else {
        echo "Akses ditolak<br>token salah<br>admin tidak ada hak akses";
    }
} else {
    echo "Akses ditolak<br>minta token<br>admin";
}
