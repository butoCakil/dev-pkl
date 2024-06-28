<?php
date_default_timezone_set('Asia/Jakarta');
$timpstamp = date('Y-m-d H:i:s');
$tanggal = date('Y-m-d');
$tanggal_pilih = @$_GET['tmp'] ? $_GET['tmp'] : $tanggal;
$tgl = date('d', strtotime($tanggal_pilih));
$thn = date('Y', strtotime($tanggal_pilih));

// hari bahasa indonesia
$hari = date('l', strtotime($tanggal_pilih));
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

// bulan indonesia
$bulan = date('F', strtotime($tanggal_pilih));
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

session_start();
$title = "Preview Presensi Prakerin " . date('Y');
$admin = false;

if (@$_GET['nis']) {
    $nis = @$_GET['nis'];
} else {
    $nis = @$_POST['nis'];
}

include "views/header.php";
include "views/navbar.php";
include "koneksi.php";

$sql = "SELECT * FROM presensi WHERE nis = ? AND timestamp LIKE ?";
$stmt = mysqli_prepare($konek, $sql);
$tanggal_pilih_pattern = '%' . mysqli_real_escape_string($konek, $tanggal_pilih) . '%';
mysqli_stmt_bind_param($stmt, "ss", $nis, $tanggal_pilih_pattern);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cek = mysqli_num_rows($result);
mysqli_stmt_close($stmt);

$sql_s = "SELECT * FROM duditerisi WHERE nis = ?";
$stmt_s = mysqli_prepare($konek, $sql_s);
mysqli_stmt_bind_param($stmt_s, "s", $nis);
mysqli_stmt_execute($stmt_s);
$result_s = mysqli_stmt_get_result($stmt_s);
$hasil_s = mysqli_fetch_assoc($result_s);
$kode_dudi = $hasil_s['kode'];
$nama_siswa = isset($hasil_s['namasiswa']) ? $hasil_s['namasiswa'] : null;
$kelas_siswa = isset($hasil_s['kelas']) ? $hasil_s['kelas'] : null;
$dudika = isset($hasil_s['namadudi']) ? $hasil_s['namadudi'] : null;
mysqli_stmt_close($stmt_s);

$lokasi_query = "SELECT * FROM datadudi WHERE kode = '$kode_dudi'";
$query_lokasi = mysqli_query($konek, $lokasi_query);
$data_lokasi = mysqli_fetch_array($query_lokasi);
?>

<style>
    .container .tampilanprev .fotoprev {
        width: 200px;
        height: 200px;
        margin: 10px;
        object-fit: cover;
        object-position: center;
        /* transisi */
        transition: 1s;
    }

    .container .tampilanprev .labelfotoprev {
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        margin-top: 10px;
    }

    .container .tampilanprev {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .container .catatan {
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        margin-top: 10px;
        border: 1px solid #ccc;
    }

    .jurnal {
        font-weight: normal;
        max-height: 100px;
        overflow: auto;
    }

    .container .tampilanprevtbl {
        display: flex;
        flex-direction: column;
        width: 30%;
        margin: 0 auto;
    }

    .container .tampilanprev .fotoprev:hover {
        object-fit: contain;
        transform: scale(1.4);
        /* transisi */
        transition: all 0.5s;
    }

    .container .tampilanprev .prevperfoto {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 5px
    }

    .container span,
    .container h4,
    .container h6 {
        display: block;
        margin: 0 auto;
        text-align: center;
    }

    @media screen and (max-width: 992px) {
        .container .tampilanprevtbl {
            width: 100%;
        }
    }
</style>

<div class="container">
    <?php
    if ($cek) {
        ?>
        <h4><?= $nama_siswa; ?><br>(<?= $kelas_siswa; ?>)</h4>
        <span class="badge text-bg-info"><?= $dudika; ?></span>
        <h6><?= $hari_indonesia; ?>, <?= $tgl; ?>     <?= $bulan_indonesia; ?>     <?= $thn; ?></h6>
        <div class="tampilanprev">
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="prevperfoto">
                    <img src="img/presensi/<?= $row['file']; ?>" class="fotoprev" id="fotoprev<?= $no; ?>">
                    <label class="labelfotoprev" for="fotoprev<?= $no; ?>"><?= $row['timestamp']; ?></label>
                    <label class="badge text-bg-secondary labelfotoprev m-1"
                        for="fotoprev<?= $no; ?>"><?= $row['jurnal'] ? $row['jurnal'] : ($row['ket'] ? $row['ket'] : ""); ?></label>
                </div>
                <?php $no++;
            } ?>
        </div>

        <div class="catatan">
            <h6>Catatan</h6>

            <?php
            $sqll = "SELECT * FROM presensi WHERE nis = ? AND timestamp LIKE ?";
            $stmtl = mysqli_prepare($konek, $sqll);

            $tanggal_pilih_pattern = '%' . mysqli_real_escape_string($konek, $tanggal_pilih) . '%';

            mysqli_stmt_bind_param($stmtl, "ss", $nis, $tanggal_pilih_pattern);
            mysqli_stmt_execute($stmtl);

            $resultl = mysqli_stmt_get_result($stmtl);
            $rowl = mysqli_fetch_assoc($resultl);
            mysqli_stmt_close($stmtl);
            ?>
            <p>
                Keterangan: <b class="badge text-bg-info"><?= $rowl['ket']; ?></b>
            </p>

            <?php
            // Query SQL menggunakan prepared statement
            $sqll = "SELECT * FROM presensi WHERE nis = ?";
            $stmtl = mysqli_prepare($konek, $sqll);

            // Bind parameter ke dalam prepared statement
            mysqli_stmt_bind_param($stmtl, "s", $nis);

            // Eksekusi prepared statement
            mysqli_stmt_execute($stmtl);

            // Ambil hasil query
            $resultl = mysqli_stmt_get_result($stmtl);

            // Tutup prepared statement
            mysqli_stmt_close($stmtl);
            ?>

            <label for="">Rekap Jurnal</label>
            <p class="jurnal">
                <?php
                $nomer = 0;
                while ($rowl = mysqli_fetch_assoc($resultl)) {
                    if ($rowl['jurnal']) {
                        $nomer++;
                        ?>
                        <tr>
                            <?= $nomer; ?>.&nbsp;<i
                                class="badge badge-dark text-bg-dark"><?= $rowl['timestamp']; ?></i>&nbsp;<?= $rowl['jurnal']; ?><br>
                        </tr>
                        <?php
                    }
                }
                ?>
            </p>
        </div>
    <?php } else { ?>
        <h1>Belum ada foto hari ini</h1>
    <?php } ?>
    <div class="tampilanprevtbl mt-5">

        <?php if (!@$_SESSION["admin"]) { ?>
            <?php if (@$_GET['akses'] == 'presensi') { ?>
                <form id="uploadForm" action="presensi.php" method="post">
                    <input type="hidden" name="nis" value="<?= htmlspecialchars($nis); ?>">
                    <input type="hidden" name="akses" value="presensilagi">
                    <?php

                    $sql_datasiswa = "SELECT * FROM datasiswa WHERE nis = ?";
                    $stmt_datasiswa = mysqli_prepare($konek, $sql_datasiswa);
                    mysqli_stmt_bind_param($stmt_datasiswa, "s", $nis);
                    mysqli_stmt_execute($stmt_datasiswa);
                    $result_datasiswa = mysqli_stmt_get_result($stmt_datasiswa);
                    $row = mysqli_fetch_assoc($result_datasiswa);
                    $adadata = mysqli_num_rows($result_datasiswa);
                    // dpatkan nick sebagai sandi
                    $nick = $row["nama"];
                    $db_pass = $row["password"];
                    $nick_no_spaces = str_replace(' ', '', $nick);
                    $nick_lower = strtolower($nick_no_spaces);
                    $nick_8_chars = substr($nick_lower, 0, 8);

                    $password = isset($_POST['password']) ? $_POST['password'] : '';
                    $status_ganti_password = false;


                    // Jika password tidak ada di database, cek ke password default
                    if (empty($db_pass)) {
                        $password = $nick_8_chars;
                    } else {
                        $password = $db_pass;
                    }

                    ?>

                    <input type="hidden" name="password" value="<?= $password; ?>">
                    <input type="hidden" name="form" value="form">
                    <button type="submit" class="btn btn-success border-0 mb-3 w-100">
                        <i c lass="fa-solid fa-upload"></i>&nbsp; Upload Foto Kegiatan (Jurnal)
                    </button>
                </form>

                <script>
                    // JavaScrip      t to submit the form when the link is clicked
                    document.querySelector('.btn-success').addEventListener('click', function () {
                        document.getElementById('uploadForm').submit();
                    });
                </script>

            <?php } ?>
        <?php } ?>
        <?php mysqli_close($konek); ?>
        <!-- tombol rekap -->
        <a href="rekap.php?nis=<?= $nis; ?>&akses=rekapabsen" class="btn btn-info border-0 mb-3"><i
                class="fa-solid fa-file-alt"></i>&nbsp;
            Rekap Presensi <?= substr($nama_siswa, 0, 10); ?>.
        </a>

        <!-- tombol pembimbing -->
        <div class="input-group mb-3">
            <span class="input-group-text bg-dark text-light gradient">Pembimbing </span>
            <input type="text" class="form-control" value="<?= $data_lokasi["pembimbing"]; ?>" disabled>
            <?php
            $msg_nama = str_replace(" ", "%20", $nama_siswa);
            $msg_kelas = str_replace(" ", "%20", $kelas_siswa);
            $dudika_ = str_replace(" ", "%20", $dudika);
            $link_wa = "https://api.whatsapp.com/send?phone=" . $data_lokasi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0Atempat%20prakerin:%20" . $dudika_ . "%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20..";
            ?>
            <span class="input-group-text bg-success btn-success"><a href="<?= $link_wa; ?>" class="text-light"><i
                        class="fa-brands fa-whatsapp fa-beat"
                        style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a></span>
        </div>

        <!-- tombol kembali -->
        <a href="index.php" class="btn btn-dark border-0 mb-3"><i class="fa-solid fa-arrow-left"></i>&nbsp;
            Kembali
        </a>
    </div>
</div>

<div class="container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
    crossorigin="anonymous"></script>


<?php include "views/footer.php" ?>