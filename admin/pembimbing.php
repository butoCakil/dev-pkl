<?php
session_start();
$title = "Admin Prakerin";
$admin = true;
include "../views/header.php";
include "../views/navbar.php";

if (@$_SESSION['pesan']) {
    $pesan = @$_SESSION['pesan'];
    $bg_alert = "success";
} elseif (@$_SESSION['pesan_error']) {
    $pesan = @$_SESSION['pesan_error'];
    $bg_alert = "danger";
} else {
    $pesan = "";
    $bg_alert = "";
}
?>

<div class="container">
    <?php if ($pesan) { ?>
        <div class="alert alert-<?= $bg_alert; ?> alert-dismissible fade show text-center" role="alert">
            <?= $pesan; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php } ?>

    <h1>Daftar Pembimbing</h1>
    <a href="tambahpembimbing.php?akses=tambah" class="btn btn-sm border-0 btn-success">
        <i class="fas fa-plus"></i>&nbsp;
        Tambah Pembimbing</a>
    <a href="../admin/" class="btn btn-sm btn-dark border-0 m-2">
        <i class="fas fa-arrow-left"></i>&nbsp;
        Kembali
    </a>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <th>No</th>
                <th>Nama Pembimbing</th>
                <th>Jurusan Bimbingan</th>
                <th>CP (WA)</th>
                <?php if (@$_SESSION['admin'] == "admin") { ?>
                    <th>Aksi</th>
                <?php } ?>
            </thead>
            <tbody>
                <?php
                include '../koneksi.php';
                $query = mysqli_query($konek, "SELECT * FROM datapembimbing");
                $no = 0;
                foreach ($query as $data) {
                    $no++;
                ?>
                    <tr>
                        <td><?= $no; ?></td>
                        <td><?= $data['nama']; ?></td>
                        <td><?= $data['jur']; ?></td>
                        <td>
                            <?php
                            $link_wa = "https://api.whatsapp.com/send?phone=" . @$data["cp"] . "&text=";
                            ?>
                            <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i class="fa-brands fa-whatsapp fa-beat" style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                            <?= $data['cp']; ?>
                        </td>
                        <?php if (@$_SESSION['admin'] == "admin") { ?>
                            <td class="d-flex justify-content-around">
                                <a href="tambahpembimbing.php?akses=ubah&i=<?= $data['id']; ?>" class="btn btn-sm border-0 btn-warning"><i class="fas fa-edit"></i>
                                </a>

                                <form action="tambahpembimbing.php" method="post">
                                    <input type="hidden" name="id" value="<?= $data['id']; ?>">
                                    <input type="hidden" name="namapembimbing" value="<?= $data['nama']; ?>">
                                    <button type="submit" name="hapuspembimbing" value="hapus" class="btn btn-sm border-0 btn-danger" onclick="return confirm('Yakin, data ini mau dihapus?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php

if ($pesan) {
    $pesan = "";
    unset($_SESSION['pesan']);
    unset($_SESSION['pesan_error']);
}

include "../views/footer.php";
