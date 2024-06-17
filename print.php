<?php
if (@$_GET["akses"] == "print" && @$_GET["nis"]) {
    include "koneksi.php";

    $nis = @$_GET["nis"];

    $sql = "SELECT * FROM duditerisi WHERE nis = '$nis'";
    $query = mysqli_query($konek, $sql);
    $data = mysqli_fetch_array($query);

?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Cetak Surat Pernyataan</title>
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
            margin-left: 30mm;
            text-align: justify;
            display: flex;
            flex-wrap: wrap;
            width: 150mm;
            font-family: 'Courier New';
            font-weight: 400;
        }

        h5 {
            /* background-color: darkgrey; */
            margin-left: 30mm;
            margin-right: 20mm;
            font-size: 12pt;
            font-family: 'Courier New';
            font-weight: 500;
        }
    </style>

    <body>
        <div class="book">
            <h2>SURAT PERNYATAAN ORANG TUA / WALI MURID</h2>
            <br><br>
            <div class="page">
                <pre>
    Saya yang bertanda tangan dibawah ini :

    Nama                    : .......................................................
    Tempat, Tanggal Lahir   : .......................................................
    Pekerjaan               : .......................................................
    Status Keluarga         : .......................................................
    Alamat                  : .......................................................
                              .......................................................
                              .......................................................
    No Telp / WA            : .......................................................


<?php
    if ($data["gander"] == "L") {
        $siswa_i = "Siswa";
    } else if ($data["gander"] == "P") {
        $siswa_i = "Siswi";
    } else {
        $siswa_i = "Siswa(i)";
    }
?>
    Bahwa selaku orang tua/wali dari :

        Nama                    : <?= $data["namasiswa"]; ?>

        NIS                     : <?= $data["nis"]; ?>

        Kelas / Komp. Keahlian  : <?= $data["kelas"]; ?> / Teknik Elektronika
        No Telp / WA            : ............................

    Menyatakan dengan sesungguhnya, bahwa :

    1. Mengizinkan <?= $siswa_i; ?> yang tersebut di atas untuk mengikuti kegiatan Praktik Kerja
       Industri (Prakerin) di : 
            </pre>
            <div id="paragraf1">
                <p>Nama DUDIKA &nbsp;: <?= $data["namadudi"]; ?></p>
                <h5>Alamat &nbsp;&nbsp;: <?= $data["alamat"]; ?></h5>
            </div>
            <pre>

       yang akan dilaksanakan mulai bulan Juli 2023 sampai bulan Desember 2024.

    2. <?= $siswa_i; ?> Wajib mentaati dan mematuhi Protokol Kesehatan dalam Pelaksanaan Prakerin, 
       serta Peraturan yang berlaku selama berlangsungnya Prakerin di Dunia Industri.

    3. <?= $siswa_i; ?> tersebut diatas mengikuti kegiatan sesuai Jadwal yang ditetapkan satuan
       pendidikan.

    4. Dijatuhi Sanksi apabila selama mengikuti Prakerin di Industri <?= $siswa_i; ?> melanggar
       peraturan yang telah ditetapkan oleh Dunia Industri dan Satuan pendidikan.

    5. Orang tua diharapkan ikut serta membimbing dan mengawasi selama pelaksanaan
       prakerin berlangsung.

       Demikianlah surat pernyataan ini saya buat dengan sebenarnya dan dengan rasa 
    tanggung jawab yang penuh.

                                                                Bansari,  Mei 2023

                                                            Yang membuat pernyataan
                                                             Orang Tua/Wali Murid





                                                            (____________________)
                </pre>
            </div>
        </div>
    </body>

    </html>
    <script type="text/javascript">
        window.print();
    </script>

<?php
} else {
    echo "Akses ditolak!";
}
