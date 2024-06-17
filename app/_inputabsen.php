<?php
include "../koneksi.php";

$idpembimbing = @$_POST['idp'];

$q_pem = mysqli_query($konek, "SELECT * FROM datapembimbing WHERE id = '$idpembimbing'");

while ($h_pembimbing = mysqli_fetch_array($q_pem)) {
    $pembimbing = $h_pembimbing['nama'];
}

?>
<input type="hidden" name="nama_pembimbing" value="<?= $pembimbing; ?>">

<select class="form-control" name="siswa" required>
    <?php

    $tampil = mysqli_query($konek, "SELECT * FROM duditerisi WHERE pembimbing = '$pembimbing'");
    $jml = mysqli_num_rows($tampil);

    if ($jml > 0) {

        echo "<option value=\"\">-- Pilih Nama Siswa --</option>";

        while ($r = mysqli_fetch_array($tampil)) {
    ?>
            <option value="<?= $r['nis'] ?>">[<?= $r['nis']; ?>]&nbsp;[<?= $r['kelas']; ?>]&nbsp;-&nbsp;<?= $r['namasiswa'] ?></option>
    <?php
        }
    } else {
        echo "<option selected>-- Tidak ada data --</option>";
    }

    ?>
</select>