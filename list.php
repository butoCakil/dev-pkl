<?php
session_start();
include "views/header.php";
include "views/navbar.php";

$list = @$_GET['list'];
?>

<?php
include "koneksi.php";

// Inisialisasi variabel $list
$list = isset($_GET['list']) ? $_GET['list'] : '';

// Fungsi untuk memeriksa karakter mencurigakan
function isSuspicious($value)
{
    // Contoh: hanya huruf dan angka yang diperbolehkan
    return !preg_match('/^[a-zA-Z0-9]+$/', $value);
}

if (!empty($list)) {
    // Memeriksa karakter mencurigakan
    if (isSuspicious($list)) {
        // Kosongkan variabel $list
        $list = '';
        // Tampilkan alert menggunakan JavaScript
        echo '<script>alert("Detected suspicious characters in input.");';
        // Redirect menggunakan JavaScript setelah alert
        echo 'window.location.href = "list.php";</script>';
        exit; // Pastikan untuk keluar dari skrip PHP setelah redirect
    } else {
        // Escape variabel $list untuk mengamankan dari SQL injection
        $list = mysqli_real_escape_string($konek, $list);
        $and_list = " AND jur = '$list'";
        $where_list = " WHERE jur = '$list'";
        $query = "SELECT * FROM datadudi WHERE (status = 'prakerin' OR status = 'semua') AND jur = '$list' ORDER BY namadudi ASC";
        $q = mysqli_query($konek, $query);
    }
} else {
    $and_list = "";
    $where_list = "";
    $query = "SELECT * FROM datadudi WHERE (status = 'prakerin' OR status = 'semua') ORDER BY namadudi ASC";
    $q = mysqli_query($konek, $query);
}


$title = "List Data DU/DI";
$admin = false;
?>

<style>
    table,
    .btn,
    label {
        font-size: 12px;
    }

    .head {
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 2px 3px 2px 2px rgba(0, 0, 0, 0.2);
    }

    .judul_list h6 {
        font-size: 12px;
    }

    .logo_1 {
        margin-top: -5px;
        margin-bottom: -5px;
    }


    @media screen and (min-width: 768px) {
        .sembunyi {
            display: none;
        }

        .head {
            background: none;
            border: none;
            box-shadow: none;
            display: flex;
            flex-direction: column;
        }

        #tabel_list_2 {
            display: none;
        }
    }

    @media screen and (max-width: 768px) {
        #tabel_list_1 {
            display: none;
        }
    }
</style>

<div class="container">
    <div class="head">
        <div class="judul_list mt-2">
            <h5>DAFTAR DUDIKA</h5>
            <h6>PRAKTIK KERJA INDUSTRI (Prakerin)</h6>
        </div>
        <div class="col-md-3 sembunyi">
            <img src="TE1.gif" class="logo_1">
        </div>
        <div class="judul_list">
            <!-- <h6>KOMPETENSI KEAHLIAN TEKNIK ELEKTRONIKA</h6> -->
            <h6>SMK NEGERI BANSARI TAHUN 2023 / 2024</h6>
            <div class="alert alert-info m-1 p-1">
                <b><?= @$_GET['list'] ? "Jurusan " . $_GET['list'] : "Semua Jurusan"; ?></b>
            </div>

            <div class="d-flex justify-content-center p-1">

                <a href="javascript:window.history.go(-1);" class="btn btn-sm btn-dark border-0 mb-0"><i
                        class="fa-solid fa-chevron-left fa-beat"></i>&nbsp;Kembali</a>
                <!-- <a href="/" class="btn btn-sm btn-primary border-0 mb-3"><i class="fa-solid fa-right-to-bracket fa-beat-fade"></i>&nbsp;Pendaftaran</a> -->

                <div class="dropdown mx-1">
                    <button class="btn btn-secondary border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Pilih Tampilan Jurusan
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="list.php">Semua</a></li>
                        <li><a class="dropdown-item" href="list.php?list=AT">AT</a></li>
                        <li><a class="dropdown-item" href="list.php?list=DKV">DKV</a></li>
                        <li><a class="dropdown-item" href="list.php?list=TE">TE</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
        Informasi: Silakan untuk survey ke tempat Prakerin yang ada pada List untuk informasi lebih. Atau bisa mencari tempat prakerin sendiri kemudian dilaporkan dan dikonfirmasikan ke pak Arif.
        <?php $link_wa = "https://api.whatsapp.com/send?phone=" . "6287735512475" . "&text=Assalamu'alaikum,%0ASaya:%20%0AKelas:%20%0Atempat%20prakerin:%20%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20melaporkan%20soal%20prakerin.%20"; ?>
        <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i class="fa-brands fa-whatsapp text-light"></i>&nbsp;Chat</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> -->
    <br>

    <div id="tabel_list_1" class="table-responsive">
        <table id="tabeldata" class="table table-striped table-bordered">
            <thead class="table-dark align-middle">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">DUDIKA</th>
                    <th scope="col">Sisa Kuota (L/P)</th>
                    <th scope="col">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Daftar&nbsp;Siswa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </th>
                    <th scope="col">Pembimbing<button
                            onclick="alert('Klik tombol berlogo WhatsApp pada nama pembimbing untuk menghubungi pembimbing melailui chat WA!');"
                            class="btn btn-sm btn-success border-0">No. WA</button></th>
                    <th scope="col">Jur</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Lokasi&nbsp;Maps</th>
                    <th scope="col">Kos</th>
                    <th scope="col">Biaya Bimbingan</th>
                    <th scope="col">Biaya Hidup</th>
                    <?php if (@$_SESSION["admin"]) { ?>
                        <th scope="col">Cetak Surat</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 0;
                while ($data = mysqli_fetch_array($q)) {
                    $no++;

                    $kode = $data['kode'];

                    $q2 = mysqli_query($konek, "SELECT * FROM duditerisi WHERE kode='$kode'");
                    ?>
                    <tr>
                        <th scope="row"><?= $no; ?></th>
                        <td>
                            <?= $data["namadudi"]; ?><br>
                            (<?= $data["kota"]; ?>)
                            <?php
                            if ($data["status"] == "prakerin") {
                                echo "<i class='fa-solid fa-helmet-safety'></i>";
                            } elseif ($data["status"] == "magang") {
                                echo "<i class='fa-solid fa-briefcase'></i>";
                            } elseif ($data["status"] == "semua") {
                                echo "<i class='fa-solid fa-helmet-safety'></i>";
                                echo "&nbsp;";
                                echo "<i class='fa-solid fa-briefcase'></i>";
                                // echo "<i class='fa-solid fa-globe-asia'></i>";
                            }
                            ?>

                        </td>
                        <td><?= @$data["kuotatoal"] ? $data["kuotatoal"] : '<span class="badge text-bg-warning">Penuh</span>'; ?>
                            <?php if ($data["kuotacow"] > 0 || $data["kuotacew"] > 0) { ?>
                                (<?= $data["kuotacow"]; ?>/<?= $data["kuotacew"]; ?>)
                            <?php } ?>
                        </td>
                        <td>
                            <?php
                            $nomor = 0;
                            $jumlah_data_siswa = mysqli_num_rows($q2);
                            while ($data_siswa = mysqli_fetch_array($q2)) {
                                $nomor++;
                                echo "<span class='badge text-bg-dark'>" . $nomor . ".</span>&nbsp;" . "<span class='badge text-bg-secondary'>" . $data_siswa["kelas"] . "</span>" . "&nbsp;<span class='badge text-bg-info'>" . $data_siswa['nis'] . "</span>&nbsp;<span class='badge text-bg-warning'>" . $data_siswa["gander"] . "</span><br>" . $data_siswa["namasiswa"] . "<br><br>";
                            }

                            if ($jumlah_data_siswa == 0) {
                                echo "<span class='badge text-bg-danger'>Belum Ada Siswa terdaftar</span>";
                            }
                            ?>
                        </td>
                        <td>
                            <?= $data["pembimbing"]; ?><br>
                            <?php
                            $link_wa = "https://api.whatsapp.com/send?phone=" . @$data["nowa"] . "&text=Assalamu'alaikum,%20maaf%20menggangu,%0ASaya:%20%0AKelas:%20%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $data["namadudi"] . ".%0A";
                            ?>
                            <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i
                                    class="fa-brands fa-whatsapp fa-beat"
                                    style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                        </td>
                        <td><?= $data["jur"]; ?></td>
                        <td><?= $data["ket"]; ?></td>
                        <td><?= $data["alamat"]; ?></td>
                        <td>
                            <?php if (($data["map"]) && ($data["map"] != "-")) { ?>
                                <a href="<?= $data["map"]; ?>" class="btn btn-sm btn-outline-primary" target="_blank"><i
                                        class="fa-solid fa-location-dot fa-bounce text-danger"></i>
                                    Lihat&nbsp;Maps</a>
                            <?php } ?>
                        </td>
                        <td><?= $data["kos"]; ?></td>
                        <td><?= $data["beabim"]; ?></td>
                        <td><?= $data["beahidup"]; ?></td>
                        <?php if (@$_SESSION["admin"] || $admin == true) { ?>
                            <td>
                                <a href="printkonfirmasi.php?aksi=print2&kode=<?= $kode; ?>"
                                    class="btn btn-info border-0 btn-sm m-1" target="_blank">
                                    <i class="fa-solid fa-print fa-shake"></i>
                                    Konfirmasi
                                </a>
                                <a href="printsuratijin.php?aksi=print3&kode=<?= $kode; ?>"
                                    class="btn btn-primary border-0 btn-sm m-1" target="_blank">
                                    <i class="fa-solid fa-print fa-shake"></i>
                                    Surat&nbsp;Ijin
                                </a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <style>
        #tabel_list_2 #tabeldata2 tbody #kota .btn {
            width: 100%;
        }
    </style>

    <div id="tabel_list_2" class="table-responsive">
        <table id="tabeldata2" class="table table-striped table-bordered">
            <thead class="table-dark align-middle">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">DUDIKA</th>
                    <th scope="col">Sisa Kuota (L/P)</th>
                    <th scope="col">Lokasi&nbsp;Maps (Kota)</th>
                    <th scope="col">Detail</th>
                    <?php if (@$_SESSION["admin"]) { ?>
                        <th scope="col">Cetak&nbsp;Surat</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $qq = mysqli_query($konek, "SELECT * FROM datadudi ORDER BY namadudi ASC");
                $nom = 0;
                while ($data_a = mysqli_fetch_array($qq)) {
                    $nom++;

                    $kode = $data_a['kode'];

                    $q2 = mysqli_query($konek, "SELECT * FROM duditerisi WHERE kode='$kode'");
                    ?>
                    <tr>
                        <th scope="row"><?= $nom; ?></th>
                        <td><?= $data_a["namadudi"]; ?></td>
                        <td><?= @$data_a["kuotatoal"] ? $data_a["kuotatoal"] : '<span class="badge text-bg-warning">Penuh</span>'; ?>
                            <?php if ($data_a["kuotacow"] > 0 || $data_a["kuotacew"] > 0) { ?>
                                (<?= $data_a["kuotacow"]; ?>/<?= $data_a["kuotacew"]; ?>)
                            <?php } ?>
                        </td>
                        <td id="kota"><a href="<?= $data_a["map"]; ?>" class="btn btn-sm btn-outline-primary"
                                target="_blank"><i class="fa-solid fa-location-dot fa-bounce text-danger"></i><br>
                                <?= $data_a["kota"]; ?></a>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm border-0" data-bs-toggle="modal"
                                data-bs-target="#detaildudikalist<?= $nom; ?>" type="button">
                                <i class="fa-solid fa-info-circle fa-beat"></i>&nbsp;
                                Detail
                            </button>
                        </td>

                        <?php if (@$_SESSION["admin"] || $admin == true) { ?>
                            <td>
                                <a href="printkonfirmasi.php?aksi=print2&kode=<?= $kode; ?>"
                                    class="btn btn-info border-0 btn-sm m-1" target="_blank">
                                    <i class="fa-solid fa-print fa-shake"></i>&nbsp;Konfirmasi
                                </a>
                                <a href="printsuratijin.php?aksi=print3&kode=<?= $kode; ?>"
                                    class="btn btn-primary border-0 btn-sm m-1" target="_blank">
                                    <i class="fa-solid fa-print fa-shake"></i>&nbsp;Surat&nbsp;Ijin
                                </a>
                            </td>
                        <?php } ?>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="detaildudikalist<?= $nom; ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="detaildudikalist<?= $nom; ?>Label"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detaildudikalist<?= $nom; ?>Label">Detail info DUDIKA</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <?php
                                    $daftardudi = "SELECT * FROM datadudi WHERE kode = '$kode'";
                                    $querydudi = mysqli_query($konek, $daftardudi);
                                    $rowdudi = mysqli_fetch_assoc($querydudi);

                                    // mencari data dudi
                                    $telahdaftar = "SELECT * FROM duditerisi WHERE kode LIKE '$kode'";
                                    $querytelahdaftar = mysqli_query($konek, $telahdaftar);
                                    $jumlahtelahdaftar = mysqli_num_rows($querytelahdaftar);
                                    ?>
                                    <div>
                                        <div class="alert alert-info" role="alert">
                                            <p>
                                                <?php $namadudi_temp = $rowdudi["namadudi"]; ?>
                                                <span class="badge text-bg-primary"><i
                                                        class="fa-solid fa-briefcase"></i>&nbsp;&nbsp;Nama DU/DI :
                                                </span><br><?= $rowdudi["namadudi"]; ?> <br>
                                                <span class="badge text-bg-primary"><i
                                                        class="fa-solid fa-circle-question"></i>&nbsp;&nbsp;Keterangan :
                                                </span><br><?= $rowdudi["ket"]; ?> <br>
                                                <span class="badge text-bg-primary"><i
                                                        class="fa-solid fa-map-location-dot"></i>&nbsp;&nbsp;Alamat :
                                                </span><br><?= $rowdudi["alamat"]; ?> <br>
                                                <span class="badge text-bg-light text-primary"><i
                                                        class="fa-solid fa-location-crosshairs fa-beat"></i></span>&nbsp;<span
                                                    class="badge text-bg-light text-primary"><i
                                                        class="fa-solid fa-location-arrow fa-shake"></i></span>&nbsp;<a
                                                    id="btn_loc_1" href="<?= $rowdudi["map"]; ?>"
                                                    class="btn btn-light btn-sm border-0" target="_blank"><i
                                                        class="fa-solid fa-location-dot text-danger fa-bounce"></i>&nbsp;Lihat
                                                    Lokasi</a><br><br>
                                                <span class="badge text-bg-secondary"><i
                                                        class="fa-solid fa-house"></i>&nbsp;&nbsp;Info Kos :
                                                </span><br><?= $rowdudi["kos"]; ?> <br>
                                                <span class="badge text-bg-secondary"><i
                                                        class="fa-solid fa-money-bill-trend-up"></i>&nbsp;&nbsp;Biaya
                                                    Bimbingan : </span><br><?= $rowdudi["beabim"]; ?> <br>
                                                <span class="badge text-bg-secondary"><i
                                                        class="fa-solid fa-money-bill-wheat"></i>&nbsp;&nbsp;Biaya Hidup :
                                                </span><br><?= $rowdudi["beahidup"]; ?> <br><br>
                                                <span class="badge text-bg-dark"><i
                                                        class="fa-solid fa-user-gear"></i>&nbsp;&nbsp;Pembimbing :
                                                </span><br>
                                                <?= $rowdudi["pembimbing"] ? $rowdudi["pembimbing"] : "-"; ?>

                                                <?php
                                                $link_wa = "https://api.whatsapp.com/send?phone=" . @$rowdudi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20," . "%0AKelas:%20" . "%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $rowdudi["namadudi"] . ".%0A";
                                                ?>
                                                <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i
                                                        class="fa-brands fa-whatsapp fa-bounce"></i></a>
                                                <br><br>
                                                Sisa kuota : <span
                                                    class="badge text-bg-danger"><?= @$rowdudi["kuotatoal"] ? $rowdudi["kuotatoal"] : 'Penuh!'; ?></span>
                                                <br>

                                                <?php
                                                if (@$rowdudi["kuotatoal"]) {
                                                    $tmbl_daftar = "";
                                                } else {
                                                    $tmbl_daftar = "d-none";
                                                }
                                                ?>

                                                <?php if ($rowdudi["kuotacow"] || $rowdudi["kuotacew"]) { ?>
                                                    Kuota Laki-laki (L) : <span
                                                        class="badge text-bg-primary"><?= $rowdudi["kuotacow"] ? $rowdudi["kuotacow"] : "-"; ?></span><br>
                                                    Kuota Perempuan (P) : <span
                                                        class="badge text-bg-warning"><?= $rowdudi["kuotacew"] ? $rowdudi["kuotacew"] : "-"; ?></span><br>
                                                <?php } ?>

                                                <br>
                                                Jumlah Siswa yang telah terdaftar :
                                                <?= $jumlahtelahdaftar ? $jumlahtelahdaftar : '<span class="badge text-bg-success">- Belum ada yang memilih -</span>'; ?><br>

                                                <?php
                                                $no = 0;
                                                while ($rowtelahdaftar = mysqli_fetch_assoc($querytelahdaftar)) {
                                                    $no++;
                                                    ?>
                                                    <?= $no; ?>. <?= $rowtelahdaftar["namasiswa"]; ?>
                                                    (<?= $rowtelahdaftar["gander"]; ?>) (<?= $rowtelahdaftar["kelas"]; ?>) <br>
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <style>
                                    .ngiri {
                                        position: absolute;
                                        left: 0;
                                        margin-left: 20px;
                                    }
                                </style>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">
                                        <i class="fa-solid fa-times fa-beat"></i>&nbsp;&nbsp;Tutup</button>
                                    <a href="index.php?kodedudi_next=<?= $kode; ?>&akses_next=cektempat" type="button"
                                        class="btn btn-primary border-0 ngiri <?= $tmbl_daftar; ?>">
                                        <i class="fa-solid fa-check fa-bounce"></i>&nbsp;&nbsp;
                                        Daftar di sini</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <!---->

    <?php
    // jumlahkan kolom kuota pada tabel datadudi
    $q3 = mysqli_query($konek, "SELECT SUM(kuotacow) AS kuotacow, SUM(kuotacew) AS kuotacew, SUM(kuotatoal) AS kuotatoal FROM datadudi" . $where_list);
    $data3 = mysqli_fetch_array($q3);

    // jumlahkan kolom id pada tabel duditerisi
    $q4 = mysqli_query($konek, "SELECT COUNT(id) AS id FROM duditerisi" . $where_list);
    $data4 = mysqli_fetch_array($q4);
    // jumlah "L" pada tabel duditerisi
    $q4_jml_L = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_l FROM duditerisi WHERE gander='L'" . $and_list);
    $data4_jml_L = mysqli_fetch_array($q4_jml_L);
    // jumlah "P" pada tabel duditerisi
    $q4_jml_P = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_p FROM duditerisi WHERE gander='P'" . $and_list);
    $data4_jml_P = mysqli_fetch_array($q4_jml_P);

    $sql5_jml_sis = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_siswa FROM datasiswa" . $where_list);
    $data5_jml_sis = mysqli_fetch_array($sql5_jml_sis);

    $sql6_jumlah_dudi_tersisa = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_dudi_tersisa FROM datadudi WHERE kuotatoal > 0" . $and_list);
    $data6_jumlah_dudi_tersisa = mysqli_fetch_array($sql6_jumlah_dudi_tersisa);

    $sql7_dudi_penuh = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_dudi_penuh FROM datadudi WHERE kuotatoal = 0" . $and_list);
    $data7_dudi_penuh = mysqli_fetch_array($sql7_dudi_penuh);

    $sql8_total_dudikan = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_total_dudikan FROM datadudi" . $where_list);
    $data8_total_dudikan = mysqli_fetch_array($sql8_total_dudikan);

    $sql9_jumlah_siswa_L = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_siswa_L FROM datasiswa WHERE gander='L'" . $and_list);
    $data9_jumlah_siswa_L = mysqli_fetch_array($sql9_jumlah_siswa_L);

    $sql9_jumlah_siswa_P = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_siswa_P FROM datasiswa WHERE gander='P'" . $and_list);
    $data9_jumlah_siswa_P = mysqli_fetch_array($sql9_jumlah_siswa_P);
    ?>


    <style>
        .table-info {
            width: 400px;
        }
    </style>

    <div class="mt-3 table-responsive table-info">
        <table class="table table-borderless">

            <body>
                <tr>
                    <td>
                        <i class="fa-solid fa-bell fa-shake text-info"></i>&nbsp;&nbsp;
                        Total Seluruh Kuota&nbsp;
                    </td>
                    <td>:&nbsp;
                        <span class="badge text-bg-dark"><?= @$data3["kuotatoal"] + @$data4["id"]; ?></span>&nbsp;
                        dari&nbsp;
                        <span
                            class="badge text-bg-dark"><?= @$data8_total_dudikan["jumlah_total_dudikan"]; ?></span>&nbsp;
                        DUDIKA
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-bell fa-shake text-info"></i>&nbsp;&nbsp;Total Seluruh Siswa(i)&nbsp;</td>
                    <td>:&nbsp;
                        <span class="badge text-bg-primary"><?= @$data5_jml_sis['jumlah_siswa']; ?></span>&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span
                            class="badge text-bg-primary"><?= @$data9_jumlah_siswa_L['jumlah_siswa_L']; ?></span>&nbsp;
                        P:&nbsp;
                        <span
                            class="badge text-bg-primary"><?= @$data9_jumlah_siswa_P['jumlah_siswa_P']; ?></span>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-check fa-bounce text-success"></i>&nbsp;&nbsp;
                        Jumlah Siswa(i) telah terdaftar&nbsp;</td>
                    <td>
                        :&nbsp;
                        <span class="badge text-bg-info"><?= $data4["id"] ? $data4["id"] : 0; ?></span>&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span
                            class="badge text-bg-info"><?= $data4_jml_L["jumlah_l"] ? $data4_jml_L["jumlah_l"] : 0; ?></span>&nbsp;
                        P:&nbsp;
                        <span
                            class="badge text-bg-info"><?= $data4_jml_P["jumlah_p"] ? $data4_jml_P["jumlah_p"] : 0; ?></span>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fa-solid fa-triangle-exclamation fa-beat text-warning"></i>&nbsp;&nbsp;
                        Siswa(i) belum terdaftar&nbsp;
                    </td>
                    <td>:&nbsp;
                        <span class="badge text-bg-warning">
                            <?php $siswa_belum_pilih = @$data5_jml_sis['jumlah_siswa'] - @$data4["id"];
                            echo ($siswa_belum_pilih > 0) ? $siswa_belum_pilih : 0; ?>
                        </span>&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span class="badge text-bg-warning">
                            <?php $siswa_belum_pilih_L = @$data9_jumlah_siswa_L['jumlah_siswa_L'] - @$data4_jml_L['jumlah_l'];
                            echo ($siswa_belum_pilih_L > 0) ? $siswa_belum_pilih_L : 0; ?>
                        </span>&nbsp;
                        P:&nbsp;
                        <span class="badge text-bg-warning">
                            <?php $siswa_belum_pilih_P = @$data9_jumlah_siswa_P['jumlah_siswa_P'] - @$data4_jml_P['jumlah_p'];
                            echo ($siswa_belum_pilih_P > 0) ? $siswa_belum_pilih_P : 0; ?>
                        </span>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>
                        <i class="fa-solid fa-bell fa-shake text-info"></i>&nbsp;&nbsp;
                        Jumlah Kuota Tersisa&nbsp;
                    </td>
                    <td>
                        :&nbsp;
                        <span
                            class="badge text-bg-success"><?= @$data3["kuotatoal"] ? $data3["kuotatoal"] : 0; ?></span>&nbsp;
                        dari&nbsp;
                        <span
                            class="badge text-bg-success"><?= ($data6_jumlah_dudi_tersisa["jumlah_dudi_tersisa"] > 0) ? $data6_jumlah_dudi_tersisa["jumlah_dudi_tersisa"] : 0; ?></span>&nbsp;
                        DUDIKA
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;</td>
                    <td>
                        &nbsp;&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span
                            class="badge text-bg-primary"><?= $data3["kuotacow"] ? $data3["kuotacow"] : 0; ?></span>&nbsp;
                        P:&nbsp;
                        <span
                            class="badge text-bg-primary"><?= $data3["kuotacew"] ? $data3["kuotacew"] : 0; ?></span><br>&nbsp;&nbsp;
                        <?php if ((@$data3["kuotatoal"] - (@$data3["kuotacow"] + @$data3["kuotacew"])) > 0) { ?>
                            <i class="fa-solid fa-arrow-right"></i>&nbsp;
                            L/P:&nbsp;
                            <span
                                class="badge text-bg-primary"><?= @$data3["kuotatoal"] - (@$data3["kuotacow"] + @$data3["kuotacew"]); ?></span>&nbsp;
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-bell fa-shake text-info"></i>&nbsp;&nbsp;
                        DUDIKA Penuh
                    </td>
                    <td>
                        :&nbsp;
                        <span
                            class="badge text-bg-danger"><?= ($data7_dudi_penuh["jumlah_dudi_penuh"] > 0) ? $data7_dudi_penuh["jumlah_dudi_penuh"] : 0; ?></span>
                    </td>
                </tr>
            </body>
        </table>
    </div>

    <!---->
    <br>
</div>

<div class="container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
    crossorigin="anonymous"></script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#tabeldata').DataTable({
            dom: 'rBlfitp',
            buttons: [
                // 'print', 'excel', 'csv', 'pdf'
                'excel'
            ],
            responsive: true,
            "lengthChange": true,
            "lengthMenu": [
                [-1, 10, 20, 30, 50, -1],
                ["Semua", 10, 20, 30, 50, "Semua"]
            ],
            "pagingType": "full",
            "language": {
                "emptyTable": "Data tidak ditemukan.",
                "info": "Ditampilkan _START_ sampai _END_, dari _TOTAL_ baris data",
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
        }),
            $('#tabeldata2').DataTable({
                dom: 'rlfitp',
                responsive: true,
                "lengthChange": true,
                "lengthMenu": [
                    [-1, 10, 15, 25, 50, -1],
                    ["Semua", 10, 15, 25, 50, "Semua"]
                ],
                "pagingType": "full",
                "language": {
                    "emptyTable": "Data tidak ditemukan.",
                    "info": "Ditampilkan _START_ sampai _END_, dari _TOTAL_ baris data",
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
            });;
    });
</script>
<?php include "views/footer.php" ?>