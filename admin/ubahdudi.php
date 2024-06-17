<?php
session_start();
$title = "Ubah Data DUDIKA";
$admin = true;
include "../views/header.php";
include "../views/navbar.php";

if (@$_SESSION["admin"]) {
    if (@$_SESSION["admin"] == "admin") {


    include "../koneksi.php";

    $q = mysqli_query($konek, "SELECT * FROM datadudi ORDER BY namadudi ASC");
    $title = "List Data DUDIKA";
    $admin = false;
?>

    <style>
        @media screen and (max-width: 768px) {

            table,
            .btn,
            label {
                font-size: 12px;
            }
        }
    </style>
    <div class="container">
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

        <div class="judul_list">
            <h4>
                <span class="badge text-bg-warning">
                    <!-- icon edit -->
                    <i class="fas fa-edit fa-flip"></i>&nbsp;
                    UBAH
                </span>
                <br>
                DAFTAR DUDIKA
            </h4>

            <a href="../admin/" class="btn btn-sm btn-dark border-0 mb-3"><i class="fa-solid fa-chevron-left"></i>&nbsp;Kembali</a>
        </div>
        <br>

        <div class="table-responsive">
            <table id="tabeldata" class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">DUDIKA</th>
                        <th scope="col">Sisa Kuota (L/P)</th>
                        <th scope="col">Pembimbing (CP)</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Lokasi Maps</th>
                        <th scope="col">Kos</th>
                        <th scope="col">Biaya</th>
                        <!--<th scope="col">Status</th>-->
                        <!--<th scope="col">Jur.</th>-->
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    while ($data = mysqli_fetch_array($q)) {
                        $no++;

                        if ($data['status'] == "hidden") {
                            $hidden_row = 'class="text-secondary fst-italic"';
                        } else {
                            $hidden_row = "";
                        }
                    ?>
                        <tr <?= $hidden_row; ?>>
                            <th scope="row"><?= $no; ?></th>
                            <td>
                                <?= $data["namadudi"]; ?><br>
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
                                } elseif ($data["status"] == 'hidden') {
                                    echo "<i class='fa-solid fa-eye-slash'></i>";
                                }
                                ?>
                            </td>
                            <td><?= $data["kuotatoal"]; ?>
                                <?php if ($data["kuotacow"] > 0 || $data["kuotacew"] > 0) { ?>
                                    (<?= $data["kuotacow"]; ?>/<?= $data["kuotacew"]; ?>)
                                <?php } ?>
                            </td>
                            <td>
                                <?= $data["pembimbing"]; ?><br>
                                <?php
                                if(@$data["nowa"]){
                                    $link_wa = "https://api.whatsapp.com/send?phone=" . @$data["nowa"] . "&text=Assalamu'alaikum,%20maaf%20menggangu,%0ASaya:%20%0AKelas:%20%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $data["namadudi"] . ".%0A";
                                } 
                                ?>
                                <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i class="fa-brands fa-whatsapp fa-beat"></i></a>
                            </td>

                            <td><?= $data["ket"]; ?></td>
                            <td><?= $data["alamat"]; ?></td>
                            <td>
                                 <?php if ($data["map"]) { ?>
                                 <a href="<?= $data["map"]; ?>" class="btn btn-sm btn-outline-primary" target="_blank"><i class="fa-solid fa-location-dot fa-bounce text-danger"></i>&nbsp;Lihat Maps</a>
                                 <?php } ?>
                            </td>
                            <td><?= $data["kos"]; ?></td>
                            <td>
                                <span class="badge text-bg-info">Bimbingan:</span> <?= $data["beabim"]; ?><br>
                                <span class="badge text-bg-info">Bea Hidup:</span> <?= $data["beahidup"]; ?>
                            </td>
                            <!--<td><?= $data["status"]; ?></td>-->
                            <!--<td><?= $data["jur"]; ?></td>-->
                            <td>
                                <a href="tambahdudi.php?kode=<?= $data["kode"]; ?>&edit=dudi" class="btn btn-sm btn-warning m-2 border-0"><i class="fa-solid fa-edit fa-shake"></i>&nbsp;Ubah</a>

                                <form action="trash.php" method="POST">
                                    <input type="hidden" name="kode" value="<?= $data["kode"]; ?>">
                                    <button type="submit" name="hapusdatadudi" value="hapus" class="btn btn-sm btn-danger m-2 border-0" onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="fa-solid fa-trash fa-fade"></i>&nbsp;Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <br><br>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#tabeldata').DataTable({
                dom: 'rBlftip',
                buttons: [
                    // 'print', 'excel', 'csv', 'pdf'
                    'excel'
                ],
                responsive: true,
                "lengthChange": true,
                "lengthMenu": [
                    [-1, 5, 10, 15, 25, 50, -1],
                    ["Semua", 5, 10, 15, 25, 50, "Semua"]
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

    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>-->

<?php
    $admin = true;
    include "../views/footer.php";
    } else {
            echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../admin';
        </script>";
    }
} else { ?>
    <script type="text/javascript">
        window.onload = () => {
            $('#adminlogin').modal('show');
        }
    </script>
<?php } ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php include "../views/footer.php" ?>