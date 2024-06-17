<?php 
session_start();
if (@$_SESSION["admin"] == "admin") { ?>
    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>Upload XLS</title>
    </head>

    <body>
        <style>
            .container {
                text-align: center;
            }

            .container #formFile {
                width: 50%;
                margin: auto;
            }
        </style>

        <div class="container p-2">
            <h4>Upload XLS ke Database</h4>
            <button class="btn btn-sm btn-secondary border-0" onclick="window.location='../admin';">
                << Kembali</button><br>
                    <?php
                    include "aksi.php";

                    for ($i = 0; $i < 3; $i++) {

                        if ($i == 0) {
                            $label = "<b>DATA SISWA</b>";
                            $file_download = "datasiswa.xls";
                        } elseif ($i == 1) {
                            $label = "<b>DATA DUDI</b>";
                            $file_download = "datadudi.xls";
                        } elseif ($i == 2) {
                            $label = "<b>DATA PENEMPATAN</b>";
                            $file_download = "duditerisi.xls";
                        } else {
                            $label = "-";
                            $file_download = "";
                        }
                    ?>
                        <hr>
                        <form action="" class="m-2" method="POST" enctype="multipart/form-data">
                            <div class="my-1">
                                <label for="formFile" class="form-label">Upload <?= $label; ?> file Excel (XLS, XLSX) ke database</label>
                                <div>
                                    <label for="contoh1"><i>Template Format Excel untuk <?= $label; ?></i></label>
                                    <a href="download/<?= $file_download; ?>" class="text-decoration-none" download>&nbsp;
                                        <svg style="color: rgb(23, 135, 8);" width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.79293 7.49998L2.14648 5.85353L2.85359 5.14642L4.50004 6.79287L6.14648 5.14642L6.85359 5.85353L5.20714 7.49998L6.85359 9.14642L6.14648 9.85353L4.50004 8.20708L2.85359 9.85353L2.14648 9.14642L3.79293 7.49998Z" fill="#178708"></path>
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 0C2.67157 0 2 0.671573 2 1.5V3H1.5C0.671573 3 0 3.67157 0 4.5V10.5C0 11.3284 0.671573 12 1.5 12H2V13.5C2 14.3284 2.67157 15 3.5 15H13.5C14.3284 15 15 14.3284 15 13.5V1.5C15 0.671573 14.3284 0 13.5 0H3.5ZM1.5 4C1.22386 4 1 4.22386 1 4.5V10.5C1 10.7761 1.22386 11 1.5 11H7.5C7.77614 11 8 10.7761 8 10.5V4.5C8 4.22386 7.77614 4 7.5 4H1.5Z" fill="#178708"></path>
                                        </svg>
                                        Download
                                    </a>
                                    <div class="alert alert-warning p-1 mb-2 w-75 mx-auto">
                                        <li>Jangan mengubah format tabel pada template, cukup ubah data sesuai format.</li>
                                        <li>Isi data kolom yang kosong dengan "0" (untuk bilangan/jumlah) atau "-" (untuk keterangan/nama).</li>
                                    </div>
                                </div>
                                <input class="form-control" type="file" name="filexls" id="formFile">
                            </div>

                            <div class="mb-1">
                                <input type="checkbox" name="hapus" value="hapus" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Hapus dan timpa Data Sebelumnya?</label>
                            </div>

                            <button type="submit" name="upload" value="<?= ($i + 1); ?>" class="btn btn-primary btn-sm">UPLOAD</button>
                        </form>
                    <?php } ?>
        </div>

        <!-- Optional JavaScript; choose one of the two! -->

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <!-- Option 2: Separate Popper and Bootstrap JS -->
        <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    </body>

    </html>

<?php
} else {
    // alert 
    echo "<script>
            alert('Maaf, Anda tidak memiliki hak akses ke halaman ini!');
            window.location.href='../admin';
        </script>";
} ?>