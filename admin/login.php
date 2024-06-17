<!--Halaman login dalam perbaikan, Maaf-->
<?php
// start session
session_start();

echo "masuk Sesi";

if (@$_GET["login"] == "admin") {
    include "../koneksi.php";
    $username = $_GET["username"];
    $password = $_GET["password"];

    $q = mysqli_query($konek, "SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
    if (mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
        $_SESSION["admin"] = $data["username"];

        //  simpan ke cookies
        setcookie("admin", $data["username"], time() + (60 * 60 * 24 * 30));
        // echo "<script>alert('Login berhasil');</script>";
        if(@$_SESSION['url_go']){
                header("Location: " . $_SESSION['url_go']);
            } else {
                // header("Location: ../");
                header("Location: ../admin");
            }
    } else {
        $r = mysqli_query($konek, "SELECT * FROM aksesdudi WHERE user = '$username' AND password = '$password'");
        if (mysqli_num_rows($r) > 0) {
            $data_dudi = mysqli_fetch_assoc($r);
            $_SESSION["kodedudi"] = $data_dudi["kodedudi"];
            $_SESSION["userdudi"] = $data_dudi["user"];
            $_SESSION["namadudi"] = $data_dudi["namadudi"];

            
            // echo "<script>alert('Username atau Password salah!');</script>";
            
            if(@$_SESSION['url_go']){
                header("Location: " . $_SESSION['url_go']);
            } else {
                header("Location: ../");
            }
        } else {
            echo "<script>alert('Username atau Password salah!');</script>";
            header("Location: ../");
        }
    }
} else {
    // alert
    echo "<script>alert('Gagal Login! Akses Tidak di Ijinkan! Silakan LOGIN');</script>";
    header("Location: ../");
}
