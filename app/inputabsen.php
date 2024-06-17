<?php
session_start();

$admin = true;
$app = true;
include "../views/header.php";
include "../views/navbar.php";

    include "../koneksi.php";
if (@$_SESSION["admin"]) {

    if (@$_POST['post_absen'] == "inputabsen") {
        // echo "<pre>";
        // print_r(@$_POST);
        // echo "</pre>";
        
        $nis = $_POST['siswa'];
        $keterangan = $_POST['ket'];
        $catatan = $_POST['catatan'];
        $nama_pembimbing = $_POST['nama_pembimbing'];
        $tanggal = $_POST['tanggal'];

        // cari kode dudi
        $q_dudi = mysqli_query($konek, "SELECT kode FROM duditerisi WHERE nis = '$nis'");
        $kode = mysqli_fetch_array($q_dudi)['kode'];

        // insert ke presensi
        $file = @$_FILES['fotodok']['name'];
        $file_loc = @$_FILES['fotodok']['tmp_name'];
        $file_size = @$_FILES['fotodok']['size'];
        $file_type = @$_FILES['fotodok']['type'];

        $new_size = $file_size / 1024;
        $new_file_name = strtolower($file);
        $new_file_name = $nis . '_' . $kode . '_' . $tanggal . "_" . $jam . "_" .  $new_file_name;
        $final_file = str_replace(' ', '_', $new_file_name);

        $folder = "../img/presensi/" . $final_file;
        $allowTypes = array('jpg', 'png', 'jpeg'); //allow only these types of files
        $fileType = pathinfo($folder, PATHINFO_EXTENSION); // get file extension
        $compressedImage = compressImage($file_loc, $folder, 10); //compress image
            // echo "oook5<br>";

        $timestamp = $tanggal . " 08:00:00";

        if ($compressedImage) {
            // echo "compres<br>";
            $sql = "INSERT INTO presensi (timestamp, nis, kode, file, type, size, ket, jurnal) VALUES ('$timestamp', '$nis', '$kode', '$final_file', '$file_type', '$new_size', '$keterangan', '$catatan')";

            $hasil = mysqli_query($konek, $sql);
            $tmp = "tambahkan Absen dari: $nis,<br>Kode DUDI: $kode,<br>Pembimbing: $nama_pembimbing,<br>Keterangan: $keterangan,<br>Catatan: $catatan<br>Tanggal: $tanggal";

            if ($hasil) {
                $pesan = "Berhasil $tmp";
                $_SESSION['pesan'] = $pesan;
                $pesan = "";
            } else {
                $pesan_er = "Gagal $tmp<br>" .  mysqli_error($konek);
                $_SESSION['pesan_er'] = $pesan_er;
                $pesan_er = "";
            }

            echo "<script>
                window.location.href = '../app/inputabsen.php';
            </script>";
        } else {
            echo "nggak nompres<br>";
        }
    }

    $q_pembimbing = mysqli_query($konek, "SELECT * FROM datapembimbing");
    $get_pembimbing = @$_GET['idp'];
?>

    <div class="container">
        <div class="mb-3 text-center">
            <h4>Input Absen Siswa oleh pembimbing</h4>
        </div>
        <?php
        $p = @$_SESSION['pesan'];
        $p_r = @$_SESSION['pesan_er'];

        if ($p || $p_r) { ?>
            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                <?= $p; ?><?= $p_r; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        <div class="mb-2" style="max-width: 500px; margin: auto">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-2">
                    <label for="">Pembimbing</label>
                    <select class="form-control" name="pembimbing" id="select_pembimbing" required>
                        <option value="">-- Pilih Nama Pembimbing --</option>
                        <?php
                        foreach ($q_pembimbing as $data1) {
                        ?>
                            <option value="<?= $data1['id']; ?>"><?= $data1['nama']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-2">
                    <label for="">Siswa</label>
                    <div id="select_siswa">
                        <select class="form-control" name="siswa" required>
                            <option value="">-- Pembimbing belum dipilih --</option>
                        </select>
                    </div>
                </div>

                <div class="mb-2">
                    <label for="">Tanggal Absen</label>
                    <input type="date" class="form-control" name="tanggal" id="tanggal" required>
                </div>

                <div class="mb-2">
                    <label for="">Upload Bukti Foto</label>
                    <input type="file" class="form-control" name="fotodok" id="inputGroupFile01" required>
                </div>

                <div class="mb-2">
                    <label for="">Keterangan</label>
                    <select class="form-control" name="ket" id="select_ket" required>
                        <option value="Masuk">Masuk</option>
                        <option value="Izin">Ijin</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Tidak_Masuk">Libur</option>
                        <!-- <option value="">Alpa</option> -->
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Catatan</label>
                    <textarea class="form-control" name="catatan" id="text_catatan" name="" id="" cols="30" rows="3" required></textarea>
                </div>
                <div class="mb-3 d-flex justify-content-between">
                    <button type="submit" name="post_absen" value="inputabsen" class="btn btn-success btn-sm border-0">Simpan</button>
                    <a href="../app/inputabsen.php" class="btn btn-secondary btn-sm border-0">Refresh</a>

                    <a href="../admin" class="btn btn-dark btn-sm border-0">Kembali</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>-->


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script type="text/javascript">
        $('#select_pembimbing').change(function() {
            var select_pembimbing = $(this).val();
            $.ajax({
                type: 'POST',
                url: '_inputabsen.php',
                data: 'idp=' + select_pembimbing,
                success: function(response) {
                    // alert(response);
                    $('#select_siswa').html(response);
                }
            });
        });
    </script>

    <?php

    if (!$_POST) {
        unset($_SESSION['pesan']);
        unset($_SESSION['pesan_er']);
    }
} else {
    // $_SESSION['url_go'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['url_go'] = $_SERVER['REQUEST_URI'];
    // echo $_SESSION['url_go'];
    ?>
    <script type="text/javascript">
        window.onload = () => {
            $('#adminlogin').modal('show');
        }
    </script>
<?php } ?>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php

include "../views/footer.php";

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
?>