<?php
$admin = true;
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
// $tanggal = date('Y-m-d', strtotime('-3 day', strtotime($tanggal)));
$tanggal_id = date('d-m-Y');
$tgl = date('d');
$bln = date('m');
$thn = date('Y');

$_ppemb = '';

$tanggal_pilih = @$_GET['tanggal'];

if ($tanggal_pilih > $tanggal) {
    echo "<script>alert('Opps, perlu mesin waktu untuk melihat hasil tanggal yang dipilih (" . $tanggal_pilih . "). Kita jalani dulu hari ini. Okay? hehe');</script>";
    $tanggal_pilih = $tanggal;
}

$tampil = @$_GET['tampil'];
$jurusan = @$_GET['jur'];

if (!$tanggal_pilih) {
    $tanggal_pilih = $tanggal;
}

$bln_pilih = date('m', strtotime($tanggal_pilih));
$thn_pilih = date('Y', strtotime($tanggal_pilih));

$tanggal_pilih_format = date('d-m-Y', strtotime($tanggal_pilih));

// tanggal kurang 1 hari
$tanggal_kurang_1 = date('Y-m-d', strtotime('-1 day', strtotime($tanggal_pilih)));

// tanggal tambah 1 hari
$tanggal_tambah_1 = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_pilih)));


// hari bahasa indonesia
$hari = date('l', strtotime($tanggal_pilih));
$hari_indonesia_array = array(
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
);

$hari_indonesia = $hari_indonesia_array[$hari];

// bulan indonesia
$bulan = date('F', strtotime($tanggal_pilih));
$bulan_indonesia_array = array(
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

$bulan_indonesia = $bulan_indonesia_array[$bulan];

// disable button selanjutnya jika yang tampil data hari ini
if ($tanggal_pilih == $tanggal) {
    $disabled_next = 'btn-secondary disabled';
} else {
    $disabled_next = 'btn-info';
}

session_start();
$title = "Presensi Siswa di DUDIKA";
$admin = true;
include "../views/header.php";
include "../views/navbar.php";

if (@$_SESSION["admin"]) {
    $pembimbing_pilih = @$_GET['p'];
    $dudi_pilih = @$_GET['d'];

    include "../koneksi.php";

    // Prepared statement untuk mencari nama pembimbing berdasarkan id
    $sql_cek_pembimbing = "SELECT * FROM datapembimbing WHERE id = ?";
    $stmt_pembimbing = mysqli_prepare($konek, $sql_cek_pembimbing);
    mysqli_stmt_bind_param($stmt_pembimbing, "s", $pembimbing_pilih);
    mysqli_stmt_execute($stmt_pembimbing);
    $result_cek_pembimbing = mysqli_stmt_get_result($stmt_pembimbing);
    $hasil_cek_pembimbing = mysqli_fetch_array($result_cek_pembimbing);

    $hasil_nama_pembimbing = @$hasil_cek_pembimbing['nama'];

    // Menutup statement setelah penggunaan
    mysqli_stmt_close($stmt_pembimbing);

    $data_siswa = array();
    $sql_statement = "SELECT * FROM duditerisi";

    if ($hasil_nama_pembimbing) {
        $sql_statement .= " WHERE pembimbing = ?";
        $stmt = mysqli_prepare($konek, $sql_statement);
        mysqli_stmt_bind_param($stmt, "s", $hasil_nama_pembimbing);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($hasil_para_siswa = mysqli_fetch_array($result)) {
            $data_siswa[] = $hasil_para_siswa;
        }
        $sub_title = "Pembimbing: " . $hasil_nama_pembimbing;
    } elseif ($dudi_pilih) {
        $sql_statement .= " WHERE kode = ?";
        $stmt = mysqli_prepare($konek, $sql_statement);
        mysqli_stmt_bind_param($stmt, "s", $dudi_pilih);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($hasil_para_siswa = mysqli_fetch_array($result)) {
            $data_siswa[] = $hasil_para_siswa;
        }
        $sub_title = "DUDI: " . $dudi_pilih;
    } else {
        if ($jurusan) {
            $sql_statement .= " WHERE jur = ?";
            $stmt = mysqli_prepare($konek, $sql_statement);
            mysqli_stmt_bind_param($stmt, "s", $jurusan);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($hasil_para_siswa = mysqli_fetch_array($result)) {
                $data_siswa[] = $hasil_para_siswa;
            }
            $sub_title = "Jurusan: " . $jurusan;
        } else {
            $stmt = mysqli_prepare($konek, $sql_statement);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            while ($hasil_para_siswa = mysqli_fetch_array($result)) {
                $data_siswa[] = $hasil_para_siswa;
            }
            $sub_title = '';
        }
    }

    mysqli_stmt_close($stmt);

    $result_pembimbing = mysqli_query($konek, "SELECT * FROM datapembimbing");
    $result_dudi = mysqli_query($konek, "SELECT * FROM datadudi");
    ?>

    <style>
        h4 {
            text-align: center;
            margin-bottom: 10px;
        }

        .container .row div {
            padding: 10px;
        }

        .container table tbody tr:hover {
            background-color: darkgray;
        }

        #foto_jurnal {
            width: 100px;
            height: 100px;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
        }

        #foto_jurnal:hover {
            object-fit: contain;
            transform: scale(3);
            /* transisi */
            transition: all 0.5s;
        }

        @media screen and (max-width: 768px) {

            table,
            .btn {
                font-size: 12px;
            }
        }
    </style>


    <div class="container">
        <!-- Menu tampilan rekap absen -->
        <a href="../admin/" class="btn btn-sm btn-dark border-0 m-2">
            <i class="fas fa-arrow-left"></i>&nbsp;
            Kembali
        </a>


        <!-- <div class="container"> -->
        <div class="alert alert-warning alert-dismissible fade show h-0" role="alert" style="font-size: 12px;">
            <strong>Info: </strong>
            <li>Pilih Jurusan untuk menampilkan hanya siswa sesuai Jurusan yamg dipilih.</li>
            <li>Pilih Pembimbing untuk menampilkan hanya siswa sesuai pembimbing yamg dipilih.</li>
            <li>Pilih DUDI untuk menampilkan siswa sesuai DUDI yang dipilih.</li>
            <li>Pilih tanggal untuk melihat data pada tanggal tersebut</li>
            <li>Klik Nama Siswa untuk melihat detail / Catatan / Foto Presensi.</li>
            <li>Tampil Jurnal/Tampil Presensi, mengubah tampilan tabel. Tampil Jurnal menampilkan Foto dan jurnal</li>
            <li>Keterangan: <span class='badge text-bg-success'><i class='fas fa-check'></i></span> = Masuk, <span
                    class='badge text-bg-primary'>✉️</span> = Ijin, <span class='badge text-bg-warning'>🤒</span> = Sakit,
                <span class='badge text-bg-dark'>☕️</span> = Libur, <span class='badge text-bg-danger'><i
                        class='fas fa-times'></i></span> = Tidak Presensi
            </li>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="d-flex flex-wrap">
            <div class="m-2">
                <a class="btn btn-secondary btn-sm border-0" href="rekapabsensiswa.php">
                    Semua
                </a>
            </div>

            <div class="dropdown m-2">
                <a class="btn btn-warning btn-sm border-0 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Jurusan
                </a><br>
                <!--<label for="">Pilih DUDI</label>-->

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="rekapabsensiswa.php?jur=AT">AT</a></li>
                    <li><a class="dropdown-item" href="rekapabsensiswa.php?jur=DKV">DKV</a></li>
                    <li><a class="dropdown-item" href="rekapabsensiswa.php?jur=TE">TE</a></li>
                </ul>
            </div>

            <div class="dropdown m-2">
                <a class="btn btn-success btn-sm border-0 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Pembimbing
                </a><br>
                <!--<label for="">Pilih Pembimbing</label>-->

                <ul class="dropdown-menu">
                    <?php
                    foreach ($result_pembimbing as $dtp) {
                        echo '<li><a class="dropdown-item" href="rekapabsensiswa.php?p=' . $dtp["id"] . '">' . '[' . $dtp["jur"] . '] ' . $dtp["nama"] . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="dropdown m-2">
                <a class="btn btn-primary btn-sm border-0 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Dudi
                </a><br>
                <!--<label for="">Pilih DUDI</label>-->

                <ul class="dropdown-menu">
                    <?php
                    foreach ($result_dudi as $dtd) {
                        echo '<li><a class="dropdown-item" href="rekapabsensiswa.php?d=' . $dtd["kode"] . '">' . '[' . $dtd["jur"] . '] ' . $dtd["namadudi"] . '</a></li>';
                    }
                    ?>
                </ul>
            </div>

            <div class="m-2">
                <input class="form-control form-control-sm" type="date" name="tanggal_pilih"
                    value="<?php echo $tanggal_pilih; ?>"
                    onchange="pilih_tanggal(this.value, ' <?= $pembimbing_pilih; ?>', '<?= $dudi_pilih; ?>', '<?= $tampil; ?>', '<?= $jurusan; ?>');">
                <label class="mx-2">Pilih Tanggal</label>
            </div>

            <div class="m-2">
                <?php
                $_url_ = $_SERVER['REQUEST_URI'];

                if (strpbrk($_url_, "?")) {
                    if ($tampil == "jurnal") {
                        $_link_ = $_url_ . "&tampil=";
                    } else {
                        $_link_ = $_url_ . "&tampil=jurnal";
                    }
                } else {
                    if ($tampil == "jurnal") {
                        $_link_ = "?tampil=";
                    } else {
                        $_link_ = "?tampil=jurnal";
                    }
                }


                ?>
                <a class="btn btn-dark btn-sm border-0" href="<?= $_link_; ?>">
                    Tampil <?= ($tampil == "jurnal") ? "Presensi" : "Jurnal"; ?>
                </a>
            </div>
        </div>

        <?php if (@$_SESSION["error"]) {
            $pesan = $_SESSION["error"];
            unset($_SESSION["error"]);
            ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $pesan; ?>
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <?php if (@$_SESSION["ok"]) {
            $pesan = $_SESSION["ok"];
            unset($_SESSION["ok"]);
            ?>
            <div class="alert alert-success text-center" role="alert">
                <?= $pesan; ?>
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>


        <div class="m-2">
            <div class="d-flex justify-content-between">
                <?php
                if ($tanggal_pilih >= $tanggal) {
                    $disabled_tombol_selanjutnya = "disabled";
                } else {
                    $disabled_tombol_selanjutnya = "";
                }
                ?>
                <button class="btn btn-light btn-sm border-0"
                    onclick="tgl_s('<?= $tanggal_kurang_1; ?>', '<?= $pembimbing_pilih; ?>', '<?= $dudi_pilih; ?>', '<?= $tampil; ?>', '<?= $jurusan; ?>');">
                    << Sebelumnya </button>
                        <button class="btn btn-light btn-sm border-0"
                            onclick="tgl_s('<?= $tanggal_tambah_1; ?>', '<?= $pembimbing_pilih; ?>', '<?= $dudi_pilih; ?>', '<?= $tampil; ?>', '<?= $jurusan; ?>');"
                            <?= $disabled_tombol_selanjutnya; ?> id="">
                            Selanjutnya >>
                        </button>
            </div>
            <h4>Data Presensi Siswa di DUDIKA</h4>
            <h4><i><?= $hari_indonesia . ', ' . date('d', strtotime($tanggal_pilih)) . ' ' . $bulan_indonesia . ' ' . date('Y', strtotime($tanggal_pilih)); ?></i>
            </h4>
            <h4 class="badge bg-danger text-center d-block mx-auto"><?= $_GET ? $sub_title : "Semua Jurusan"; ?></h4>
        </div>
        <div class="table-responsive">
            <table id="tabeldatasiswa" class="table mt-3">
                <thead>
                    <tr>
                        <!--<th>No</th>-->
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>No WA</th>
                        <th>DUDIKA</th>
                        <th>Qty</th>
                        <?php
                        if (@$tampil == 'jurnal') {
                            ?>
                            <th>Junal Harian</th>
                            <?php
                        } else {
                            $tgl_sekarang = $_tgl_kemarin = date('Y-m-d', strtotime('-7 day', strtotime($tanggal_pilih)));
                            $hari_sekarang = $hari_indonesia;
                            $tgl_kemarin = array();
                            $hari_kemarin = array();

                            for ($s = 0; $s < 8; $s++) {
                                $hari_sekarang = date('d', strtotime($tgl_sekarang));
                                $bulan_sekarang = date('m', strtotime($tgl_sekarang));
                                $hari_col = date('l', strtotime($thn_pilih . "-" . $bulan_sekarang . "-" . sprintf("%02d", $hari_sekarang)));
                                ?>
                                <th class="text-center" style="font-size: 12px;">
                                    <?= $hari_indonesia_array[$hari_col] . ", "; ?>
                                    <?= date('d/m', strtotime($tgl_sekarang)); ?>
                                </th>
                                <?php
                                $_tgl_kemarin = date('Y-m-d', strtotime('+1 day', strtotime($tgl_sekarang)));
                                $tgl_kemarin[] = $_tgl_kemarin;
                                $tgl_sekarang = $_tgl_kemarin;
                            }
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data_siswa as $dts) {
                        $namasiswa = $dts['namasiswa'];
                        $nis = $dts['nis'];
                        $gander = $dts['gander'];
                        $kelas = $dts['kelas'];
                        $namadudi = $dts['namadudi'];
                        $pembimbing = $dts['pembimbing'];
                        ?>
                        <tr>
                            <!--<td><?= $no++; ?></td>-->
                            <td><?= $nis; ?><br><span class="badge badge-sm bg-dark"><?= $kelas; ?></span></td>
                            <td>
                                <a class="text-decoration-none" href="../rekap.php?nis=<?= $nis; ?>&akses=rekapabsen">
                                    <?= $namasiswa; ?><br><span class="badge badge-sm bg-info"><?= $gander; ?></span>
                                </a>
                            </td>

                            <td>
                                <?php
                                $query = "SELECT nohp FROM datasiswa WHERE nis = ?";
                                $stmt = mysqli_prepare($konek, $query);
                                mysqli_stmt_bind_param($stmt, "s", $nis);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if (mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_array($result);

                                    if ($row["nohp"]) {
                                        $link_wa = "https://api.whatsapp.com/send?phone=" . $row["nohp"];
                                        ?>
                                        <br><a href="<?= htmlspecialchars($link_wa); ?>" class="btn btn-sm btn-success border-0"><i
                                                class="fab fa-whatsapp"
                                                style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                                        <?php
                                    }
                                }

                                mysqli_stmt_close($stmt);
                                ?>
                            </td>

                            <td><?= $namadudi; ?><br><span class="badge badge-sm bg-success"><?= $pembimbing; ?></span></td>

                            <td>
                                <?php
                                // Query untuk menghitung jumlah 'Masuk' berdasarkan 'nis'
                                // Prepared statement untuk menghitung jumlah 'Masuk'
                                $query = "SELECT COUNT(`ket`) AS jumlah_masuk FROM `presensi` WHERE `ket` = 'Masuk' AND `nis` = ?";
                                $stmt = mysqli_prepare($konek, $query);

                                // Bind parameter $nis ke dalam prepared statement
                                mysqli_stmt_bind_param($stmt, "s", $nis);

                                // Eksekusi prepared statement
                                mysqli_stmt_execute($stmt);

                                // Ambil hasil query
                                $result = mysqli_stmt_get_result($stmt);

                                // Periksa apakah query berhasil dijalankan
                                if ($result) {
                                    // Ambil nilai jumlah 'Masuk' dari hasil query
                                    $row = mysqli_fetch_assoc($result);
                                    $jumlah_masuk = $row['jumlah_masuk'];

                                    // Tampilkan jumlah 'Masuk'
                                    echo "<span class='badge bg-dark'>M: $jumlah_masuk</span><br>";

                                    // Bebaskan hasil query
                                    mysqli_free_result($result);
                                } else {
                                    // Tampilkan pesan kesalahan jika query gagal
                                    echo "Error: " . mysqli_stmt_error($stmt);
                                }

                                // Tutup prepared statement
                                mysqli_stmt_close($stmt);

                                // IJIN
                                // Query untuk menghitung jumlah 'Masuk' berdasarkan 'nis'
                        
                                // Prepared statement untuk menghitung jumlah 'Ijin'
                                $query = "SELECT COUNT(`ket`) AS jumlah_ijin FROM `presensi` WHERE `ket` = 'Ijin' AND `nis` = ?";
                                $stmt = mysqli_prepare($konek, $query);

                                // Bind parameter $nis ke dalam prepared statement
                                mysqli_stmt_bind_param($stmt, "s", $nis);

                                // Eksekusi prepared statement
                                mysqli_stmt_execute($stmt);

                                // Ambil hasil query
                                $result = mysqli_stmt_get_result($stmt);

                                // Periksa apakah query berhasil dijalankan
                                if ($result) {
                                    // Ambil nilai jumlah 'Ijin' dari hasil query
                                    $row = mysqli_fetch_assoc($result);
                                    $jumlah_ijin = $row['jumlah_ijin'];

                                    // Tampilkan jumlah 'Ijin'
                                    echo "<span class='badge bg-info text-dark'>I: $jumlah_ijin</span><br>";

                                    // Bebaskan hasil query
                                    mysqli_free_result($result);
                                } else {
                                    // Tampilkan pesan kesalahan jika query gagal
                                    echo "Error: " . mysqli_stmt_error($stmt);
                                }

                                // Tutup prepared statement
                                mysqli_stmt_close($stmt);

                                // SAKIT
                                // Query untuk menghitung jumlah 'Masuk' berdasarkan 'nis'
                                // Prepared statement untuk menghitung jumlah 'Sakit'
                                $query = "SELECT COUNT(`ket`) AS jumlah_sakit FROM `presensi` WHERE `ket` = 'Sakit' AND `nis` = ?";
                                $stmt = mysqli_prepare($konek, $query);

                                // Bind parameter $nis ke dalam prepared statement
                                mysqli_stmt_bind_param($stmt, "s", $nis);

                                // Eksekusi prepared statement
                                mysqli_stmt_execute($stmt);

                                // Ambil hasil query
                                $result = mysqli_stmt_get_result($stmt);

                                // Periksa apakah query berhasil dijalankan
                                if ($result) {
                                    // Ambil nilai jumlah 'Sakit' dari hasil query
                                    $row = mysqli_fetch_assoc($result);
                                    $jumlah_sakit = $row['jumlah_sakit'];

                                    // Tampilkan jumlah 'Sakit'
                                    echo "<span class='badge bg-warning text-dark'>S: $jumlah_sakit</span>";

                                    // Bebaskan hasil query
                                    mysqli_free_result($result);
                                } else {
                                    // Tampilkan pesan kesalahan jika query gagal
                                    echo "Error: " . mysqli_stmt_error($stmt);
                                }

                                // Tutup prepared statement
                                mysqli_stmt_close($stmt);
                                ?>
                            </td>

                            <?php
                            if (@$tampil == 'jurnal') {
                                echo "<td>";
                                $sql_absen = mysqli_query($konek, "SELECT * FROM presensi WHERE nis = '$nis' AND timestamp LIKE '%" . $tanggal_pilih . "%'");

                                $data_file = array();
                                foreach ($sql_absen as $dtap) {
                                    // $data_file[] = $dtap;
                    
                                    // echo "<pre>";
                                    // print_r($data_file);
                                    // echo "</pre>";
                    
                                    $data_file = @$dtap['file'];
                                    $data_jurnal = @$dtap['jurnal'];
                                    // echo "<td>$data_file</td>";
                                    ?>

                                    <img src="../img/presensi/<?= $data_file; ?>" id="foto_jurnal"><br>
                                    <label for="foto_jurnal"><?= $data_jurnal ? $data_jurnal : "-"; ?></label><br>
                                    <?php
                                }
                                echo "</td>";
                            } else {

                                $tgl_sekarang = $_tgl_kemarin = date('Y-m-d', strtotime('-7 day', strtotime($tanggal_pilih)));
                                $tgl_kemarin = array();

                                for ($s = 0; $s < 8; $s++) {
                                    echo "<td class='text-center'>";

                                    // Prepared statement untuk mengambil presensi
                                    $sql_absen = "SELECT * FROM presensi WHERE nis = ? AND timestamp LIKE ?";
                                    $stmt = mysqli_prepare($konek, $sql_absen);

                                    // Bind parameter $nis dan $tgl_sekarang ke dalam prepared statement
                                    $nis_param = $nis;
                                    $tgl_param = '%' . $tgl_sekarang . '%';
                                    mysqli_stmt_bind_param($stmt, "ss", $nis_param, $tgl_param);

                                    // Eksekusi prepared statement
                                    mysqli_stmt_execute($stmt);

                                    // Ambil hasil query
                                    $result_absen = mysqli_stmt_get_result($stmt);
                                    $hitung_absen = mysqli_num_rows($result_absen);
                                    $hasil_ket_absen = mysqli_fetch_array($result_absen);
                                    $ket_absen = isset($hasil_ket_absen['ket']) ? $hasil_ket_absen['ket'] : '';

                                    // Tentukan hasil presensi berdasarkan ket_absen
                                    if ($ket_absen == "Masuk") {
                                        $hasil_presensi = "<i class='fas fa-check'></i>&nbsp;";
                                        $bgbg = "success";
                                    } elseif ($ket_absen == "Izin") {
                                        $hasil_presensi = "✉️&nbsp;";
                                        $bgbg = "primary";
                                    } elseif ($ket_absen == "Sakit") {
                                        $hasil_presensi = "🤒&nbsp;";
                                        $bgbg = "warning";
                                    } elseif ($ket_absen == "Tidak_Masuk") {
                                        $hasil_presensi = "☕️&nbsp;";
                                        $bgbg = "dark";
                                    } else {
                                        $hasil_presensi = "✅&nbsp;";
                                    }

                                    // Tampilkan badge sesuai dengan hasil presensi
                                    if ($hitung_absen > 0) {
                                        echo "<span class='badge bg-$bgbg'>$hasil_presensi&nbsp;$hitung_absen</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'><i class='fas fa-times'></i></span>";
                                    }

                                    // Hitung tanggal kemarin untuk iterasi selanjutnya
                                    $_tgl_kemarin = date('Y-m-d', strtotime('+1 day', strtotime($tgl_sekarang)));
                                    $tgl_kemarin[] = $_tgl_kemarin;
                                    $tgl_sekarang = $_tgl_kemarin;

                                    // Tutup prepared statement
                                    mysqli_stmt_close($stmt);

                                    echo "</td>";
                                }
                            }
                            ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- </div> -->

        <script type="text/javascript">
            $(document).ready(function () {
                $('#tabeldatasiswa').DataTable({
                    dom: 'rBlftip',
                    buttons: [
                        // 'print', 'excel', 'csv', 'pdf'
                        'print', 'excel'
                    ],
                    responsive: true,
                    "lengthChange": true,
                    "lengthMenu": [
                        <?php if (@$_GET) { ?>[-1, 5, 10, 25, 50, -1],
                            ["Semua", 5, 10, 25, 50, "Semua"]
                                                <?php } else { ?>[10, 25, 50, -1],
                            [10, 25, 50, "Semua"]
                                                <?php } ?>
                    ],
                    "pagingType": "full",
                    "language": {
                        "emptyTable": "Data tidak ditemukan.",
                        "info": "Ditampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "infoEmpty": "Ditampilkan 0 sampai 0 dari 0 data",
                        "infoFiltered": "(Disaring dari _MAX_ total data)",
                        "lengthMenu": "Tampilkan _MENU_ baris data",
                        "loadingRecords": "Memuat...",
                        "processing": "Memproses...",
                        "search": "Cari:",
                        "zeroRecords": "Tidak ditemukan data yang sesuai.",
                        "paginate": {
                            "first": "<<",
                            "last": ">>",
                            "next": "lanjut >",
                            "previous": "< sebelum"
                        },
                    },
                });
            });

            function pilih_tanggal(tanggal_pilih, pembimbing, dudi, tampil, juru) {
                window.location.href = "rekapabsensiswa.php?tanggal=" + tanggal_pilih + "&p=" + pembimbing + "&d=" + dudi + "&tampil=" + tampil + "&jur=" + juru;
            }

            function tgl_s(tanggal_pilih, pembimbing, dudi, tampil, juru) {
                window.location.href = "rekapabsensiswa.php?tanggal=" + tanggal_pilih + "&p=" + pembimbing + "&d=" + dudi + "&tampil=" + tampil + "&jur=" + juru;
            }
        </script>


        <?php
        mysqli_close($konek);
} else {
    // $_SESSION['url_go'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['url_go'] = $_SERVER['REQUEST_URI'];
    // echo $_SESSION['url_go'];
    ?>
        <script type="text/javascript">
            window.onload = () => {
                $('#adminlogin').modal('show');
            }
        </script>
    <?php } ?>


    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>

    <?php include "../views/footer.php" ?>

    <?php
    function cari_data($_tbl, $_nis, $_array)
    {
        $_data = array();

        foreach ($_array as $d) {
            if ($d[$_tbl] == $_nis) {
                $_data[] = $d;
            }
        }

        return $_data;
    }
    ?>