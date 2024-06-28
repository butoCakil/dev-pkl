<?php
session_start();
$title = "Data Siswa";
$admin = true;
include "../views/header.php";
include "../views/navbar.php";

if (isset($_SESSION["admin"])) {
    include "../koneksi.php";

    $aql_siswa = "SELECT * FROM datasiswa ORDER BY nis ASC";
    $result_siswa = mysqli_query($konek, $aql_siswa);
    ?>

    <style>
        h4 {
            text-align: center;
            margin-bottom: 10px;
        }

        @media screen and (max-width: 768px) {

            table,
            .btn {
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

        <a href="../admin/" class="btn btn-secondary border-0">
            <i class="fas fa-arrow-left"></i>&nbsp;
            Kembali
        </a>
        <div class="mx-5">
            <h4>Data Siswa di DUDIKA</h4>
        </div>
        <div class="table-responsive">
            <table id="tabeldatasiswa" class="table mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>DUDIKA</th>
                        <th>Pembimbing</th>
                        <?php if (@$_SESSION['admin'] == 'admin') { ?>
                            <th>Aksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 0;
                    while ($data = mysqli_fetch_assoc($result_siswa)) {
                        $no++;
                        $nis = $data['nis'];
                        $namasiswa = $data["nama"];
                        $gandersiswa = $data["gander"];

                        $sql_pembimbing = "SELECT * FROM duditerisi WHERE nis = ?";
                        $stmt_pembimbing = mysqli_prepare($konek, $sql_pembimbing);

                        // Bind parameter ke statement
                        mysqli_stmt_bind_param($stmt_pembimbing, "s", $nis);

                        // Eksekusi statement
                        mysqli_stmt_execute($stmt_pembimbing);

                        // Ambil hasil query
                        $result_pembimbing = mysqli_stmt_get_result($stmt_pembimbing);
                        $data_pembimbing = mysqli_fetch_assoc($result_pembimbing);

                        $namadudi = @$data_pembimbing["namadudi"] ? $data_pembimbing["namadudi"] : "-";
                        $kode_dudi = @$data_pembimbing["kode"] ? $data_pembimbing["kode"] : "-";

                        $sql_pembimbing = "SELECT * FROM datadudi WHERE kode = '$kode_dudi'";
                        $query_pembimbing = mysqli_query($konek, $sql_pembimbing);
                        $hasil_pembimbing = mysqli_fetch_assoc($query_pembimbing);
                        $namapembimbing = @$hasil_pembimbing["pembimbing"] ? $hasil_pembimbing["pembimbing"] : "-";
                        ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <td><?= $nis; ?></td>
                            <td>
                                <?= $namasiswa; ?>
                                <?php
                                if ($data["nohp"] && $data["nohp"] != "-") {
                                    $link_wa = "https://api.whatsapp.com/send?phone=" . @$data["nohp"];
                                    ?>
                                    <br><a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i
                                            class="fa-brands fa-whatsapp"
                                            style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                                <?php } ?>
                            </td>
                            <td><span class="badge text-bg-info"><?= $data["kelas"]; ?></span></td>
                            <td><?= $gandersiswa; ?></td>
                            <td><?= $namadudi; ?></td>
                            <td><?= $namapembimbing; ?></td>

                            <?php if (@$_SESSION['admin'] == 'admin') { ?>
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm m-1 border-0" data-bs-toggle="modal"
                                        data-bs-target="#modaldatasiswa<?= $nis; ?>">
                                        <i class="fas fa-edit fa-beat"></i>
                                    </button>

                                    <form action="hapusdudisiswa.php" method="post">
                                        <input type="hidden" name="nis" value="<?= $nis; ?>">
                                        <button type="submit" name="hapusdudisiswa" value="hapusdudisiswa"
                                            class="btn btn-danger btn-sm m-1 border-0"
                                            onclick="return confirm('Pilihan DUDIKA dari siswa ini akan dihapus. Lanjutkan?')">
                                            <i class="fas fa-trash fa-shake"></i>
                                        </button>
                                    </form>


                                    <!-- <a href="hapusdudisiswa.php" class="btn btn-danger btn-sm m-1 border-0">
                                    <i class="fas fa-trash"></i>&nbsp;
                                    Hapus
                                </a> -->
                                </td>
                            <?php } ?>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="modaldatasiswa<?= $nis; ?>" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="modaldatasiswaLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <!-- <div class="modal-header">
                                        <h5 class="modal-title" id="modaldatasiswaLabel">Detail</h5>
                                    </div> -->
                                    <div class="modal-body">
                                        <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <form action="ubahdatasiswa.php" method="GET">
                                            <div class="mb-3">
                                                <label for="nis" class="form-label">N I S</label>
                                                <input type="number" class="form-control" id="nis" name="nis"
                                                    value="<?= @$nis ? $nis : ""; ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nama" class="form-label">Nama&nbsp;
                                                    <span class="badge text-bg-info">
                                                        <?= $gandersiswa; ?>
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" id="nama" name="nama"
                                                    value="<?= $namasiswa ?>" required>
                                            </div>

                                            <!-- select dudi -->
                                            <div class="mb-3">
                                                <label for="kodedudi"
                                                    class="form-label">DUDIKA&nbsp;<?= @$data_pembimbing["namadudi"] ? '<i class="fa-solid fa-circle-check fa-bounce text-info"></i>' : ""; ?></label>
                                                <select name="kodedudi" class="form-select" aria-label="Default select example">
                                                    <option value="<?= $kode_dudi; ?>">
                                                        <?= @$data_pembimbing["namadudi"] ? $namadudi : 'Pilih DUDIKA'; ?>
                                                    </option>
                                                    <?php
                                                    $kuota_gander = "";

                                                    if (@$data["gander"] == "L") {
                                                        $kuota_gander = "kuotacow";
                                                    } else if (@$data["gander"] == "P") {
                                                        $kuota_gander = "kuotacew";
                                                    } else {
                                                        $kuota_gander = "kuotatoal";
                                                    }

                                                    $sql = "SELECT * FROM datadudi WHERE $kuota_gander > 0 OR (kuotatoal > 0 AND (kuotacew = 0 AND kuotacow = 0)) ORDER BY namadudi ASC";
                                                    $query = mysqli_query($konek, $sql);

                                                    while ($rowdudi = mysqli_fetch_assoc($query)) {
                                                        $kode = $rowdudi["kode"];

                                                        if ($kode == $kode_dudi) {
                                                            $selected = " selected";
                                                        } else {
                                                            $selected = "";
                                                        }
                                                        ?>
                                                        <option value="<?= $kode; ?>" <?= $selected; ?>><?= $rowdudi["namadudi"]; ?>
                                                            (Kuota:
                                                            <?= $rowdudi["kuotatoal"]; ?>            <?php if ($rowdudi["kuotacow"] || $rowdudi["kuotacew"]) { ?>)
                                                                (L: <?= $rowdudi["kuotacow"]; ?>, P:
                                                                <?= $rowdudi["kuotacew"]; ?>            <?php } ?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-sm border-0 mx-1" name="akses"
                                                value="ubahdatasiswa">
                                                Ganti
                                                &nbsp;
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-secondary btn-sm border-0 mx-1 float-end"
                                                data-bs-dismiss="modal">
                                                Tutup
                                                &nbsp;
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Understood</button>
                                </div> -->
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

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


<?php } else { ?>
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