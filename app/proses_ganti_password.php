<?php
$nis = isset($_POST["nis"]) ? $_POST["nis"] : '';
$passwordlama = isset($_POST["passwordlama"]) ? $_POST["passwordlama"] : '';
$token = isset($_POST["token"]) ? $_POST["token"] : '';
$new_password = isset($_POST["new_password"]) ? $_POST["new_password"] : '';

// ambil data siswa dari database
include "../koneksi.php";

// cek ketersediaan data
$sql_datasiswa = "SELECT * FROM datasiswa WHERE nis = ?";
$stmt_datasiswa = mysqli_prepare($konek, $sql_datasiswa);
mysqli_stmt_bind_param($stmt_datasiswa, "s", $nis);
mysqli_stmt_execute($stmt_datasiswa);
$result_datasiswa = mysqli_stmt_get_result($stmt_datasiswa);
$row = mysqli_fetch_assoc($result_datasiswa);

if ($row > 0) {
    // dpatkan nick sebagai sandi
    $nis = isset($row["nis"]) ? $row["nis"] : '';
    $nick = $row["nama"];
    $db_pass = $row["password"];
    $nick_no_spaces = str_replace(' ', '', $nick);
    $nick_lower = strtolower($nick_no_spaces);
    $nick_8_chars = substr($nick_lower, 0, 8);

    $tokengenerated = $nick_8_chars . $nis;
    $md5new_password = md5($new_password);

    if ($token == $tokengenerated) {
        $sql = "UPDATE datasiswa SET `password` = ? WHERE `nis` = ?";
        $stmt = mysqli_prepare($konek, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $md5new_password, $nis);
        if (mysqli_stmt_execute($stmt)) {
            $berhasil = true;
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
            $berhasil = false;
        }

        mysqli_stmt_close($stmt);
        mysqli_close($konek);

        if ($berhasil) {
            echo '<script>alert("Berhasil Mengganti Password :' . $new_password . '");</script>';
            echo '<script>window.history.go(-2);</script>';
            exit;
        } else {
            echo '<script>alert("Gagal ganti Password");</script>';
            echo '<script>window.history.go(-2);</script>';
            exit;
        }
    } else {
        echo '<script>alert("Input melalui form yang benar");</script>';
        echo '<script>window.history.go(-2);</script>';
        exit;
    }
} else {
    mysqli_close($konek);
    echo '<script>alert("Harus masukkan password");</script>';
    echo '<script>window.history.go(-2);</script>';
    exit;
}