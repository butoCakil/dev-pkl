<?php

sleep(2);
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');

function curl_get_contents($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

// $data = curl_get_contents("https://script.google.com/macros/s/AKfycbx3nYleaTnAO_nSGrI_idGl2_0-9FZ4WUyZJJyCpn3Lv8UZv3WW6UX8PIU7mdsyx_j9/exec");
$data = curl_get_contents("https://script.google.com/macros/s/AKfycbyWCIFv3jCyfWHxRbh5nUsoo46dGjQyo800A543dv3boG_A63O4KaJ2SaYDeF7tWk-l/exec");
$json = json_decode($data, TRUE);
// echo "$tanggal<br>";
// echo "<pre>";
// print_r($json['data']);
// echo "</pre>";
// die;

// Array untuk nama hari dalam bahasa Indonesia
$nama_hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
$nama_bulan = array(
    1 => "Januari",
    2 => "Februari",
    3 => "Maret",
    4 => "April",
    5 => "Mei",
    6 => "Juni",
    7 => "Juli",
    8 => "Agustus",
    9 => "September",
    10 => "Oktober",
    11 => "November",
    12 => "Desember"
);

$data_json = $json['data'];
$katakunci1 = "presensi#";
$katakunci2 = "Presensi#";
$katakunci3 = "PRESENSI#";
$katakunci4 = "PRESENSI #";
$katakunci5 = "Presensi #";
$katakunci6 = "presensi #";
$datawa = array();
$data_array = array();
$jml_data_json = count($data_json);

echo "Jml data: $jml_data_json<br>";
include "../koneksi.php";

$i = 0;
foreach ($data_json as $data) {
    $status = $data['status'];
    $message = $data['message'];
    $nomor_ = "62" . $data['from'];
    $media_ = $data['media'];

    $datawa[$i]['timestamp'] = str_replace(".000Z", "", str_replace("T", " ", $data['timestamp']));
    $timestamp = $datawa[$i]['timestamp'];
    $date = new DateTime($timestamp);
    $date->add(new DateInterval('PT7H'));
    $timestamp = $date->format('Y-m-d H:i:s');
    $_timestamp = $timestamp;
    
    // jika bukan data baru -> SKIP
            
    $timestamp = strtotime($timestamp);
    // Format tanggal dalam bahasa Indonesia (DD-MM-YYYY)
    $hari = date('w', $timestamp);
    $tanggal = date('d', $timestamp);
    $bulan = date('n', $timestamp);
    $tahun = date('Y', $timestamp);
    $jam = date('H:i:s', $timestamp);
            
    // Nama hari dan bulan dalam bahasa Indonesia
    $hari_indonesia = $nama_hari[$hari];
    $bulan_indonesia = $nama_bulan[$bulan];
        
    // Gabungkan nama hari, tanggal, bulan, tahun, dan jam dalam variabel pesan
    $pesan = "";

    // tulis message
    if ($status != "dibaca") {
        $simpan_pesan = mysqli_query($konek, "INSERT INTO `loadchat`(`nomor`, `pesan`, `media`, `status`, `timestamp`) VALUES ('$nomor_', '$message','$media_','diterima', '$_timestamp')");
      
        if (str_contains($message, $katakunci1) || str_contains($message, $katakunci2) || str_contains($message, $katakunci3) || str_contains($message, $katakunci4) || str_contains($message, $katakunci5) || str_contains($message, $katakunci6)) {
            $datawa[$i]['from'] = "62" . $data['from'];
            $datawa[$i]['message'] = $data['message'];
            $datawa[$i]['media'] = $data['media'];

            $explode_msg = explode("#", $data['message']);
            // $datawa[$i]['pesan'] = $explode_msg;

            $datawa[$i]['nis'] = str_replace(" ", "", @$explode_msg[1]);
            $datawa[$i]['ket'] = strtolower(str_replace(" ", "", @$explode_msg[2]));
            $datawa[$i]['catatan'] = @$explode_msg[3];

            $nomor = $datawa[$i]['from'];
            $media = $datawa[$i]['media'];
            $nis = $datawa[$i]['nis'];
            $ket = ucfirst($datawa[$i]['ket']);
            $catatan = $datawa[$i]['catatan'];

            // cari kode dudi

            $sql_dudi = mysqli_query($konek, "SELECT kode FROM duditerisi WHERE nis = '$nis'");

            $hasil_kode = mysqli_fetch_array($sql_dudi)['kode'];

            // data baru? berdasarkan NIS dan timestamp

            // cek tabel presensi hari ini
            $sql_cek_presensi = mysqli_query($konek, "SELECT * FROM presensi WHERE nis = '$nis' AND timestamp LIKE '%" . $_timestamp . "%'");
            
            if (mysqli_num_rows($sql_cek_presensi) > 0) {
                $pesan = "$nis telah melakukan presensi.";
            } else {
                // jika data baru
                // validasi pesan
                // ada nis

                if ($nis && $ket && $catatan && $media) {
                    // validasi NIS
                    $sql_validasi_NIS = mysqli_query($konek, "SELECT * FROM datasiswa WHERE nis = '$nis'");

                    if (mysqli_num_rows($sql_validasi_NIS) > 0) {
                        $hasil_validasi_NIS = mysqli_fetch_array($sql_validasi_NIS);

                        $nama_ = $hasil_validasi_NIS['nama'];
                        $kelas_ = $hasil_validasi_NIS['kelas'];
                        $gander_ = $hasil_validasi_NIS['gander'];
                        $jur_ = $hasil_validasi_NIS['jur'];

                        // jika data baru valid, SIMPAN DB presensi
                        $simpan_presensi = mysqli_query($konek, "INSERT INTO `presensi`(`timestamp`, `nis`, `kode`, `file`, `type`, `size`, `ket`, `jurnal`) VALUES ('$_timestamp','$nis','$hasil_kode','$media','image/jpeg','1024','$ket','$catatan')");

                        if ($simpan_presensi) {
                            // kirim balasan OK
                            // update nohp siswa jika bukan nomor pembimbing

                            // cek nomor
                            $sql_cek_nomor = mysqli_query($konek, "SELECT cp FROM datapembimbing WHERE cp = '$nomor'");

                            if (mysqli_num_rows($sql_cek_nomor) == 0) {
                                $update_nomor_siswa = mysqli_query($konek, "UPDATE `datasiswa` SET `nohp`='$nomor' WHERE `nis` = '$nis'");
                            }

                            $pesan = "Terimakasih, $nama_ ($nis) ($kelas_) ($gander_) Telah berhasil melakukan presensi.";
                        } else {
                            $pesan = "Oops, Maaf, ada kendala dalam melakukan presensi. Hubungi Admin Jurusan";
                        }
                    } else {
                        $pesan = "NIS: $nis tidak terdaftar";
                    }
                } else {
                    // jika data baru tidak valid kirim balasan
                    // balasan melengkapi sesuai format
                    if (!$nis) {
                        $pesan = "Presensi Belum berhasil. FORMAT presensi salah -> Periksa Kembali Penempatan NIS, Keterangan, dan Catatan dengan benar. Tolong ulangi lagi";
                    } elseif (!$ket) {
                        // ada ket
                        $pesan = "Presensi Belum berhasil. FORMAT presensi salah -> Periksa Kembali Penempatan NIS, Keterangan, dan Catatan dengan benar. Tolong ulangi lagi";
                    } elseif (!$catatan) {
                        // ada catatan
                        $pesan = "Presensi Belum berhasil. FORMAT presensi salah -> Periksa Kembali Penempatan NIS, Keterangan, dan Catatan dengan benar. Tolong ulangi lagi";
                    } elseif (!$media) {
                        $pesan = "Presensi Belum berhasil. Tolong ulangi lagi ya, dengan menyertakan Foto Selfie.";
                    } else {
                        $pesan = "Presensi Belum berhasil. FORMAT presensi salah -> presensi#nis#keterangan#catatan. Tolong ulangi lagi";
                    }
                }
            }

            echo "$pesan<br>";

            // $token = "qnNDCgY0F4F3MVna2LoF";
            $token = "@USo9EJ4cicpFQ1t9n0n";
            $pesan = "$pesan

_$hari_indonesia, $tanggal $bulan_indonesia $tahun, Pukul $jam WIB_
pkl.smknbansari.sch.id © " . date('Y');
            include "apiwa.php";
            
            sleep(1);
            
            echo "dapetnya: '" . $json['detail'] . "'<br>";

            if ($json['detail'] == 'success! message in queue') {
                echo 'Detail: Success!';
                echo "<br>";
                
                $nomorTelepon = substr($nomor, 2);
            
                echo "nomor: " . $nomor . "<br>";
                echo "nomorTelepon: " . $nomorTelepon . "<br>";
                
                // $scriptUrl = "https://script.google.com/macros/s/AKfycbz2kC7Tkl39XWxsrH8ONZzxZSy6WBd0hlY0HABZMxr55kxrdd25rD7H8fQ-lbTkVQj0/exec";
                // $url = $scriptUrl . "?nomor_telepon=" . $nomorTelepon;
                // echo $url . "<br>";
                
                // $response = @file_get_contents($url);
    
                // if ($response !== false) {
                //     echo $response;
                // } else {
                //     echo "GAGAL report: " . error_get_last()['message'];
                // }
                // ?nomor_telepon=82241863393
    
                $scriptUrl = "https://script.google.com/macros/s/AKfycbwYuBjV3V-JunSG1bwkU3FfWlUPH7bB0Q5pqoeT91yRgXEL0XEmJmsfnSxaiAteQgb4/exec";
                $url = $scriptUrl . "?nomor_telepon=" . $nomorTelepon;
    
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
    
                if ($response !== false) {
                    echo "Berhasil: " . $response;
                } else {
                    echo "GAGAL report: " . curl_error($ch);
                }
    
                curl_close($ch);
            } else {
                echo 'Detail: Error atau tidak ada data "success".';
            }
        } else {
            // Cek apakah string JSON mengandung karakter '#' atau kata "presensi"
            if (strpos($message, '#') !== false || stripos(strtolower($message), 'nis') !== false || stripos(strtolower($message), 'nis:') !== false || stripos(strtolower($message), 'nis :') !== false) {
                // Jika mengandung karakter '#' atau kata "presensi", set pesan kesalahan
                $pesan = "Jika anda akan melakukan presensi, Format anda belum tepat.
                
Balas dengan \"presensi\" (tanpa tanda petik) untuk mendapatkan petunjuk bagaimana melakukan presensi.";
                $token = "@USo9EJ4cicpFQ1t9n0n";
                $nomor = $nomor_;
            $pesan = "$pesan

_$hari_indonesia, $tanggal $bulan_indonesia $tahun, Pukul $jam WIB_
pkl.smknbansari.sch.id © " . date('Y');
            include "apiwa.php";
            
            $nomor = "";
            $nomor_ = "";
            }
        }
    }

    $explode_msg = "";
    $i++;
}

mysqli_close($konek);

// echo "<pre>";
// print_r($datawa);
// echo "</pre>";

// echo "<pre>";
// print_r($json['data']);
// echo "</pre>";
