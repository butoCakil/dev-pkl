<?php
if (isset($_GET['pass']) && $_GET['pass'] == "!234") {
    include "../koneksi.php";

    $sql_datadudi = mysqli_query($konek, "SELECT * FROM datadudi");

    if ($sql_datadudi) {
        while ($data = mysqli_fetch_assoc($sql_datadudi)) {
            $kode = $data['kode'];
            $pembimbing = $data['pembimbing'];

            // Gunakan prepared statement untuk mengamankan kueri
            $stmt = mysqli_prepare($konek, "UPDATE `duditerisi` SET `pembimbing` = ? WHERE `kode` = ?");
            mysqli_stmt_bind_param($stmt, "si", $pembimbing, $kode);
            $update = mysqli_stmt_execute($stmt);

            if ($update) {
                echo "Berhasil memperbarui Pembimbing $kode menjadi $pembimbing";
            } else {
                echo "Gagal memperbarui Pembimbing $kode<br>" . mysqli_error($konek);
            }

            echo "<br>";

            // Tutup statement prepared
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Gagal menjalankan kueri: " . mysqli_error($konek);
    }

    // Tutup koneksi
    mysqli_close($konek);
} else {
    echo "Password ditolak";
}
