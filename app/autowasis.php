<?php
$pesan_brotkes = @$_GET['brotkes'];
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
$jam = date("H:i:s");

if ((tgl($tanggal, "hari") != "Minggu") && (tgl($tanggal, "hari") != "Sabtu") && (tgl($tanggal, "hari") != "Jumat")) {

    $angka_tanggal = date('d');
    $angka_tahun = date('Y');
    $hari_ini = tgl($tanggal, "hari");
    $bulan_ini = tgl($tanggal, "bulan");

    include "../koneksi.php";

    // Melihat siswa bernomor WA

    $q_siswa = mysqli_query(
        $konek,
        "SELECT * FROM datasiswa WHERE (nohp IS NOT NULL) AND nohp <> '-'"
    );

    $kirim = false;
    $nis_siswa = "";
    $data = array();
    $no = 0;
    foreach ($q_siswa as $dp) {
        $data[] = $dp;
        $nis_siswa = $dp['nis'];
        $no2 = 0;

        // Lihat Presensi siswa berdasar NIS
        $q_presensi = mysqli_query($konek, "SELECT nis, timestamp, ket FROM presensi WHERE nis = '$nis_siswa' AND timestamp LIKE '%$tanggal%'");

        foreach ($q_presensi as $spp) {
            $data[$no]['presensi'] = $spp;

            $no2++;
        }

        if ($data[$no]['presensi']) {
            $kirim = false;
        } else {
            $kirim = true;
        }

        // jika belum absen
        if (@$kirim) {
            // Cari anggota yang lain
            // cari dudi siswa
            $q_dudi = mysqli_query($konek, "SELECT namadudi, kode, pembimbing FROM duditerisi WHERE nis = '$nis_siswa'");

            foreach ($q_dudi as $dd) {
                $data[$no]['info'] = $dd;

                $kode_dudi = $dd['kode'];

                // mencari siswa di dudi tersebut
                $q_siswa_di_dudi = mysqli_query($konek, "SELECT nis, namasiswa, kelas, gander, jur FROM duditerisi WHERE kode = '$kode_dudi'");

                $no3 = 0;
                foreach ($q_siswa_di_dudi as $dsdd) {
                    $data[$no]['info']['anggota'][] = $dsdd;

                    $nis_anggota = $dsdd['nis'];

                    // cari nis di presensi hari ini
                    $q_presensi = mysqli_query($konek, "SELECT nis, timestamp, ket FROM presensi WHERE nis = '$nis_anggota' AND timestamp LIKE '%$tanggal%'");

                    foreach ($q_presensi as $spp) {
                        $data[$no]['info']['anggota'][$no3]['presensi'] = $spp;
                    }
                    $no3++;
                }
            }
        }

        $no++;
    }

    // mulai membuat Pesan
    $pesan_1 = "";

    for ($i = 0; $i < count($data); $i++) {
        $nohp = $data[$i]['nohp'];

        $nama_siswa = $data[$i]['nama'];
        $nis_siswa = $data[$i]['nis'];
        $kelas_siswa = $data[$i]['kelas'];
        $jur_siswa = $data[$i]['jur'];
        $gander_siswa = $data[$i]['gander'];

        $presensi = array();
        $presensi = $data[$i]['presensi'];
        $anggota_didudi = array();
        $dikirim = false;

        if (!$presensi) {
            $dikirim = true;
            $info_presensi = array();
            $info_presensi = $data[$i]['info'];

            $nama_dudi = $info_presensi['namadudi'];
            $pembimbing_dudi = $info_presensi['pembimbing'];

            $anggota_didudi = $info_presensi['anggota'];

            $sub_pesan = "";

            for ($iii = 0; $iii < count($anggota_didudi); $iii++) {
                $nama_anggota = $anggota_didudi[$iii]['namasiswa'];
                $kelas_anggota = $anggota_didudi[$iii]['kelas'];
                $presensi_anggota = @$anggota_didudi[$iii]['presensi'];

                if ($presensi_anggota) {
                    $keterangan_anggota = $presensi_anggota['ket'];

                    if ($keterangan_anggota == "Masuk") {
                        $keterangan_anggota = "✅ *$keterangan_anggota*";
                    } elseif ($keterangan_anggota == "Izin") {
                        $keterangan_anggota = "ℹ️ *$keterangan_anggota*";
                    } elseif ($keterangan_anggota == "Sakit") {
                        $keterangan_anggota = "🤒 *$keterangan_anggota*";
                    } elseif ($keterangan_anggota == "Tidak_Masuk") {
                        $keterangan_anggota = "☕️ *Libur* ";
                    } elseif ($keterangan_anggota == "Absen") {
                        $keterangan_anggota = "👤 *Sudah $keterangan_anggota*";
                    }
                } else {
                    $keterangan_anggota = "🚫 Belum/Tidak Absen";
                }

                $sub_pesan = $sub_pesan . "
    " . ($iii + 1) . ". $nama_anggota: $keterangan_anggota";

                $keterangan_anggota = "";
            }

            $pesan_2 = "Daftar Siswa di $nama_dudi:$sub_pesan";

            $pesan_1 = "Pesan dari https://pkl.smknbansari.sch.id untuk:
*$nama_siswa* ($nis_siswa)($gander_siswa)
Kelas: $kelas_siswa
Tempat Prakerin: $nama_dudi
Guru Pembimbing: $pembimbing_dudi

dinyatakan bahwa, pada :
hari  $hari_ini, $angka_tanggal $bulan_ini $angka_tahun sampai Pukul $jam,
BELUM melakukan Absensi Prakerin.

Segera lakukan presensi. Lakukan konfirmasi ke nomor ini, jika ada kendala atau kesalahan pendataan dari aplikasi.

Link Absensi:
https://pkl.smknbansari.sch.id/presensi.php?nis=$nis_siswa&akses=presensi

$pesan_2

NB: 
⚠️ Jangan lupa mengisi JURNAL Harian.
🏋 Selalu jaga kesehatan️,  
🤝 Jaga hubungan baik dengan teman, rekan kerja, dan pembimbing,
dan Tetap Semangat! ✊🥳";
        }

        $pesan = $pesan_1;

        if ($pesan_brotkes) {
            $pesan = $pesan_brotkes;
            $dikirim = true;
        }

        if ($dikirim) {

            $nomor = $nohp;
            // $token = "qnNDCgY0F4F3MVna2LoF";
            $token = "@USo9EJ4cicpFQ1t9n0n";

            if (@$_GET['kirim'] == 'tidak') {
                echo "Batal kirim ke: $nohp<br>";
            } else {
                echo "Dikirim ke: $nohp<br>";
                include "apiwa.php";
            }
        } else {
            echo "Tidak dikirim ke: $nohp<br>";
        }

        echo "<pre>";
        print_r($pesan);
        echo "</pre>";
        echo "=======================<br>";
        
    }

    echo "<pre>";
    print_r($data);
    echo "</pre>";
} else {
    echo "Hari: " . tgl($tanggal, "hari") . "<br>";
}

if ($pesan_brotkes) {
    echo '<script>
        alert("Pesan dikirim");
        window.location.href = "../";
    </script>';
}

function tgl($tanggal, $_mode)
{
    if ($_mode == "hari") {
        $hari = date('l', strtotime($tanggal));
        $hari_indonesia = array(
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        );

        $hari_indonesia = $hari_indonesia[$hari];
        return $hari_indonesia;
    } else if ($_mode == "bulan") {
        // bulan indonesia
        $bulan = date('F', strtotime($tanggal));
        $bulan_indonesia = array(
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        );

        $bulan_indonesia = $bulan_indonesia[$bulan];
        return $bulan_indonesia;
    }
}
