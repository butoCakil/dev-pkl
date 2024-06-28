<!--Halaman login dalam perbaikan, Maaf-->
<?php
// Mulai sesi
session_start();

// Cek jika parameter login adalah admin
if ($_GET["login"] == "admin") {
    // Ambil data dari URL
    $username = $_GET["username"];
    $password = $_GET["password"];

    // Include file koneksi database
    include "../koneksi.php";

    // Query untuk admin
    $sql_admin = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt_admin = mysqli_prepare($konek, $sql_admin);
    mysqli_stmt_bind_param($stmt_admin, "ss", $username, $password);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    if (mysqli_num_rows($result_admin) > 0) {
        $data = mysqli_fetch_assoc($result_admin);
        $_SESSION["admin"] = $data["username"];

        // Simpan ke cookie
        setcookie("admin", $data["username"], time() + (60 * 60 * 24 * 30));

        mysqli_close($konek);
        
        // Redirect ke halaman yang diminta sebelumnya jika ada, jika tidak ke halaman admin
        if (isset($_SESSION['url_go'])) {
            header("Location: " . $_SESSION['url_go']);
        } else {
            header("Location: ../admin");
        }
        exit(); // Pastikan tidak ada output lain sebelum header redirect
    } else {
        // Jika tidak ada di tabel admin, cek di tabel aksesdudi
        $sql_dudi = "SELECT * FROM aksesdudi WHERE user = ? AND password = ?";
        $stmt_dudi = mysqli_prepare($konek, $sql_dudi);
        mysqli_stmt_bind_param($stmt_dudi, "ss", $username, $password);
        mysqli_stmt_execute($stmt_dudi);
        $result_dudi = mysqli_stmt_get_result($stmt_dudi);

        if (mysqli_num_rows($result_dudi) > 0) {
            $data_dudi = mysqli_fetch_assoc($result_dudi);
            $_SESSION["kodedudi"] = $data_dudi["kodedudi"];
            $_SESSION["userdudi"] = $data_dudi["user"];
            $_SESSION["namadudi"] = $data_dudi["namadudi"];

            mysqli_close($konek);
            // Redirect ke halaman yang diminta sebelumnya jika ada, jika tidak ke halaman utama
            if (isset($_SESSION['url_go'])) {
                header("Location: " . $_SESSION['url_go']);
            } else {
                header("Location: ../");
            }
            exit(); // Pastikan tidak ada output lain sebelum header redirect
        } else {
            // Jika tidak ada di kedua tabel, tampilkan pesan kesalahan dan redirect ke halaman utama
            echo "<script>alert('Username atau Password salah!');</script>";
            header("Location: ../");
            exit(); // Pastikan tidak ada output lain sebelum header redirect
        }
    }

} else {
    // Jika tidak ada parameter login atau tidak sama dengan "admin", tampilkan pesan akses tidak diijinkan dan redirect ke halaman utama
    echo "<script>alert('Gagal Login! Akses Tidak di Ijinkan! Silakan LOGIN');</script>";
    header("Location: ../");
    exit(); // Pastikan tidak ada output lain sebelum header redirect
}
?>