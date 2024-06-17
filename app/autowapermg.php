<?php
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
// $tanggal = '2023-06-13';

$tanggal_min_1 = date('Y-m-d', strtotime('-1 days', strtotime($tanggal)));
$tanggal_min_2 = date('Y-m-d', strtotime('-2 days', strtotime($tanggal)));
$tanggal_min_3 = date('Y-m-d', strtotime('-3 days', strtotime($tanggal)));
$tanggal_min_4 = date('Y-m-d', strtotime('-4 days', strtotime($tanggal)));
$tanggal_min_5 = date('Y-m-d', strtotime('-5 days', strtotime($tanggal)));
$tanggal_min_6 = date('Y-m-d', strtotime('-6 days', strtotime($tanggal)));
$tanggal_min_7 = date('Y-m-d', strtotime('-7 days', strtotime($tanggal)));

$array_tgl = array(
    $tanggal_min_7,
    $tanggal_min_6,
    $tanggal_min_5,
    $tanggal_min_4,
    $tanggal_min_3,
    $tanggal_min_2,
    $tanggal_min_1,
    // $tanggal
);

// echo '<pre>';
// print_r($array_tgl);
// echo '</pre>';


$tahun = date('Y');
$jam = date('H.i.s');

include "../koneksi.php";

// cari dudi dan pembimbing
$query_pembimbing = mysqli_query($konek, "SELECT nama FROM datapembimbing");

$data_pembimbing = array();
$no = 0;

while ($hasil_pembimbing = mysqli_fetch_array($query_pembimbing)) {
    $data_pembimbing[$no]['pembimbing'] = $hasil_pembimbing['nama'];
    $_data_pemb = $data_pembimbing[$no]['pembimbing'];

    // echo $no + 1 . ". " . $_data_pemb;
    // echo "<br>";

    $query_dudi_pembimbing = mysqli_query($konek, "SELECT namadudi, kota, kode, nowa, map, jur FROM datadudi WHERE pembimbing = '$_data_pemb'");

    $nno = 0;
    while ($hasil_data_pembimbing = mysqli_fetch_array($query_dudi_pembimbing)) {
        $data_pembimbing[$no][$nno] = $hasil_data_pembimbing;

        $kode_dudi = $hasil_data_pembimbing['kode'];

        // echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        // echo $nno + 1 . ". " . $kode_dudi;
        // echo "<br>";

        // cari siswa sesuai pembimbing

        $query_siswa_pembimbing = mysqli_query($konek, "SELECT kode, nis, namasiswa, kelas, gander, jur FROM `duditerisi` WHERE `kode` = '$kode_dudi'");


        $num = 0;

        while ($hasil_siswa_pembimbing = mysqli_fetch_array($query_siswa_pembimbing)) {
            $data_pembimbing[$no][$nno]['siswa']['nis'][] = $hasil_siswa_pembimbing['nis'];
            $data_pembimbing[$no][$nno]['siswa']['namasiswa'][] = $hasil_siswa_pembimbing['namasiswa'];
            $data_pembimbing[$no][$nno]['siswa']['gander'][] = $hasil_siswa_pembimbing['gander'];
            $data_pembimbing[$no][$nno]['siswa']['kelas'][] = $hasil_siswa_pembimbing['kelas'];

            $num++;
        }

        // cari siswa di presensi

        for ($tgl = 0; $tgl < count($array_tgl); $tgl++) {
            $query_siswa_presensi = mysqli_query($konek, "SELECT timestamp, nis, ket, jurnal FROM presensi WHERE kode = '$kode_dudi' AND timestamp LIKE '%" . $array_tgl[$tgl] . "%'");
            $nnoo = 0;
            while ($hasil_siswa_presensi = mysqli_fetch_array($query_siswa_presensi)) {
                // $data_pembimbing[$no][$nno][$nnoo] = $hasil_siswa_presensi;
                $data_pembimbing[$no][$nno]['presensi'][$array_tgl[$tgl]]['nis'][] = $hasil_siswa_presensi['nis'];
                $data_pembimbing[$no][$nno]['presensi'][$array_tgl[$tgl]]['ket'][] = $hasil_siswa_presensi['ket'];
                $data_pembimbing[$no][$nno]['presensi'][$array_tgl[$tgl]]['jurnal'][] = $hasil_siswa_presensi['jurnal'];
                $nis = $hasil_siswa_presensi['nis'];

                $query_siswa_per_dudi = mysqli_query($konek, "SELECT nama, kelas, jur, gander FROM datasiswa WHERE nis = '$nis'");

                $nom = 0;
                while ($hasil_siswa_per_dudi = mysqli_fetch_array($query_siswa_per_dudi)) {
                    $data_pembimbing[$no][$nno]['presensi'][$array_tgl[$tgl]]['nama'][] = $hasil_siswa_per_dudi['nama'];
                    $data_pembimbing[$no][$nno]['presensi'][$array_tgl[$tgl]]['gander'][] = $hasil_siswa_per_dudi['gander'];
                    $data_pembimbing[$no][$nno]['presensi'][$array_tgl[$tgl]]['kelas'][] = $hasil_siswa_per_dudi['kelas'];

                    $nom++;
                }

                $nnoo++;
            }
        }

        $nno++;
    }

    $no++;
}

// echo '<pre>';
// print_r($data_pembimbing);
// echo '</pre>';
// die;

// echo "jumlah siswa Dtech : " . count($data_pembimbing[0][3]['nis']);
// echo "<br>";
// echo "<br>";

// echo "jumlah Pembimbing: " . count($data_pembimbing);
// echo "<br><br>";

$pesan_awal = array();
$pesantext = array();
$pesan_dudi = array();
$pesan_siswa = array();
$pesan_dudi_siswa = array();
$kirim = 0;
$jumsis = 0;
$hitkirim = 0;
$nowa = array();

$text_dudi_siswa = '';
$text_siswa = '';
$text_dudi = '';
$text = '';

$jumlah_pembimbing = count($data_pembimbing);

// echo "Jumlah Pembimbing: " . $jumlah_pembimbing;
// echo "<br>";

for ($i = 0; $i < $jumlah_pembimbing; $i++) {
    if ($data_pembimbing[$i]['pembimbing']) {
        $nowa[] = $data_pembimbing[$i][0]['nowa'];
        $jumlah_dudi_pembimbing = count($data_pembimbing[$i]) - 1;

        // echo $i + 1 . ". ";
        // echo "Pembimbing: " . $data_pembimbing[$i]['pembimbing'];
        // echo "<br>";
        // echo "&nbsp;&nbsp;&nbsp;&nbsp;Jumlah dudi Pembimbing: " . $jumlah_dudi_pembimbing;
        // echo '<br>';
        // echo "&nbsp;&nbsp;&nbsp;&nbsp;No WA Pembimbing: " . $nowa[$i];
        // echo '<br>';

$pesan_awal[] = 'Dari Admin TE BOS
Yth. Pembimbing Prakerin : *' . $data_pembimbing[$i]['pembimbing'] . '*
Berikut rekap Presensi Prakerin Selama 1 Minggu 
(' . tgl($array_tgl[0], 'hari') . ", " . date('d', strtotime($array_tgl[0])) . " " . tgl($array_tgl[0], "bulan") . " " . date('Y', strtotime($array_tgl[0])) . ' s/d '  . tgl($array_tgl[6], 'hari') . ", " . date('d', strtotime($array_tgl[6])) . " " . tgl($array_tgl[6], "bulan") . " " . date('Y', strtotime($array_tgl[6])) . '), 
Jurusan : *' . $data_pembimbing[$i][0]['jur'] . '* 
Tahun   : ' . $tahun;

        $no = 0;
        $nomer_dudi = 0;

        for ($j = 0; $j < $jumlah_dudi_pembimbing; $j++) {
            if ($data_pembimbing[$i][$j]['siswa']['nis']) {
                $nomer_dudi++;

                $jml_sis = count($data_pembimbing[$i][$j]['siswa']['nis']);

                // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                // echo $no + 1 . " Nama DUdi: " . $data_pembimbing[$i][$j]['namadudi'];
                // echo '<br>';
                // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                // echo "Jumlah Siswa: " . $jml_sis;
                // echo '<br>';

                $pesan_dudi[$i][$nomer_dudi - 1][] = '

' . $nomer_dudi . '. DUDI: *' . strtoupper($data_pembimbing[$i][$j]['namadudi']) . '*
    Alamat: ' . $data_pembimbing[$i][$j]['kota'] . '
    Lokasi Map: ' . $data_pembimbing[$i][$j]['map'] . '
    
    Jumlah Siswa: ' . $jml_sis . '
';

                $nu = 0;
                $nis_presensi = array();
                for ($k = 0; $k < $jml_sis; $k++) {
                    $namasiswa = $data_pembimbing[$i][$j]['siswa']['namasiswa'][$k];
                    $kelassiswa = $data_pembimbing[$i][$j]['siswa']['kelas'][$k];
                    $gendersiswa = $data_pembimbing[$i][$j]['siswa']['gander'][$k];
                    $nissiswa = $data_pembimbing[$i][$j]['siswa']['nis'][$k];

                    $ket_presensisiswa = '';
                    $ket = "";
                    for ($absn = 0; $absn < count($array_tgl); $absn++) {
                        $nis_presensi = ($data_pembimbing[$i][$j]['presensi'][$array_tgl[$absn]]['nis']);
                        if($nis_presensi){
                            $jumlah_presensi = count($nis_presensi);
                        } else {
                            $jumlah_presensi = 0;
                        }

                        for ($yg = 0; $yg < $jumlah_presensi; $yg++) {
                            $nis_hasil = @$data_pembimbing[$i][$j]['presensi'][$array_tgl[$absn]]['nis'][$yg];

                            if ($nissiswa == $nis_hasil) {
                                $ket_hasil = @$data_pembimbing[$i][$j]['presensi'][$array_tgl[$absn]]['ket'][$yg];

                                if ($ket_hasil) {
                                    $ket = $ket_hasil;
                                    break;
                                } elseif ($nis_hasil) {
                                    $ket = "Absen";
                                    break;
                                }
                            }
                        }

                        // // $presensisiswa = @$data_pembimbing[$i][$j]['presensi'][$array_tgl[$absn]]['nis'][$k];
                        // // $ket = @$data_pembimbing[$i][$j]['presensi'][$array_tgl[$absn]]['ket'][$k];

                        if ($ket) {
                            if ($ket == "Masuk") {
                                $icon_ket = "✅ ";
                            } elseif ($ket == "Izin") {
                                $icon_ket = "ℹ️ ";
                            } elseif ($ket == "Sakit") {
                                $icon_ket = "🤒 ";
                            } elseif ($ket == "Tidak_Masuk") {
                                $icon_ket = "☕️ ";
                            } else {
                                $icon_ket = "☑️ ";
                            }

                            $ket_presensisiswa = $ket_presensisiswa . '[' . ($absn + 1) . ']' . $icon_ket;
                        } else {
                            $ket_presensisiswa = $ket_presensisiswa . '[' . ($absn + 1) . ']🚫 ';
                        }
                        
                    $ket = "";
                    }
                    

                    // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    // echo $nu + 1 . '. ' . $namasiswa . ': ' . $ket_presensisiswa;
                    // echo '<br>';

                    $pesan_siswa[$i][$nomer_dudi - 1][$k] = "
    " . ($k + 1) . ". (" . $kelassiswa . ")(" . $gendersiswa . ") *" . strtoupper($namasiswa) . "*: " . ($ket_presensisiswa ? "
        " . $ket_presensisiswa . "
        " : "");
                    $nu++;
                }
            }
            $no++;
        }
    }
}

// echo "kirim " . $kirim;
// echo "<br>";
// echo "<br>";
// echo "hitkirim " . $hitkirim;
// echo "<br>";
// echo "<br>";
// echo "kirim ke " . $nowa[$kirim - 1];
$jumlah_siswa = 0;
$_tgl = "";

for ($ttggl = 0; $ttggl < count($array_tgl); $ttggl++) {
    $_tgl = $_tgl . "[" . ($ttggl + 1) . "] => " . tgl($array_tgl[$ttggl], 'hari') . ", " . date('d', strtotime($array_tgl[$ttggl])) . " " . tgl($array_tgl[$ttggl], "bulan") . " " . date('Y', strtotime($array_tgl[$ttggl])) . "
    ";
}

// echo "Keterangan: " . $_tgl;
// echo "<br>";
// $ii = 15;

for ($ii = 0; $ii < $jumlah_pembimbing; $ii++) {
    $text =  "";
    $text = $text . $pesan_awal[$ii];

    for ($jj = 0; $jj < count($pesan_dudi[$ii]); $jj++) {
        $text_dudi = $text_dudi . $pesan_dudi[$ii][$jj][0];

        for ($kkk = 0; $kkk < count($pesan_siswa[$ii][$jj]); $kkk++) {
            if ($kkk == 0) {
                $text_siswa = $text_dudi;
            }

            $text_siswa = $text_siswa . $pesan_siswa[$ii][$jj][$kkk];
            $text_dudi = $text_siswa;
        }
    }

    $text = $text . $text_dudi;
    $text = $text . "

_Keterangan:_
    " . $_tgl . "
    ✅ = Masuk,
    ℹ️ = Ijin,
    🤒 = Sakit,
    ☕️ = Libur,
    🚫 = Tidak Presensi
    
Selengkapnya bisa dilihat di Aplikasi \"Prakerin Skaneba\" 
https://pkl.smknbansari.sch.id/admin/rekapabsensiswa.php?p=" . ($ii+1) . "

Login sebagai Pembimbing:
👤 Username: pembimbing
🔐 Password: pembimbing$

_Update: " . date("H:i:s d-m-Y") . "_
===========================================";
    $pesantext[$ii] = $text;

    $token = "qnNDCgY0F4F3MVna2LoF"; //WA ku
    // $token = "@USo9EJ4cicpFQ1t9n0n"; //WA Marketingfg
    $nomor = $nowa[$ii];
    $pesan = $pesantext[$ii];

    // kirim WA
    if(@$_GET['kirim'] == 'tidak'){
        echo "Tidak Dikirim<br>";
    } else {
        include "apiwa.php";
        echo "Dikirim<br>";
        echo "Ke $nomor<br>";
        echo "Pesan: $pesan<br>";
    }

    $text = '';
    $text_dudi = '';
    $text_siswa = '';
}


echo "<br>";
echo "<br>";
echo 'pesantext: ';
echo '<pre>';
print_r($pesantext);
echo '</pre>';

// echo '<br>';
// echo 'Pesan: <br>';
// echo '<pre>';
// print_r($pesan_awal);
// echo '</pre>';

// echo "pesan_dudi: ";
// echo '<pre>';
// print_r($pesan_dudi);
// echo '</pre>';

// echo "pesan_siswa: ";
// echo '<pre>';
// print_r($pesan_siswa);
// echo '</pre>';

echo '<pre>';
print_r($data_pembimbing);
echo '</pre>';

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
