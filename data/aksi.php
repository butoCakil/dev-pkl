<?php
if (isset($_SESSION["admin"])) {
    require "vendor/autoload.php";

    include "../koneksi.php";

    if ($konek) {
        echo "<div class='badge bg-dark m-1'><span class='text-success'>&#9673;&nbsp;</span>DB Ready</div>";
        echo '<br>';
    } else {
        echo "<div class='badge bg-danger m-1'><span class='text-light'>&#9673;&nbsp;</span>DB Gak Konek Boss</div>";
        echo '<br>';
    }

    if (@$_POST['upload']) {
        $error = "";
        $ekstensi = "";
        $success = "";
        $spreadsheet = "";
        $sheetData = "";

        $jumlah_kolom = @$_POST['upload'];

        if ($jumlah_kolom == '1') {
            $jumlah_kolom = 6;
            $tbl_db = "`datasiswa`";
            $text_query = "`nis`, `nama`, `kelas`, `jur`, `gander`, `nohp`";
        } elseif ($jumlah_kolom == '2') {
            $jumlah_kolom = 16;
            $tbl_db = "`datadudi`";
            $text_query = "`namadudi`, `alamat`, `kota`, `kuotacow`, `kuotacew`, `kuotatoal`, `kode`, `pembimbing`, `nowa`, `kos`, `beabim`, `beahidup`, `ket`, `map`, `jur`, `status`";
        } elseif ($jumlah_kolom == '3') {
            $jumlah_kolom = 9;
            $tbl_db = "`duditerisi`";
            $text_query = "`namadudi`, `alamat`, `kode`, `nis`, `namasiswa`, `kelas`, `gander`, `pembimbing`, `jur`";
        } else {
            $jumlah_kolom = 0;
            $tbl_db = "";
        }

        $file_name = $_FILES['filexls']['name'];
        $file_data = $_FILES['filexls']['tmp_name'];

        if (!$file_name) {
            $error .= "<li>Maukkan file xls/xlsx</li>";
        } else {
            $ekstensi = pathinfo($file_name)['extension'];
        }

        $ekstensi_diijinkan = array("xls", "xlsx");

        if (!in_array($ekstensi, $ekstensi_diijinkan)) {
            $error .= "<li>Masukkan file hanya yang ber-ekstensi XLS atau XLSX</li>";
        }

        if (!$error) {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_data);
            $spreadsheet = $reader->load($file_data);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            if (@$_POST['hapus'] == 'hapus') {
                $truncate_table = mysqli_query($konek, "TRUNCATE TABLE $tbl_db");

                if ($truncate_table) {
                    $success .= "<li>Tabel $tbl_db, berhasil dibersihkan.</li>";
                } else {
                    $success .= "<li>Tabel $tbl_db, GAGAL dibersihkan.</li>";
                }
            }

            $jumlahData = 0;
            $stmt = null;
            $param_type = "";
            $params = array();

            // Mendefinisikan jenis parameter dan nilai awal
            for ($i = 1; $i <= $jumlah_kolom; $i++) {
                $param_type .= "s";
                $params[$i] = "";
            }

            $sql_query = "INSERT INTO $tbl_db ($text_query) VALUES (";

            for ($i = 1; $i <= $jumlah_kolom; $i++) {
                $sql_query .= "?";
                $sql_query .= ($i < $jumlah_kolom) ? ", " : ")";
            }

            $stmt = mysqli_prepare($konek, $sql_query);

            if (!$stmt) {
                die('Query preparation failed: ' . mysqli_error($konek));
            }

            // Binding parameters
            $bind_params = array(&$param_type);
            for ($i = 1; $i <= $jumlah_kolom; $i++) {
                $bind_params[] = &$params[$i];
            }

            call_user_func_array(array($stmt, 'bind_param'), $bind_params);

            foreach ($sheetData as $row) {
                // Sanitasi dan memasukkan nilai ke dalam parameter
                for ($i = 1; $i <= $jumlah_kolom; $i++) {
                    $params[$i] = mysqli_real_escape_string($konek, $row[$i]);
                }

                // Eksekusi statement
                if (!mysqli_stmt_execute($stmt)) {
                    echo "<li>GAGAL: " . mysqli_stmt_error($stmt) . "</li>";
                } else {
                    $jumlahData++;
                }
            }

            mysqli_stmt_close($stmt);

            if ($jumlahData) {
                $success .= "<li>$jumlahData data berhasil dimasukkan ke database $tbl_db</li>";
            }
        }

        if ($error) {
            ?>
            <div class="alert alert-danger p-1"><?= $error; ?></div>
            <?php
        }

        if ($success) {
            ?>
            <div class="alert alert-success p-1"><?= $success; ?></div>
            <?php
        }
    }

    mysqli_close($konek);
} else {
    echo '<script>alert("Akses Ditolak!");';
    echo 'window.location.href = "../";</script>';
    exit;
}
?>