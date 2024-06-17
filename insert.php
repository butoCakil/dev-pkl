<?php
if (@$_GET["token"]) {
    $token = @$_GET["token"];

    if ($token == "!234") {

        include "koneksi.php";

        $delete = "TRUNCATE TABLE datasiswa";
        $kosongkan = mysqli_query($konek, $delete);

        if ($kosongkan) {
            echo "<br>Berhasil Reset DB datasiswa<br>";
        } else {
            echo "<br>Gagal reset data datasiswa<br>";
            echo " - " . mysqli_error($konek) . "<br>";
        }

        $data = "2443;Ahmad Imam Mubin;L;X TE 1#2444;Ahmat Solechan;L;X TE 1#2445;Alfira Khoirul Nisa;P;X TE 1#2446;Alivia Suryani;P;X TE 1#2447;Allam Musyary Rosyiq;L;X TE 1#2448;Amanda Miftahul Rofiqoh;P;X TE 1#2449;Amelia Putri;P;X TE 1#2450;Ananda Egi Ardiansah;L;X TE 1#2451;Aras Chairil Anwar;L;X TE 1#2452;Azza Difla Ikhlila;P;X TE 1#2453;Brian Priambodo;L;X TE 1#2454;Damar Nugroho;L;X TE 1#2455;Dewi Mahfudhoh;P;X TE 1#2456;Dicky Satria Ramadhan;L;X TE 1#2457;Fachrizal Hanung;L;X TE 1#2458;Fatchur Rozak;L;X TE 1#2459;Fathul Hidayah;P;X TE 1#2460;Febra Arthur. C;L;X TE 1#2461;Firda Aulia Nisa;P;X TE 1#2462;Firgi Ahmad Pamungkas;L;X TE 1#2463;Galeh Hidayat;L;X TE 1#2464;Galuh Putra Malindo;L;X TE 1#2465;Gianindha Putri;P;X TE 1#2466;Govinda Martha Lyontina;P;X TE 1#2467;Hayu Pandan Pinaringsih;P;X TE 1#2468;Lendra Desmawan;L;X TE 1#2469;Lukita Heni;P;X TE 1#2470;Marsya Eka Yunita;P;X TE 1#2471;Nabila Tri Andini;P;X TE 1#2472;Nurma Seviranita;P;X TE 1#2473;Reiva Chelsy Aviska;P;X TE 1#2474;Renata Putri Aulia;P;X TE 1#2475;Rizal Setya Wardani;L;X TE 1#2476;Rohmat Sujati;L;X TE 1#2477;Ronal Eko Prabowo;L;X TE 1#2478;Sri Wahyuni;P;X TE 1#2479;Achmad Khalim;L;X TE 2#2480;Adi Prasetio;L;X TE 2#2481;Afifatun Indrayani;P;X TE 2#2482;Agil Reva Neza;L;X TE 2#2483;Citra Nur Chasanah;P;X TE 2#2485;Dea Velita;P;X TE 2#2486;Desi Nevika Putri;P;X TE 2#2487;Dewi Lufiani;P;X TE 2#2488;Erna Anggraeni;P;X TE 2#2489;Erni Aryati;P;X TE 2#2490;Eva Maulidya;P;X TE 2#2491;Faza Nur Ibad;L;X TE 2#2492;Fika Amalya Aziza;P;X TE 2#2493;Firdaus Satria Alfalah;L;X TE 2#2494;Galang Dwi Yuliyanto;L;X TE 2#2495;Intan Delia Paramita;P;X TE 2#2496;Khoirul Umam;L;X TE 2#2497;Latif Achmad Mubarok;L;X TE 2#2498;Laura Vidhi Adila;P;X TE 2#2499;Muhammad Ahgam Aprilianto;L;X TE 2#2500;Muhammad Haris Muyasar;L;X TE 2#2501;Muhammad Rizky Pratama;L;X TE 2#2502;Nabiil Daffa Kuncoro;L;X TE 2#2503;Nanda Bagus Winata;L;X TE 2#2504;Puji Indah Indiyani;P;X TE 2#2505;Radit Kurniawan;L;X TE 2#2506;Rafa Aditya Ferdinand;L;X TE 2#2507;Revalina Wahyu Ningtyas;P;X TE 2#2508;Satria Dimas Adi Putra;L;X TE 2#2509;Septyan Aditya Pradhana;L;X TE 2#2510;Shelfi Miranti Lerina;P;X TE 2#2511;Tiyas Yudhiyanto;L;X TE 2#2512;Varrell Zukynantha Aryusha;L;X TE 2#2513;Wahyu Sintya Musdhalifah;P;X TE 2#2514;Yuniar Eliana;P;X TE 2#2515;Agung Satriyo Pinandhito;L;X TE 3#2516;Ali Mashar;L;X TE 3#2517;Ana Khoirun Nisa;P;X TE 3#2518;Andri Wibowo;L;X TE 3#2519;Diandra Zahra Afita;P;X TE 3#2520;Dika Ranga Setiawan;L;X TE 3#2521;Dita Nur Laely;P;X TE 3#2522;Dutha Rustian Pradana;L;X TE 3#2523;Eko Budi Prasetiyo;L;X TE 3#2524;Eli Puspitasari;P;X TE 3#2525;Elsa Fadita;P;X TE 3#2526;Fatirul Fahmi;L;X TE 3#2527;Fauzi Cahyo Nugroho;L;X TE 3#2528;Fredi Firmansyah Exqi Saputra;L;X TE 3#2529;Giska Aulia Putri;P;X TE 3#2530;Icha Lifia Alfiani;P;X TE 3#2531;Kiara Rahelnia;P;X TE 3#2532;Laely Selfina Isnaeni;P;X TE 3#2533;Luluk Wiwidyanti;P;X TE 3#2534;Lutfi Setiawan;L;X TE 3#2535;Mahmud Shodiq;L;X TE 3#2536;Muchammad Zulman Ilhami;L;X TE 3#2537;Muhammad Adkha Sukuriyan;L;X TE 3#2538;Nurul Halimah;P;X TE 3#2539;Nurwanto;L;X TE 3#2540;Puput Septiyani;P;X TE 3#2541;Ria Seviana;P;X TE 3#2542;Rita Muyasyaroh;P;X TE 3#2543;Rizqi Rahman Biantoro;L;X TE 3#2544;Rizqi Zaenal Mahfut Al Huda;L;X TE 3#2545;Siva Hikmatun H.Y;P;X TE 3#2546;Stefis Jenis Fentura;L;X TE 3#2547;Steven Azril Bastian;L;X TE 3#2548;Vebri Prasetyo;L;X TE 3#2549;Yosi Indriyana Putri;P;X TE 3#2550;Zalikhah Rifda Adzra;P;X TE 3";

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

            $nis = mysqli_real_escape_string($konek, $simpandata[0]);
            $nama = mysqli_real_escape_string($konek, $simpandata[1]);
            $gander = mysqli_real_escape_string($konek, $simpandata[2]);
            $kelas = mysqli_real_escape_string($konek, $simpandata[3]);

            // Prepared statement
            $stmt = mysqli_prepare($konek, "INSERT INTO `datasiswa` (`nis`, `nama`, `kelas`, `gander`) VALUES (?, ?, ?, ?)");

            // Bind parameter ke prepared statement
            mysqli_stmt_bind_param($stmt, "ssss", $nis, $nama, $kelas, $gander);

            // Eksekusi statement
            if (mysqli_stmt_execute($stmt)) {
                echo "<br>Data berhasil disimpan: $nis $nama $gander $kelas";
            } else {
                echo "<br>Data gagal disimpan: $nis $nama $gander $kelas";
                echo " - " . mysqli_stmt_error($stmt) . "<br>";
            }

            // Tutup statement
            mysqli_stmt_close($stmt);

            // Tutup koneksi
            mysqli_close($konek);
        }
    } else {
        echo "Akses ditolak<br>token salah";
    }
} else {
    echo "Akses ditolak<br>minta token";
}
