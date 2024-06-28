<?php
session_start();

$title = "Admin Prakerin";
$admin = true;

include "../views/header.php";

if (@$_SESSION['admin'] == "admin") {
    include "../views/navbar.php";
    include "../koneksi.php";

    // hapus pembimbing
    if (isset($_POST['hapuspembimbing']) && $_POST['hapuspembimbing'] == "hapus") {
        // Ambil nilai dari form
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $namapembimbing = isset($_POST['namapembimbing']) ? $_POST['namapembimbing'] : '';

        // Query SQL menggunakan prepared statement untuk menghapus data pembimbing
        $sql = "DELETE FROM `datapembimbing` WHERE id = ?";
        $stmt = mysqli_prepare($konek, $sql);

        // Bind parameter ke dalam prepared statement
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Eksekusi prepared statement
        $delete = mysqli_stmt_execute($stmt);

        if ($delete) {
            $pesan = "Nama Pembimbing: " . $namapembimbing . ", Berhasil dihapus!";
            $_SESSION['pesan'] = $pesan;
        } else {
            $pesan_error = "Gagal menghapus Pembimbing: " . $namapembimbing . "<br>" . mysqli_error($konek);
            $_SESSION['pesan_error'] = $pesan_error;
        }

        // Tutup prepared statement
        mysqli_stmt_close($stmt);
        ?>
        <script>
            window.location.href = "pembimbing.php";
        </script>
        <?php
    }

    // TAMBAH PEMBIMBING
    if (isset($_POST['pembimbing']) && $_POST['pembimbing'] == "tambah") {
        // Ambil nilai dari form
        $namapembimbing = isset($_POST['namapembimbing']) ? $_POST['namapembimbing'] : '';
        $jurusanbimbingan = isset($_POST['jurusanbimbingan']) ? $_POST['jurusanbimbingan'] : '';
        $nomortelepon = isset($_POST['nomortelepon']) ? $_POST['nomortelepon'] : '';

        // Query SQL menggunakan prepared statement untuk menambahkan data pembimbing
        $sql = "INSERT INTO `datapembimbing`(`nama`, `cp`, `jur`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($konek, $sql);

        // Bind parameter ke dalam prepared statement
        mysqli_stmt_bind_param($stmt, "sss", $namapembimbing, $nomortelepon, $jurusanbimbingan);

        // Eksekusi prepared statement
        $insert_pembimbing = mysqli_stmt_execute($stmt);

        if ($insert_pembimbing) {
            $pesan = "Nama Pembimbing: " . $namapembimbing . ", Berhasil ditambahkan";
            $_SESSION['pesan'] = $pesan;
        } else {
            $pesan_error = "Gagal Tambahkan Pembimbing: " . $namapembimbing . "<br>" . mysqli_error($konek);
            $_SESSION['pesan_error'] = $pesan_error;
        }

        // Tutup prepared statement
        mysqli_stmt_close($stmt);
        ?>
        <script>
            window.location.href = "pembimbing.php";
        </script>
        <?php
    } elseif (@$_POST['pembimbing'] == "ubah") {
        $namalama = @$_POST['namalama'];
        $nomorlama = @$_POST['nomorlama'];
        $jurlama = @$_POST['jurlama'];
        $namapembimbing = @$_POST['namapembimbing'];
        $jurusanbimbingan = @$_POST['jurusanbimbingan'];
        $nomortelepon = @$_POST['nomortelepon'];
        $id = @$_POST['id'];

        if ($namalama != $namapembimbing) {
            $tambahanpesan = " dari : " . $namalama;
        } else {
            $tambahanpesan = "";
        }

        if ($nomorlama != $nomortelepon) {
            $tambahanpesan2 = " dari : " . $nomorlama . " menjadi: " . $nomortelepon;
        } else {
            $tambahanpesan2 = "";
        }

        if ($jurlama != $jurusanbimbingan) {
            $tambahanpesan3 = " dari : " . $jurlama . " menjadi: " . $jurusanbimbingan;
        } else {
            $tambahanpesan3 = "";
        }

        $success = 0;

        $update_pembimbing = mysqli_query($konek, "UPDATE `datapembimbing` SET `nama`='$namapembimbing',`cp`='$nomortelepon',`jur`='$jurusanbimbingan' WHERE id = '$id'");

        $sql = "UPDATE `datapembimbing` SET `nama`=?, `cp`=?, `jur`=? WHERE id = ?";
        // Siapkan statement prepared
        if ($stmt = mysqli_prepare($konek, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssi", $namapembimbing, $nomortelepon, $jurusanbimbingan, $id);

            if (mysqli_stmt_execute($stmt)) {
                $success++;
            } else {
                $success--;
            }

            mysqli_stmt_close($stmt);
        }

        $sql = "UPDATE `duditerisi` SET `pembimbing`=?, `jur`=? WHERE pembimbing=?";
        if ($stmt = mysqli_prepare($konek, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $namapembimbing, $jurusanbimbingan, $namalama);

            if (mysqli_stmt_execute($stmt)) {
                $success++;
            } else {
                $success--;
            }

            mysqli_stmt_close($stmt);
        }

        $sql = "UPDATE `datadudi` SET `pembimbing`=?, `nowa`=?, `jur`=? WHERE pembimbing=?";

        if ($stmt = mysqli_prepare($konek, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $namapembimbing, $nomortelepon, $jurusanbimbingan, $namalama);

            if (mysqli_stmt_execute($stmt)) {
                $success++;
            } else {
                $success--;
            }

            mysqli_stmt_close($stmt);
        }

        if ($success == 3) {
            $pesan = "Nama Pembimbing: " . $namapembimbing . ", Berhasil diubah" . $tambahanpesan . $tambahanpesan2 . $tambahanpesan3 . " (" . $success . "/3)";
            $_SESSION['pesan'] = $pesan;
        } else {
            $pesan_error = "Gagal mengubah data Pembimbing: " . $namapembimbing . " (" . $success . "/3)" . "<br>" . mysqli_error($konek);
            $_SESSION['pesan_error'] = $pesan_error;
        }
        ?>
        <script>
            window.location.href = "pembimbing.php";
        </script>
        <?php
    }

    if (@$_GET['akses'] == 'tambah') {
    } elseif (@$_GET['akses'] == 'ubah') {
        // echo '<pre>';
        // print_r($_GET);
        // echo '</pre>';

        // $namalama = @$_GET['na'];
        $id = isset($_GET['i']) ? $_GET['i'] : null;

        if (!is_null($id)) {
            $query = mysqli_prepare($konek, "SELECT * FROM datapembimbing WHERE id = ?");
            mysqli_stmt_bind_param($query, "i", $id);

            mysqli_stmt_execute($query);
            $result = mysqli_stmt_get_result($query);
            $data = mysqli_fetch_assoc($result);
            mysqli_stmt_close($query);
        } else {
            $data = null;
        }

        $namapembimbing = @$data['nama'];
        $jurusanbimbingan = @$data['jur'];
        $nomortelepon = @$data['cp'];
    }
    ?>
    <style>
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        #form_tambahdudi .form-group {
            display: flex;
            margin-top: 20px;
        }

        #form_tambahdudi .form-group label {
            width: 30%;
        }

        #form_tambahdudi .form-group input {
            width: 70%;
        }
    </style>

    <div class="container">
        <h2>
            <i class="fas fa-plus"></i>&nbsp;
            Tambah Pembimbing
        </h2>

        <form id="form_tambahdudi" class="mx-5" method="post">
            <div class="mb-3 form-group">
                <label for="namapembimbing" class="form-label">Nama Pembimbing</label>
                <input type="text" class="form-control" id="namapembimbing" name="namapembimbing"
                    placeholder="Nama Lengkap + Gelar" value="<?= @$namapembimbing; ?>">
            </div>
            <div class="mb-3 form-group">
                <label for="jurusanbimbingan" class="form-label">Jurusan</label>
                <input type="text" class="form-control" id="jurusanbimbingan" name="jurusanbimbingan"
                    placeholder="contoh: Teknik Elektronika, ditulis TE" value="<?= @$jurusanbimbingan; ?>">
            </div>

            <div class="mb-3 form-group">
                <label for="nomortelepon" class="form-label">Nomor Telepon (WA)</label>
                <input type="number" class="form-control" id="nomortelepon" name="nomortelepon"
                    placeholder="contoh: 6285xxxxxxxx" value="<?= @$nomortelepon; ?>">
            </div>

            <?php if (@$_GET['akses'] == 'ubah') { ?>
                <input type="hidden" name="id" value="<?= $id; ?>">
                <input type="hidden" name="namalama" value="<?= $namapembimbing; ?>">
                <input type="hidden" name="nomorlama" value="<?= $nomortelepon; ?>">
                <input type="hidden" name="jurlama" value="<?= $jurusanbimbingan; ?>">
            <?php } ?>

            <button type="submit" name="pembimbing" value="<?= @$_GET['akses']; ?>"
                class="btn btn-sm border-0 btn-primary"><?= ucfirst(@$_GET['akses']); ?></button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
        crossorigin="anonymous"></script>

    <?php
    mysqli_close($konek);
} else {
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../admin';
        </script>";
}

include "../views/footer.php";
