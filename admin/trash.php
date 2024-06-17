<?php
session_start();

if (@$_SESSION["admin"]) {
    if (@$_SESSION["admin"] == "admin") {

    if (@$_POST["hapusdatadudi"] == "hapus") {
        // echo "masuk hapus<br>";
        $kode = $_POST["kode"];

        // echo "kode: " . $kode . "<br>";

        include "../koneksi.php";

        $q = mysqli_query($konek, "DELETE FROM datadudi WHERE kode='$kode'");
        if ($q) {
            $_SESSION["ok"] = "Kode: " . $kode . "berhasil dihapus dari 'datadudi'<br>";
        } else {
            $_SESSION["error"] = "Kode: " . $kode . "gagal dihapus dari 'datadudi'<br>" . mysqli_error($konek);
        }

        $q = mysqli_query($konek, "DELETE FROM duditerisi WHERE kode='$kode'");
        if ($q) {
            $_SESSION["ok"] = "Kode: " . $kode . "berhasil dihapus dari 'duditerisi'<br>";
        } else {
            $_SESSION["error"] = "Kode: " . $kode . "gagal dihapus dari 'duditerisi'<br>" . mysqli_error($konek);
        }

        // redirect ke ubahdudi.php
        header("Location: ubahdudi.php");
    } else {
        $_SESSION["error"] = "Tidak ada data yang dihapus";
        // redirect
        header("location: ubahdudi.php");
    }
    } else {
        echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../admin';
        </script>";
    }
} else {
    // alert 
    $_SESSION["error"] = "Anda tidak memiliki akses ke halaman ini!";

    // header("location: ../");
        // alert 
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../';
        </script>";
}
