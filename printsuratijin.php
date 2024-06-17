<?php
if (@$_GET["aksi"] == "print3") {
    // zona waktu
    date_default_timezone_set("Asia/Jakarta");

    // function bulan indonesia
    function bulanindo($bul)
    {
        $bulan = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        );
        return $bulan[$bul];
    }

    // tanggal dengan bulan indonesia
    $tanggal_indo = date("d ") . bulanindo(date("m")) . date(" Y");

    $kodedudi = @$_GET["kode"];

    include "koneksi.php";

    $nis = @$_GET["nis"];

    $sql = "SELECT * FROM duditerisi WHERE kode = '$kodedudi'";
    $query = mysqli_query($konek, $sql);
    $jumlahsiswa = mysqli_num_rows($query);
    $data = mysqli_fetch_array($query);
    
    $sql2 = "SELECT * FROM datadudi WHERE kode = '$kodedudi'";
    $query2 = mysqli_query($konek, $sql2);
    $data2 = mysqli_fetch_array($query2);

    $namadudi = @$data["namadudi"];
    $alamat_dudi = @$data["alamat"];
    $kota = @$data2["kota"];

    // hitung jumlah data

?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Surat Ijin Prakerin</title>
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

        .book {
            width: 210mm;
            height: 297mm;
            page-break-after: always;
            flex-wrap: wrap;
            word-wrap: normal;
            padding-left: 20mm;
            padding-right: 20mm;
            /* paragraf rata kanan-kiri */
            text-align: justify;
        }

        pre {
            text-justify: auto;
            text-align: justify;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13pt;
            font-weight: 400;
        }

        p {
            /* background-color: chartreuse; */
            margin-left: 50mm;
            text-align: justify;
            display: flex;
            flex-wrap: wrap;
            width: 100mm;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13pt;
            font-weight: 400;
        }

        h5 {
            /* background-color: darkgrey; */
            margin-left: 50mm;
            margin-right: 20mm;
            font-size: 13pt;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 500;
        }

        #kopsurat {
            display: flex;
        }

        .book #kopsurat .logo img {
            height: 125px;
            margin-top: 75px;
            margin-left: 8mm;
        }

        .book #kopsurat .logo2 img {
            height: 125px;
            margin-top: 80px;
            margin-left: 8mm;
        }

        .book #kopsurat .judulkop {
            text-align: center;
            margin-left: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .book #kopsurat .judulkop p {
            margin-left: -2px;
            width: 620px;
            font-size: 12pt;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .book .border {
            border: 1px solid black;
            margin-top: 10px;
            margin-left: 8mm;
            width: 210mm;
        }

        .book .page .paragraf {
            text-align: justify;
            margin-left: 9mm;
            width: 210mm;
        }
        
        .book .page .paragraf, pre, .kepada, .ttd {
            line-height: 150%;
        }
        
        .namattd {
            line-height:60%;
        }

        .book .page .kepada {
            margin-left: 150mm;
            width: 70mm;
            flex-wrap: wrap;
        }

        .book .page .ttd, .book .page .namattd {
            margin-left: 150mm;
            width: 70mm;
        }

        .book .page h3 {
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>

    <body>
        <div class="book">

            <div id="kopsurat">
                <div class="logo">
                    <img src="Picture2.png" alt="">
                </div>
                <div class="judulkop">
                    <h2>PEMERINTAH PROVINSI JAWA TENGAH<br>DINAS PENDIDIKAN DAN KEBUDAYAAN<br>SEKOLAH MENENGAH KEJURUAN NEGERI BANSARI</h2>
                    <p>Dusun Putihan, Campuranom, Bansari, Temangung Kode Pos 56265 </p>
                    <p>Telepon: (0293)5921071 / 08112951545, Email: smkn1bansari@gmail.com</p>
                </div>
            </div>

            <div class="border"></div>
            <br><br>
            <div class="page">
                <pre>
        Nomor        : 421.4/500                                                                                  Bansari, <?= $tanggal_indo; ?> 
        Lampiran    : 2 Lembar
        Perihal        : Permohonan Izin Praktik Kerja Industri <br>
                </pre>
                <p class="kepada">
                    Kepada : <br>
                    <?php $namadudi = ucwords(strtolower($namadudi)); ?>
                    Pimpinan <?= $namadudi; ?> <br>
                    di <?= $kota; ?> <br>
                    <br>
                </p>
                <p class="paragraf">
                    Dengan hormat, <br>
                    Dalam rangka peningkatan penguasaan kompetensi peserta didik SMK Negeri Bansari,kami mewajibkan kepada siswa-siswi untuk melaksanakan Prakerin (Praktik Kerja Industri). Sehubungan dengan hal tersebut, kami mengharapkan kesediaan Bapak/Ibu untuk memberi kesempatan kepada siswa/siswi kami melaksanakan Prakerin (Praktik Kerja Industri) di Instansi yang Saudara/Bapak/Ibu pimpin.
                    Kegiatan Prakerin akan dilaksanakan pada Juli 2023 – Desember 2023. Adapun siswa yang akan mengikuti kegiatan Prakerin dengan kriteria sebagai berikut: <br>
                </p>
                <pre>

                Jumlah      : <?= $jumlahsiswa; ?> Siswa 
                Jurusan     : Teknik Elektronika 
                Kelas         : XI TE 
                Nama        : Terlampir 
                    </pre>
                <p class="paragraf">
                    Bersama dengan Surat ini kami lampirkan Lembar Konfirmasi Tempat Prakerin, Kami memohon kesediaan Saudara/Bapak/Ibu untuk mengisi Lembar Konfirmasi berikut sebagai jawaban kesediaan Saudara/Bapak/Ibu untuk mengijinkan peserta didik kami untuk melaksanakan Prakerin di Instansi yang Saudara/Bapak/Ibu pimpin. <br>
                    Demikian surat permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih. <br><br>
                </p>
                <p class="ttd">
                    Hormat kami <br>
                    Kepala SMK Negeri Bansari <br><br><br><br>
                </p>
                <p class="namattd"><u>Priyo Nugroho, S.T</u><br>
                    NIP. 19820522 200903 1 004 <br>
                </p>
            </div>
        </div>
        <div class="book">
            <div id="kopsurat">
                <div class="logo2">
                    <img src="Picture2.png" alt="">
                </div>
                <div class="judulkop">
                    <br>
                    <h2>PEMERINTAH PROVINSI JAWA TENGAH<br>DINAS PENDIDIKAN DAN KEBUDAYAAN<br>SEKOLAH MENENGAH KEJURUAN NEGERI BANSARI</h2>
                    <p>Dusun Putihan, Campuranom, Bansari, Temangung Kode Pos 56265 </p>
                    <p>Telepon (0293)5921071 / 08112951545 Surat Elektronik smkn1bansari@gmail.com</p>
                </div>
            </div>

            <div class="border"></div>
            <br><br>
            <div class="page">
                <h3>Daftar Siswa yang mengajukan Ijin Prakerin SMK Negeri Bansari 2023<br>di <?= $namadudi; ?><br>Periode Juli 2023 – Desember 2023</h3><br>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 90%;
                    }

                    th,
                    td {
                        padding: 8px;
                        text-align: left;
                        border-bottom: 1px solid #000;
                        border-right: 1px solid #000;
                    }

                    tr:hover {
                        background-color: #ff7
                    }
                </style>
                <table class="table border">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Program Keahlian</th>
                            <th>Pembimbing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($query as $row => $datasiswa) {
                        ?>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $datasiswa["nis"]; ?></td>
                                <td><?= $datasiswa["namasiswa"]; ?></td>
                                <td><?= $datasiswa["kelas"]; ?></td>
                                <td>Teknik Elektronika</td>
                                <td><?= $datasiswa["pembimbing"]; ?></td>
                            </tr>
                        <?php $no++;
                        } ?>
                </table>
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