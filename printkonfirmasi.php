<?php
if (@$_GET["aksi"] == "print2") {
    // Ambil nilai dari parameter GET
    $kodedudi = isset($_GET["kode"]) ? $_GET["kode"] : '';

    // Masukkan file koneksi.php yang berisi koneksi ke database
    include "koneksi.php";

    // Query SQL menggunakan prepared statement
    $sql = "SELECT * FROM duditerisi WHERE kode = ?";
    $stmt = mysqli_prepare($konek, $sql);

    // Bind parameter ke dalam prepared statement
    mysqli_stmt_bind_param($stmt, "s", $kodedudi);

    // Eksekusi prepared statement
    mysqli_stmt_execute($stmt);

    // Ambil hasil query
    $result = mysqli_stmt_get_result($stmt);

    // Hitung jumlah baris yang ditemukan
    $jumlahsiswa = mysqli_num_rows($result);

    // Ambil data sebagai array asosiatif
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

    // Ambil nilai-nilai yang diperlukan
    $namadudi = isset($data["namadudi"]) ? $data["namadudi"] : '';
    $alamat_dudi = isset($data["alamat"]) ? $data["alamat"] : '';

    // Tutup prepared statement
    mysqli_stmt_close($stmt);

    // Tutup koneksi ke database
    mysqli_close($konek);
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Lembar Konfirmasi Tempat Prakerin</title>
    </head>

    <style type="text/css">
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 12pt "Tahoma";
        }

        * {
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
        }

        h2 {
            text-align: center;
            margin-top: 70px;
        }

        .page {
            width: 210mm;
            height: 297mm;
            page-break-after: always;
            flex-wrap: wrap;
            word-wrap: normal;
            padding-left: 20mm;
            padding-right: 10mm;
            /* paragraf rata kanan-kiri */
            text-align: justify;
        }

        pre {
            text-justify: auto;
            text-align: justify;
        }

        p {
            /* background-color: chartreuse; */
            margin-left: 50mm;
            text-align: justify;
            display: flex;
            flex-wrap: wrap;
            width: 100mm;
            font-family: 'Courier New', Courier, monospace;
            font-weight: 400;
        }

        h5 {
            /* background-color: darkgrey; */
            margin-left: 50mm;
            margin-right: 20mm;
            font-size: 12pt;`
            font-family: 'Courier New', Courier, monospace;
            font-weight: 500;
        }
        
        .book {
            line-height: 150%;
        }
    </style>

    <body>
        <div class="book">
            <h2>LEMBAR KONFIRMASI TEMPAT PRAKERIN<br>
                SMK NEGERI BANSARI TAHUN PELAJARAN 2023/2024<br>
                PERIODE JULI 2023 S.D DESEMBER 2023</h2>
            <br><br>
            <div class="page">
                <pre>
        1.Pelaksana
          a.Nama (Ketua Kelompok) : <?= @$data["namasiswa"]; ?> (Kelas: <?= @$data["kelas"]; ?>)<br>
        2.Waktu dan Tempat 
          a.Hari	:
          b.Tanggal	:
          c.Dudi	: <?= $namadudi; ?><br>
        3.Konfirmasi Tempat Prakerin
          a.Jumlah siswa yang diterima : <?= $jumlahsiswa; ?> siswa
          b.Nama Siswa (Anggota lain) 
            <?php
            $no = 1;
            foreach ($query as $key => $value) {
                echo $no . ". " . $value["namasiswa"] . " (Kelas: " . $value["kelas"] . ")<br>            ";
                $no++;
            }
            ?>

          c.Keterangan
                …………………………………………………………………………………………………………………………………………………
            ……………………………………………………………………………………………………………………………………………………………
            ……………………………………………………………………………………………………………………………………………………………
            ……………………………………………………………………………………………………………………………………………………………

          d.Ttd dan cap institusi



            ……………………….
            </pre>
            </div>
        </div>
    </body>

    </html>
    <script type="text/javascript">
        window.print();
    </script>

<?php } else { ?>
    <h4>Akses Ditolak</h4>
<?php } ?>