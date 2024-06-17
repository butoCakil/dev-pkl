<?php
$nis = @$_GET['nis'];
if ($nis) {
    session_start();
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date('Y-m-d');
    // $tanggal = '2023-06-13';

    $tahun = "2023";

    $no = 1;
    $nama_bulan = array(
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    include "views/header.php";
    include "views/navbar.php";

    include "koneksi.php";

    $q_siswa = mysqli_query($konek, "SELECT * FROM datasiswa WHERE nis = '$nis'");

    foreach ($q_siswa as $data) {
        $namasiswa = $data['nama'];
        $kelassiswa = $data['kelas'];
        $jursiswa = $data['jur'];
        $gendersiswa = $data['gander'];
    }

    if ($namasiswa) {

        // $presensi = array();
        // $q_presensi = mysqli_query($konek, "SELECT * FROM presensi WHERE timestamp LIKE '%$tanggal%' AND nis = '$nis'");
        // foreach ($q_presensi as $datapresensi) {
        //     $presensi[] = $datapresensi;
        // }

        // echo "<pre>";
        // print_r($presensi);
        // echo "</pre>";
?>

        <style>
            .view {
                margin: auto;
                width: auto;
            }

            .wrapper {
                position: relative;
                overflow: auto;
                border: 1px solid grey;
                white-space: nowrap;
            }

            .sticky-col {
                position: -webkit-sticky;
                position: sticky;
                border: none;
            }

            .first-col {
                width: 30px;
                min-width: 30px;
                max-width: 30px;
                left: 0px;
                border-color: white;
                background-color: white;
            }

            .second-col {
                width: 100px;
                min-width: 100px;
                max-width: 100px;
                left: 30px;
                border-color: white;
                background-color: white;
            }
        </style>

        <div class="container">
            <div class="d-flex justify-content-between">
                <h5>Rekap Presensi</h5>
                <a href="../" class="btn btn-dark btn-sm border-0">
                    << Kembali</a>
            </div>
            <table style="font-size: 14px;">
                <tbody>
                    <tr>
                        <td>NIS&nbsp;</td>
                        <td>:&nbsp;<?= $nis; ?></td>
                    </tr>
                    <tr>
                        <td>Nama&nbsp;</td>
                        <td>:&nbsp;<?= $namasiswa; ?>&nbsp;(<?= $gendersiswa; ?>)</td>
                    </tr>
                    <tr>
                        <td>Kelas&nbsp;</td>
                        <td>:&nbsp;<?= $kelassiswa; ?></td>
                    </tr>
                    <tr>
                        <td>Jurusan&nbsp;</td>
                        <td>:&nbsp;<?= $jursiswa; ?></td>
                    </tr>
                    <tr>
                        <td>Tahun&nbsp;</td>
                        <td>:&nbsp;<?= $tahun; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="view">
                <div class="wrapper table-responsive" style="font-size: 12px;">
                    <table class="table table-bordered">
                        <thead class="text-center bg-info">
                            <th class="sticky-col first-col bg-info">No.</th>
                            <th class="sticky-col second-col bg-info">Bulan</th>
                            <th colspan="31">Tangal</th>
                        </thead>
                        <thead class="bg-info">
                            <th class="sticky-col first-col bg-info"></th>
                            <th class="sticky-col second-col bg-info"></th>
                            <?php for ($i = 1; $i <= 31; $i++) { ?>
                                <th><?= $i; ?></th>
                            <?php } ?>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php for ($j = 6; $j < 12; $j++) { ?>
                                <tr>
                                    <td class="sticky-col first-col bg-light"><?= ($no++); ?></td>
                                    <td class="sticky-col second-col bg-light"><?= @$nama_bulan[$j]; ?></td>
                                    <?php

                                    $bulan_genap = array(6, 8, 10, 12);

                                    for ($k = 0; $k < 31; $k++) {
                                        if (in_array(($j + 1), $bulan_genap) && (($k + 1) == 31)) {
                                            $_gettgl = "";
                                        } else {
                                            $_gettgl = $tahun . "-" . sprintf("%02d", ($j + 1)) . "-" . sprintf("%02d", ($k + 1));
                                        }


                                        if ($_gettgl) {
                                            $nama_hari = tgl($_gettgl, "hari");

                                            if ($nama_hari == "Minggu") {
                                                $bg_tbl = "danger";
                                                $bg = "background-color: rgb(253, 156, 156);";
                                            } elseif ($nama_hari == "Sabtu") {
                                                $bg_tbl = "secondary";
                                                $bg = "background-color: rgb(219, 219, 219);";
                                            } elseif ($nama_hari == "Jumat") {
                                                $bg_tbl = "success";
                                                $bg = "background-color: rgb(197, 235, 197);";
                                            } else {
                                                $bg_tbl = "light";
                                                $bg = "background-color: white;";
                                            }

                                            $q_presensi = mysqli_query($konek, "SELECT * FROM presensi WHERE timestamp LIKE '%$_gettgl%' AND nis = '$nis'");
                                            $hasil = mysqli_fetch_array($q_presensi);
                                            $ket = $hasil['ket'];

                                            if ($ket == "Masuk") {
                                                $icon_ket = "☑️";
                                            } elseif ($ket == "Izin") {
                                                $icon_ket = "✉️";
                                            } elseif ($ket == "Sakit") {
                                                $icon_ket = "🩺";
                                            } elseif ($ket == "Tidak_Masuk") {
                                                $icon_ket = "☕️";
                                            } else {
                                                $icon_ket = "-";
                                            }

                                            echo "<td style = \"$bg\">$icon_ket</td>";
                                        } else {
                                            echo "<td style = \"background-color: rgb(0, 0, 0);\"></td>";
                                            // echo "<td><span class='badge bg-dark text-dark'>-</span></td>";
                                        }
                                    }
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container"></div>

<?php
        include "views/footer.php";
    } else {
        echo "<script>
            alert('NIS tidak ditemukan.');
            window.location.href='../';
        </script>";
    }
} else {
    echo "<script>
            alert('Perlu input NIS untuk membuka halaman ini.');
            window.location.href='../';
        </script>";
}

function cari_database($_tgl, $_key, $_db)
{
    foreach ($_db as $dd) {
        if ($dd[$_key]) {
            $data[] = $dd;
            return $data;
        }
    }
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
