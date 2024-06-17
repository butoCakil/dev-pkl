<?php
require "vendor/autoload.php";

$host   = "localhost";
$user = "u0360177_esepro";
$pass = "zg+dHrx69o8R";
$db = "u0360177_dudi";

$konek  = mysqli_connect($host, $user, $pass, $db);

if ($konek) {
    echo "<div class='badge bg-dark m-1'><span class='text-success'>&#9673;&nbsp;</span>DB Ready</div>";
    echo '<br>';
} else {
    echo "<div class='badge bg-danger m-1'><span class='text-light'>&#9673;&nbsp;</span>DB Gak Konek Boss</div>";
    echo '<br>';
}

if (@$_POST['upload']) {
    $error          = "";
    $ekstensi       = "";
    $success        = "";
    $spreadsheet    = "";
    $sheetData      = "";

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

    $file_name  = $_FILES['filexls']['name'];
    $file_data  = $_FILES['filexls']['tmp_name'];

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
        $reader         = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_data);
        $spreadsheet    = $reader->load($file_data);
        $sheetData      = $spreadsheet->getActiveSheet()->toArray();

        if (@$_POST['hapus'] == 'hapus') {

            $truncate_table = mysqli_query($konek, "TRUNCATE TABLE $tbl_db");

            if ($truncate_table) {
                $success .= "<li>Tabel $tbl_db, berhasil dibersihkan.</li>";
            } else {
                $success .= "<li>Tabel $tbl_db, GAGAL dibersihkan.</li>";
            }
        }

        $jumlahData = 0;
        for ($i = 1; $i < count($sheetData); $i++) {
            $string_kolom   = "";
            $sql_query      = "";

            $array_kolom = array();

            for ($kol = 1; $kol <= $jumlah_kolom; $kol++) {
                $array_kolom[$i][] = str_replace("\"", " ", str_replace("'", " ", $sheetData[$i][$kol]));
            }

            for ($kol = 0; $kol < $jumlah_kolom; $kol++) {
                $string_kolom .= "'" . $array_kolom[$i][$kol] . "'" . (($kol == ($jumlah_kolom - 1)) ? "" : ", ");
            }

            $sql_query = "INSERT INTO " . $tbl_db . " (" . $text_query . ") VALUES (" . $string_kolom . ")";

            // echo '<pre>';
            // print_r($sql_query);
            // echo '</pre>';

            $query = mysqli_query($konek, $sql_query);

            if ($query) {
                // echo "$string_kolom - OK<br>";
            } else {
                echo "<li>$string_kolom - GAGAL: " . mysqli_error($konek) . "</li>";
            }

            $jumlahData++;
        }

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

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// echo '<pre>';
// print_r(@$_FILES);
// echo '</pre>';

// echo '<pre>';
// print_r($file_name);
// echo '</pre>';

// echo '<pre>';
// print_r($file_data);
// echo '</pre>';

// echo '<pre>';
// print_r($ekstensi);
// echo '</pre>';
