<?php
session_start();

// Pastikan session admin telah ter-set
if (isset($_SESSION["admin"])) {
    // Pastikan session admin memiliki nilai "admin"
    if ($_SESSION["admin"] == "admin") {

        // Periksa apakah form telah di-submit dengan benar
        if (isset($_POST["hapusdatadudi"]) && $_POST["hapusdatadudi"] == "hapus") {
            $kode = $_POST["kode"];

            // Sisipkan file koneksi.php
            include "../koneksi.php";

            // Hapus data dari tabel datadudi
            $stmt1 = mysqli_prepare($konek, "DELETE FROM datadudi WHERE kode=?");
            mysqli_stmt_bind_param($stmt1, "s", $kode);
            $result1 = mysqli_stmt_execute($stmt1);

            if ($result1) {
                $_SESSION["ok"] .= "Kode: " . $kode . " berhasil dihapus dari 'datadudi'<br>";
            } else {
                $_SESSION["error"] .= "Kode: " . $kode . " gagal dihapus dari 'datadudi'<br>" . mysqli_error($konek);
            }

            mysqli_stmt_close($stmt1);

            // Hapus data dari tabel duditerisi
            $stmt2 = mysqli_prepare($konek, "DELETE FROM duditerisi WHERE kode=?");
            mysqli_stmt_bind_param($stmt2, "s", $kode);
            $result2 = mysqli_stmt_execute($stmt2);

            if ($result2) {
                $_SESSION["ok"] .= "Kode: " . $kode . " berhasil dihapus dari 'duditerisi'<br>";
            } else {
                $_SESSION["error"] .= "Kode: " . $kode . " gagal dihapus dari 'duditerisi'<br>" . mysqli_error($konek);
            }

            mysqli_stmt_close($stmt2);
            mysqli_close($konek);

            // Redirect ke ubahdudi.php setelah selesai
            header("Location: ubahdudi.php");
            exit; // Pastikan tidak ada output lain sebelum header

        } else {
            $_SESSION["error"] = "Tidak ada data yang dihapus";
            header("Location: ubahdudi.php");
            exit; // Pastikan tidak ada output lain sebelum header
        }

    } else {
        // Jika session admin bukan "admin", alihkan ke halaman admin
        echo "<script>
                alert('Anda tidak memiliki akses ke halaman ini!');
                window.location.href='../admin';
              </script>";
        exit; // Pastikan tidak ada output lain setelah redirect
    }
} else {
    // Jika session admin tidak ada, alihkan ke halaman login
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../';
          </script>";
    exit; // Pastikan tidak ada output lain setelah redirect
}