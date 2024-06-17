<?php
$nis = $_GET["nis"];
$akses = $_GET["akses"];
session_start();

include "views/header.php";
include "views/navbar.php";
include "koneksi.php";

$sql_tmp = "SELECT DISTINCT timestamp FROM presensi WHERE nis = '$nis' ORDER BY timestamp DESC";
$result_tmp = mysqli_query($konek, $sql_tmp);

$sql = "SELECT * FROM presensi WHERE nis = '$nis'";
$result = mysqli_query($konek, $sql);
$cek = mysqli_num_rows($result);
$hhhasil = mysqli_fetch_assoc($result);

$kode = $hhhasil["kode"];

$sqll = "SELECT * FROM datadudi WHERE kode = '$kode'";
$resultt = mysqli_query($konek, $sqll);
$hasil_dudi = mysqli_fetch_assoc($resultt);

$sql_s = "SELECT * FROM datasiswa WHERE nis = '$nis'";
$result_s = mysqli_query($konek, $sql_s);
$hasil_s = mysqli_fetch_assoc($result_s);
?>

<style>
    .prevfilerekap_td {
        background-color: black;
        width: 100px;
        height: 100px;
        border-radius: 15px;
    }

    .prevfilerekap {
        width: 100px;
        height: 100px;
        margin: 1px;
        object-fit: cover;
        object-position: center;
        border-radius: 15px;
        padding: 5px;
    }
</style>

<div class="container">
    <h2>Rekap Absen&nbsp;<span class="badge text-bg-warning"><?= $hasil_dudi["namadudi"]; ?></span></h2>
    <h4>
        <i><?= $hasil_s["nama"]; ?>&nbsp;(<?= $hasil_s["kelas"]; ?>) <?= $hasil_s['nis']; ?></i>
    </h4>
    <a href="presensi.php?nis=<?= @$hasil_s['nis']; ?>&akses=presensi" class="btn btn-dark btn-sm border-0 mb-3" href=""><i class="fas fa-arrow-left"></i>&nbsp;Kembali</a>
    <a href="semuarekap.php?nis=<?= @$hasil_s['nis']; ?>" class="btn btn-success btn-sm border-0 mb-3">
        <i class="fas fa-bars"></i>&nbsp;Semua Rekap</a>
    <div class="table-responsive">
        <table id="rekapjurnal" class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Jam</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Jurnal / Catatan</th>
                    <th scope="col">Ket</th>
                    <th scope="col">Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_tmp)) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date("d-m-Y", strtotime($row["timestamp"])); ?></td>
                        <td><?= date("H:i:s", strtotime($row["timestamp"])); ?></td>
                        <?php
                        $sql_p = "SELECT * FROM presensi WHERE nis = '$nis' AND timestamp LIKE '%" . $row['timestamp'] . "%'";
                        $result_p = mysqli_query($konek, $sql_p);

                        while ($row_p = mysqli_fetch_array($result_p)) {
                            $cari = "http";
                            $img = $row_p["file"];
                            
                            $cek_img = preg_match("/$cari/i", $img);
                            
                            if(@$cek_img){
                                $link_img = $img;
                            } else {
                                $link_img = "img/presensi/" . $img;
                            }
                        ?>
                            <td>
                                <img class="prevfilerekap fotoprev" src="<?= $link_img; ?>" alt="">
                                <a href = "<?= $link_img ?>" class = "btn btn-success btn-sm border-0"><i class="fas fa-download"></i></a>
                                <!--<label><?= $link_img ?></label>-->
                            </td>
                            <td>
                                <?php
                                echo $row_p["jurnal"];
                                ?>
                            </td>
                            <td><?= $row_p['ket']; ?></td>
                        <?php } ?>
                        <td>
                            <a href="prevpresensi.php?nis=<?= $hasil_s['nis']; ?>&akses=<?= $akses; ?>&tmp=<?= date("Y-m-d", strtotime($row["timestamp"])); ?>" class="btn btn-sm btn-success border-0">
                                <!-- rekap -->
                                <i class="fas fa-file-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php include "views/footer.php"; ?>

<style>
    .container .fotoprev:hover {
        object-fit: contain;
        transform: scale(2);
        /* transisi */
        transition: all 0.5s;
    }
</style>


<script type="text/javascript">
    $(document).ready(function() {
        $('#rekapjurnal').DataTable({
            dom: 'rBlftip',
            buttons: [
                // 'print', 'excel', 'csv', 'pdf'
                'print', 'excel'
            ],
            responsive: true,
            "lengthChange": true,
            "lengthMenu": [
                [10, 15, 25, 50, -1],
                [10, 15, 25, 50, "Semua"]
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