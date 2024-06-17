<?php
session_start();
$title = "Data Siswa";
$admin = true;
include "../views/header.php";
include "../views/navbar.php";

if (@$_SESSION["admin"]) {
    include "../koneksi.php";

    $sql = "SELECT * FROM duditerisi ORDER BY timestamp DESC";
    $query = mysqli_query($konek, $sql);

?>

    <style>
        .tabel-recent {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            /*border: 1px solid #ddd;*/
            /*max-height: 500px;*/
        }

        .tabel-recent thead {
            background-color: #4CAF50;
            color: white;
            position: sticky;
            top: 0;
        }

        .tabel-recent th {
            border: #ddd solid 2px;
            text-align: center;
            padding: 8px;
        }

        .tabel-recent td {
            border: #ddd solid 1px;
        }
        
        .timestamp {
            font-size: 10px;
            color: #666;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin: 1px;
        }
        
        .tabel-recent h6 {
            font-size: 10px;
            font-weight: 400;
            color: dodgerblue;
            font-style: italic;
        }
        
        @media screen and (max-width: 768px) {
            .timestamp {
                font-size: 9px;
            }
            
            table, .btn {
                font-size: 12px;
            }
            
            .tabel-recent h6 {
                text-align: center;
            }
        }
    </style>

    <div class="container">
        <div class="tabel-recent table-responsive">
            <h6>
                <i class="fa-solid fa-circle-info"></i>&nbsp;
                klik header kolom tabel untuk mengurutkan sesuai kolom.
            </h6>
            
            <table id="recent" class="table">
                <thead>
                    <tr>
                        <th>
                            &nbsp;
                            <i class="fas fa-history fa-spin fa-spin-reverse">&nbsp;
                            <!--<i class="fa-solid fa-sync fa-spin"></i>-->
                        </th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>Nama DU/DI</th>
                        <th>Pembimbing</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($query as $key => $value) { ?>
                        <tr>
                            <td>
                                <?php
                                $tanggal = date("Y-m-d", strtotime($value["timestamp"]));
                                $jam = date("H:i:s", strtotime($value["timestamp"]));
                                ?>
                                <div class="timestamp">
                                    <span class="badge text-bg-secondary timestamp">
                                        <?= $tanggal; ?>
                                    </span>
                                    <span class="badge text-bg-secondary timestamp">
                                        <?= $jam; ?>
                                    </span>
                                </div>
                            </td>
                            <td><?= $value['nis']; ?></td>
                            <td><?= $value['namasiswa']; ?></td>
                            <td><?= $value['kelas']; ?></td>
                            <td><?= $value['gander']; ?></td>
                            <td><?= $value['namadudi']; ?></td>
                            <td><?= $value['pembimbing']; ?></td>
                            <td><button href='../index.php?akses=login_nis&nis=<?= $value["nis"]; ?>' class='btn btn-sm btn-primary btn-sm border-0' data-bs-toggle="modal" data-bs-target="#detail<?= $value['nis']; ?>"><i class="fa-solid fa-circle-info fa-beat"></i>&nbsp;Detail</button>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="detail<?= $value['nis']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="detail<?= $value['nis']; ?>Label" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detail<?= $value['nis']; ?>Label">Detail</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $kode_dudi = $value["kode"];

                                        $lokasi_query = "SELECT * FROM datadudi WHERE kode = '$kode_dudi'";
                                        $query_lokasi = mysqli_query($konek, $lokasi_query);
                                        $data_lokasi = mysqli_fetch_array($query_lokasi);
                                        $lokasi_dudi = $data_lokasi["map"];
                                        $kuota = $data_lokasi["kuotatoal"];
                                        ?>

                                        <h5><i class="fa-solid fa-circle-info"></i>&nbsp;Sudah terdaftar ke tempat Perakerin!</h5>

                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-light gradient">Nama </span>
                                            <input type="text" class="form-control" value="<?= $value["namasiswa"]; ?>" disabled>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-light gradient">NIS </span>
                                            <input type="text" class="form-control" value="<?= $value["nis"]; ?>" disabled>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-light gradient">Kelas </span>
                                            <input type="text" class="form-control" value="<?= $value["kelas"]; ?>" disabled>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-light gradient">Nama DU/Di </span>
                                            <input type="text" class="form-control" value="<?= $value["namadudi"]; ?>" disabled>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-light gradient">Alamat</span>
                                            <textarea class="form-control" rows="3" disabled><?= $value["alamat"]; ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <a href="<?= $lokasi_dudi; ?>" class="btn btn-outline-primary btn-sm" target="_blank"><i class="fa-solid fa-location-dot fa-bounce text-danger"></i>&nbsp;Lihat Lokasi di Maps</a>
                                        </div>
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-dark text-light gradient">Pembimbing </span>
                                            <input type="text" class="form-control" value="<?= $value["pembimbing"]; ?>" disabled>
                                            <?php
                                            $msg_nama = str_replace(" ", "%20", $value["namasiswa"]);
                                            $msg_kelas = str_replace(" ", "%20", $value["kelas"]);
                                            $link_wa = "https://api.whatsapp.com/send?phone=" . $data_lokasi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $value["namadudi"] . ".%0A";
                                            ?>
                                            <span class="input-group-text bg-success btn-success"><a href="<?= $link_wa; ?>" class="text-light"><i class="fa-brands fa-whatsapp fa-beat"></i></a></span>
                                        </div>

                                        <p>Telah memilih DU/DI ini: (Kuota Tersisa: <?= $kuota; ?>)</p>

                                        <?php

                                        $aql = "SELECT * FROM duditerisi WHERE kode LIKE '$value[kode]'";
                                        $hasil_telah_daftar = mysqli_query($konek, $aql);

                                        $i = 0;
                                        while ($hasil = mysqli_fetch_array($hasil_telah_daftar)) {
                                            $i++;
                                            echo "<p>" . $i . ". " . $hasil["namasiswa"] . " (" . $hasil["gander"] . ")" . " (" . $hasil["kelas"] . ")</p>";
                                        }

                                        $aql = "SELECT * FROM duditerisi WHERE kode LIKE '$value[kode]'";
                                        $query = mysqli_query($konek, $aql);
                                        $hasil = mysqli_fetch_array($query);
                                        ?>

                                        <a href="../print.php?akses=print&nis=<?= $value["nis"]; ?>&kode=<?= $hasil["kode"]; ?>" class="btn btn-info border-0"><i class="fa-solid fa-print fa-beat"></i>&nbsp;&nbsp;Cetak Surat Pernyataan</a>
                                        <button data-bs-dismiss="modal" aria-label="Close" class="btn btn-dark border-0 float-end"><i class="fa-solid fa-times"></i>&nbsp;Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
<div class="container">
    <!---->

    <?php
    // jumlahkan kolom kuota pada tabel datadudi
    $q3 = mysqli_query($konek, "SELECT SUM(kuotacow) AS kuotacow, SUM(kuotacew) AS kuotacew, SUM(kuotatoal) AS kuotatoal FROM datadudi");
    $data3 = mysqli_fetch_array($q3);

    // jumlahkan kolom id pada tabel duditerisi
    $q4 = mysqli_query($konek, "SELECT COUNT(id) AS id FROM duditerisi");
    $data4 = mysqli_fetch_array($q4);
    // jumlah "L" pada tabel duditerisi
    $q4_jml_L = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_l FROM duditerisi WHERE gander='L'");
    $data4_jml_L = mysqli_fetch_array($q4_jml_L);
    // jumlah "P" pada tabel duditerisi
    $q4_jml_P = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_p FROM duditerisi WHERE gander='P'");
    $data4_jml_P = mysqli_fetch_array($q4_jml_P);

    $sql5_jml_sis = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_siswa FROM datasiswa");
    $data5_jml_sis = mysqli_fetch_array($sql5_jml_sis);

    $sql6_jumlah_dudi_tersisa = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_dudi_tersisa FROM datadudi WHERE kuotatoal > 0");
    $data6_jumlah_dudi_tersisa = mysqli_fetch_array($sql6_jumlah_dudi_tersisa);

    $sql7_dudi_penuh = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_dudi_penuh FROM datadudi WHERE kuotatoal = 0");
    $data7_dudi_penuh = mysqli_fetch_array($sql7_dudi_penuh);

    $sql8_total_dudikan = mysqli_query($konek, "SELECT COUNT(id) AS jumlah_total_dudikan FROM datadudi");
    $data8_total_dudikan = mysqli_fetch_array($sql8_total_dudikan);

    $sql9_jumlah_siswa_L = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_siswa_L FROM datasiswa WHERE gander='L'");
    $data9_jumlah_siswa_L = mysqli_fetch_array($sql9_jumlah_siswa_L);

    $sql9_jumlah_siswa_P = mysqli_query($konek, "SELECT COUNT(gander) AS jumlah_siswa_P FROM datasiswa WHERE gander='P'");
    $data9_jumlah_siswa_P = mysqli_fetch_array($sql9_jumlah_siswa_P);
    ?>


    <style>
        .table-info {
            width: 600px;
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
                        <span class="badge text-bg-dark"><?= $data8_total_dudikan["jumlah_total_dudikan"]; ?></span>&nbsp;
                        DUDIKA
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-bell fa-shake text-info"></i>&nbsp;&nbsp;Total Seluruh Siswa(i)&nbsp;</td>
                    <td>:&nbsp;
                        <span class="badge text-bg-primary"><?= $data5_jml_sis['jumlah_siswa']; ?></span>&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span class="badge text-bg-primary"><?= $data9_jumlah_siswa_L['jumlah_siswa_L']; ?></span>&nbsp;
                        P:&nbsp;
                        <span class="badge text-bg-primary"><?= $data9_jumlah_siswa_P['jumlah_siswa_P']; ?></span>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-check fa-bounce text-success"></i>&nbsp;&nbsp;
                        Jumlah Siswa(i) telah terdaftar&nbsp;</td>
                    <td>
                        :&nbsp;
                        <span class="badge text-bg-info"><?= $data4["id"] ? $data4["id"] : " - Kosong"; ?></span>&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span class="badge text-bg-info"><?= $data4_jml_L["jumlah_l"] ? $data4_jml_L["jumlah_l"] : " - Kosong"; ?></span>&nbsp;
                        P:&nbsp;
                        <span class="badge text-bg-info"><?= $data4_jml_P["jumlah_p"] ? $data4_jml_P["jumlah_p"] : " - Kosong"; ?></span>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td>
                        <i class="fa-solid fa-triangle-exclamation fa-beat text-warning"></i>&nbsp;&nbsp;
                        Siswa(i) belum terdaftar&nbsp;
                    </td>
                    <td>:&nbsp;
                        <span class="badge text-bg-warning">
                            <?php $siswa_belum_pilih = $data5_jml_sis['jumlah_siswa'] - $data4["id"];
                            echo $siswa_belum_pilih; ?>
                        </span>&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span class="badge text-bg-warning">
                            <?php $siswa_belum_pilih_L = $data9_jumlah_siswa_L['jumlah_siswa_L'] - $data4_jml_L['jumlah_l'];
                            echo $siswa_belum_pilih_L; ?>
                        </span>&nbsp;
                        P:&nbsp;
                        <span class="badge text-bg-warning">
                            <?php $siswa_belum_pilih_P = $data9_jumlah_siswa_P['jumlah_siswa_P'] - $data4_jml_P['jumlah_p'];
                            echo $siswa_belum_pilih_P; ?>
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
                        <span class="badge text-bg-success"><?= $data3["kuotatoal"] ? $data3["kuotatoal"] : " - Kosong"; ?></span>&nbsp;
                        dari&nbsp;
                        <span class="badge text-bg-success"><?= $data6_jumlah_dudi_tersisa["jumlah_dudi_tersisa"]; ?></span>&nbsp;
                        DUDIKA
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;</td>
                    <td>
                        &nbsp;&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L:&nbsp;
                        <span class="badge text-bg-primary"><?= $data3["kuotacow"] ? $data3["kuotacow"] : " - Kosong"; ?></span>&nbsp;
                        P:&nbsp;
                        <span class="badge text-bg-primary"><?= $data3["kuotacew"] ? $data3["kuotacew"] : " - Kosong"; ?></span><br>&nbsp;&nbsp;
                        <i class="fa-solid fa-arrow-right"></i>&nbsp;
                        L/P:&nbsp;
                        <span class="badge text-bg-primary"><?= $data3["kuotatoal"] - ($data3["kuotacow"] + $data3["kuotacew"]); ?></span>&nbsp;
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
                        <span class="badge text-bg-danger"><?= $data7_dudi_penuh["jumlah_dudi_penuh"]; ?></span>
                    </td>
                </tr>
            </body>
        </table>
    </div>
    
    <!---->
    <br>
</div>

    <!--  -->
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>-->
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#recent').DataTable({
                dom: 'rBlftip',
                buttons: [
                    // 'print', 'excel', 'csv', 'pdf'
                    'print', 'excel'
                ],
                responsive: true,
                "lengthChange": true,
                "lengthMenu": [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "Semua"]
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
    </script>

<?php } else { ?>
    <script type="text/javascript">
        window.onload = () => {
            $('#adminlogin').modal('show');
        }
    </script>
<?php } ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php include "../views/footer.php" ?>