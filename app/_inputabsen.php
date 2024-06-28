<?php
include "../koneksi.php";

// Pastikan $_POST['idp'] sudah didefinisikan
if (isset($_POST['idp'])) {
    $idpembimbing = $_POST['idp'];

    // Gunakan prepared statement untuk menghindari SQL injection
    $stmt = mysqli_prepare($konek, "SELECT * FROM datapembimbing WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $idpembimbing);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Ambil data pembimbing
    if ($h_pembimbing = mysqli_fetch_array($result)) {
        $pembimbing = $h_pembimbing['nama'];
    } else {
        // Handle jika data tidak ditemukan
        $pembimbing = "Pembimbing tidak ditemukan";
    }

    // Tutup statement prepared
    mysqli_stmt_close($stmt);
} else {
    // Handle jika $_POST['idp'] tidak ada
    $pembimbing = "ID Pembimbing tidak tersedia";
}
?>

<input type="hidden" name="nama_pembimbing" value="<?= $pembimbing; ?>">

<select class="form-control" name="siswa" required>
    <?php
    // Pastikan variabel $pembimbing sudah didefinisikan sebelumnya
    if (isset($pembimbing)) {
        // Gunakan prepared statement untuk menghindari SQL injection
        $stmt = mysqli_prepare($konek, "SELECT * FROM duditerisi WHERE pembimbing = ?");
        mysqli_stmt_bind_param($stmt, "s", $pembimbing);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Hitung jumlah baris yang ditemukan
        $jml = mysqli_num_rows($result);

        if ($jml > 0) {
            echo "<option value=\"\">-- Pilih Nama Siswa --</option>";

            // Loop untuk menampilkan opsi pilihan nama siswa
            while ($r = mysqli_fetch_array($result)) {
                ?>
                <option value="<?= $r['nis'] ?>">[<?= $r['nis']; ?>] [<?= $r['kelas']; ?>] - <?= $r['namasiswa'] ?></option>
                <?php
            }
        } else {
            // Jika tidak ada data yang ditemukan
            echo "<option selected>-- Tidak ada data --</option>";
        }

        // Tutup statement prepared
        mysqli_stmt_close($stmt);
    } else {
        // Handle jika $pembimbing tidak terdefinisi
        echo "<option selected>-- Pembimbing tidak terdefinisi --</option>";
    }
    ?>

</select>

<?php
// Tutup koneksi
mysqli_close($konek);
?>