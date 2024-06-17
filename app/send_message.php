<?php
if (isset($_POST['message'])) {
    // Ambil pesan dari URL GET
    $pesan = @$_POST['message'];
    $nomor = @$_POST['nomor'];
    $timestamp = @$_POST['timestamp'];

    // Lakukan apa yang Anda butuhkan dengan pesan ini, misalnya, menyimpannya ke dalam database
    if (@$_POST['key'] == "!234") {
        include "../koneksi.php";

        $update = mysqli_query($konek, "INSERT INTO `loadchat`(`nomor`, `pesan`, `media`, `status`, `timestamp`) VALUES ('$nomor','$pesan', NULL,'dikirim','$timestamp')");

        if ($update) {
            // Respon balik jika diperlukan
            echo "Pesan telah berhasil diterima dan diolah: $pesan";

            // kirim WA
            $token = "@USo9EJ4cicpFQ1t9n0n";
            include "apiwa.php";
        }
    } else {
        echo "perlu otentikasi";
    }
} else {
    // Respon balik jika pesan tidak ditemukan
    echo "Pesan tidak ditemukan.";
}
