<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');
$tanggal = date('Y-m-d');
$jam = date('H.i.s');
// echo "masuk ke _presensi.php <br>";

session_start();

include "views/header.php";

$pesan_ok = "<h6>Mengunggah...</h6><br>
    <div class=\"loading\">
        <i class=\"fa fa-spinner fa-spin\" style=\"font-size:24px\"></i>
    </div>
    <br>
    <h6>Tunggu sebentar!</h6>";

$pesan_er = "<h6>Gagal Mengunggah...</h6><br>
    <div class=\"loading\">
        <i class=\"fa fa-spinner fa-spin\" style=\"font-size:24px\"></i>
    </div>
    <br>
    <h6>Mengalihkan!</h6>";
?>

<style>
    .container .loading {
        display: flex;
        justify-content: center;
    }

    .container h6 {
        text-align: center;
    }
</style>

<div class="container">
    <?php

    if ((@$_FILES['file_photos']['name']) || (@$_FILES['_photos']['name'])) {
        $nis = @$_POST['nis'];
        $kelas = @$_POST['kelas'];
        $nama = @$_POST['nama'];
        $namadudi = @$_POST['namadudi'];
        $kode = @$_POST['kodedudika'];
        $keterangan = @$_POST['keterangan'];
        $catatan = @$_POST['catatanjurnal'];
        $nomor = @$_POST['nowapemb'];
        $token = @$_POST['tok'];

        $folder = "/img/presensi/";

        if (@$_FILES['file_photos']['name']) {
            include "koneksi.php";

            // echo "masuk ke file_photos <br>";
    
            $file = @$_FILES['file_photos']['name'];
            $file_loc = @$_FILES['file_photos']['tmp_name'];
            $file_size = @$_FILES['file_photos']['size'];
            $file_error = @$_FILES['file_photos']['error'];
            $file_type = @$_FILES['file_photos']['type'];

            // hitung banyaknya file 
            $file_count = count($file);

            // pengulangan sejumlah jumlah file
            // Loop untuk setiap file yang diupload
            for ($i = 0; $i < $file_count; $i++) {
                $new_size = $file_size[$i] / 1024;
                $new_file_name = strtolower($file[$i]);
                $new_file_name = $nis . '_' . $kode . '_' . $tanggal . "_" . $jam . "_" . $i . "_" . $new_file_name;
                $final_file = str_replace(' ', '_', $new_file_name);
                $folder = "img/presensi/" . $final_file;
                $file_loc = @$_FILES['file_photos']['tmp_name'][$i];
                $file_type = @$_FILES['file_photos']['type'][$i];

                $compressedImage = compressImage($file_loc, $folder, 20); // compress image
    
                // Tentukan statement SQL menggunakan prepared statement
                $sql = "INSERT INTO presensi (nis, kode, file, type, size, ket, jurnal) VALUES (?, ?, ?, ?, ?, ?, ?)";

                // Persiapkan statement
                $stmt = mysqli_prepare($konek, $sql);

                // Bind parameter ke statement
                mysqli_stmt_bind_param(
                    $stmt,
                    "sssssss",
                    $nis,
                    $kode,
                    $final_file,
                    $file_type,
                    $new_size,
                    $keterangan,
                    $catatan
                );

                // Eksekusi statement
                $upload_success = false;
                if (move_uploaded_file(($compressedImage ? $compressedImage : $file_loc), $folder)) {
                    if (mysqli_stmt_execute($stmt)) {
                        $upload_success = true;
                    }
                }

                if ($upload_success) {
                    echo "$pesan_ok";
                } else {
                    echo "$pesan_er<br><br>" . mysqli_error($konek) . "<br>";
                    ?>
                    <script>
                        // alert('Gagal Upload');
                        window.location.href = 'presensi.php?nis=<?= $nis; ?>&akses=presensi';
                    </script>
                    <?php
                }

                // Tutup statement
                mysqli_stmt_close($stmt);
            }

            mysqli_close($konek);
        } else {

            // echo "masuk ke _photos <br>";
    
            // cek hari ini sudah absen belom
            include "koneksi.php";

            // Cek apakah sudah ada absensi untuk NIS tertentu pada tanggal tertentu
            $sql_check_absensi = "SELECT * FROM presensi WHERE nis = ? AND timestamp LIKE ?";
            $stmt_check_absensi = mysqli_prepare($konek, $sql_check_absensi);
            $tanggal_bind = "%$tanggal%";
            mysqli_stmt_bind_param($stmt_check_absensi, "ss", $nis, $tanggal_bind);
            mysqli_stmt_execute($stmt_check_absensi);
            $result_check_absensi = mysqli_stmt_get_result($stmt_check_absensi);
            $hasil_sudah_absen = mysqli_num_rows($result_check_absensi);

            if ($keterangan || $hasil_sudah_absen > 0) {
                $file = @$_FILES['_photos']['name'];
                $file_loc = @$_FILES['_photos']['tmp_name'];
                $file_size = @$_FILES['_photos']['size'];
                $file_error = @$_FILES['_photos']['error'];
                $file_type = @$_FILES['_photos']['type'];

                $new_size = $file_size / 1024;
                $new_file_name = strtolower($file);
                $new_file_name = $nis . '_' . $kode . '_' . $tanggal . "_" . $jam . "_" . $new_file_name;
                $final_file = str_replace(' ', '_', $new_file_name);

                $folder = "img/presensi/" . $final_file;

                $allowTypes = array('jpg', 'png', 'jpeg'); //allow only these types of files
    
                $fileType = pathinfo($folder, PATHINFO_EXTENSION); // get file extension
    
                $compressedImage = compressImage($file_loc, $folder, 10); //compress image
    
                if ($compressedImage) {

                    $sql_insert_presensi = "INSERT INTO presensi (nis, kode, file, type, size, ket, jurnal) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_insert_presensi = mysqli_prepare($konek, $sql_insert_presensi);
                    mysqli_stmt_bind_param(
                        $stmt_insert_presensi,
                        "ssssdss",
                        $nis,
                        $kode,
                        $final_file,
                        $file_type,
                        $new_size,
                        $keterangan,
                        $catatan
                    );

                    // Eksekusi statement
                    if (mysqli_stmt_execute($stmt_insert_presensi)) {
                        echo "$pesan_ok";
                        ?>
                        <script>
                            // alert('Berhasil Upload');
                            window.location.href = 'prevpresensi.php?nis=<?= $nis; ?>&akses=presensi';
                        </script>
                        <?php
                    } else {
                        echo "$pesan_er<br>" . mysqli_error($konek) . "<br>";
                        ?>
                        <script>
                            alert('error - Gagal Upload!<br><?= mysqli_error($konek); ?>');
                            window.location.href = 'presensi.php?nis=<?= $nis; ?>&akses=presensi';
                        </script>
                        <?php
                    }

                    // Tutup statement insert
                    mysqli_stmt_close($stmt_insert_presensi);

                    // include "app/apiwa.php";
                } else {
                    echo "$pesan_er<br>" . $file_error . "<br>";
                    ?>
                    <script>
                        alert('error - Gagal Upload!<br>'.mysqli_error($konek));
                        window.location.href = 'presensi.php?nis=<?= $nis; ?>&akses=presensi';
                    </script>
                    <?php
                }
            } else {
                echo "$pesan_er";
                ?>
                <script>
                    alert('error - Gagal Upload!\nTidak ada Keterangan Absen (Masuk, Ijin, Sakit , Libur)\nTolong Ulangi lagi ya...');
                    window.location.href = 'presensi.php?nis=<?= $nis; ?>&akses=presensi';
                </script>
                <?php
            }

            // Tutup koneksi database
            mysqli_close($konek);
        }
    } else {
        echo '<script>window.history.back();</script>';
        echo '<script>alert("Tidak ada Foto!")</script>';
    }

    echo "</div>";

    function compressImage($source, $destination, $quality)
    {
        // mendapatkan info gambar 
        $imgInfo = getimagesize($source);
        $mime = $imgInfo['mime'];

        // membuat gambar baru dari file sumber
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            default:
                $image = imagecreatefromjpeg($source);
        }

        // menyimpan gambar 
        imagejpeg($image, $destination, $quality);

        // mengembalikan gambar yang dikompres 
        return $destination;
    }

    include "views/footer.php";
    ?>