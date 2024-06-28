<?php
if (isset($_POST['message'], $_POST['nomor'], $_POST['timestamp'], $_POST['key'])) {
    // Ambil dan validasi data dari POST
    $pesan = $_POST['message'];
    $nomor = $_POST['nomor'];
    $timestamp = $_POST['timestamp'];
    $key = $_POST['key'];

    // Validasi key
    if ($key == "!234") {
        // Include file koneksi
        include "../koneksi.php";

        $query = "INSERT INTO `loadchat`(`nomor`, `pesan`, `media`, `status`, `timestamp`) VALUES ('$nomor', '$pesan', NULL, 'dikirim', '$timestamp')";
        $update = mysqli_query($konek, $query);

        if ($update) {
            // Pesan balasan jika berhasil
            echo "Pesan telah berhasil diterima dan diolah: $pesan";

            $token = "@USo9EJ4cicpFQ1t9n0n";
            include "apiwa.php";
        } else {
            // Pesan balasan jika gagal menyimpan ke database
            echo "Gagal menyimpan pesan ke database: " . mysqli_error($konek);
        }

        // Tutup koneksi
        mysqli_close($konek);
    } else {
        // Pesan balasan jika kunci tidak valid
        echo "Perlu otentikasi yang benar.";
    }
} else {
    // Pesan balasan jika tidak semua data diterima
    echo "Data yang diperlukan tidak lengkap.";
}

