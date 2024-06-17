<?php
session_start();
if (@$_SESSION["admin"] == "admin") {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kirim Pesan Broadcast</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    </head>

    <body>

        <div class="container">
            <h4>Kirim Pesan Broadcast ke Siswa</h4>
            <form action="autowasis.php" method="get">
                <div class="mb-2">
                    <textarea class="form-control" name="brotkes" id="" cols="30" rows="10" required></textarea>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-success border-0" onclick="return confirm('Pesan akan dikirim ke semua nomor siswa yang tercatat. Yakin?');">Kirim</button>
                    <a class="btn btn-dark border-0" href="../admin/">Batal</a>
                </div>
            </form>
        </div>

    </body>

    </html>
<?php } else { ?>
    <script>
        alert("Tidak berhak akses kecuali admin");
        window.location.href = "../";
    </script>
<?php } ?>