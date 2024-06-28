<?php
if (@$_GET) {
    $nis = $_GET['nis'];
    $_nohp = $_GET['nohp'];

    // cari nama dan nomor
    include "../koneksi.php";

    // Pastikan variabel $nis sudah didefinisikan sebelumnya
    if (isset($nis)) {
        // Gunakan prepared statement untuk mengamankan kueri SQL
        $stmt = mysqli_prepare($konek, "SELECT * FROM datasiswa WHERE nis = ?");
        mysqli_stmt_bind_param($stmt, "s", $nis);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Pastikan hasil kueri tidak kosong
        if (mysqli_num_rows($result) > 0) {
            // Ambil data siswa dari hasil kueri
            $data_siswa = mysqli_fetch_assoc($result);

            // Ambil nilai-nilai yang dibutuhkan
            $nis = $data_siswa['nis'];
            $nama = $data_siswa['nama'];
            $kelas = $data_siswa['kelas'];
            $nohp_ = $data_siswa['nohp'];

            // Perbaiki penanganan nomor telepon
            if ($nohp_ == "-") {
                $nohp_ = "";
            }
        } else {
            // Handle jika data tidak ditemukan
            $nis = "Data siswa tidak ditemukan";
            $nama = "";
            $kelas = "";
            $nohp_ = "";
        }

        // Tutup statement prepared
        mysqli_stmt_close($stmt);


        if (isset($nohp_)) {
            // cari pesan
            $sql_query_chatbot = mysqli_query($konek, "SELECT * FROM loadchat WHERE nomor LIKE '%$nohp_%'");
            $data_chat = array();

            foreach ($sql_query_chatbot as $dtc) {
                $data_chat[] = $dtc;

                $status = $dtc['status'];
                ?>
                <div class="chatditerima">
                    <div class="namachat">
                        <?php if ($status == "diterima") { ?>
                            <label for="">(<?= $nis; ?>)</label>
                            <label for=""><?= $nama; ?></label>
                            <label for="">(<?= $kelas; ?>)</label>
                            <label for="">(<?= $status; ?>)</label>
                        <?php } else { ?>
                            <div id="dikirim">
                                <label for="">Anda </label>
                                <label for="">(<?= $status; ?>)</label>
                            </div>
                        <?php } ?>
                    </div>
                    <div>
                        <?php
                        if ($status == "diterima") {
                            $bg = ' id = ""';
                        } else {
                            $bg = ' id = "dikirim"';
                        }
                        ?>
                        <p<?= $bg; ?>><?= $dtc['pesan']; ?>
                            <br>
                            <label class="timestamp"><?= $dtc['timestamp']; ?> | <?= $dtc['nomor'];
                               ; ?></label class="timestamp">
                            <?php
                            if (@$dtc['media']) {
                                ?>
                                <a href="<?= $dtc['media']; ?>" class="timestamp">&check;&nbsp;download media</a>
                                <?php
                            }
                            ?>
                            <hr>
                            </p>
                    </div>
                </div>
                <?php
            }

            if ($data_chat) {
                // echo "Ada data<br>";
            } else {
                echo "$nis - $nama - Tidak ada chat<br>";
            }
        } else {
            echo "$nis - $nama - Nomor WA belum tercatat<br>";
        }
    }

    mysqli_close($konek);
    
    // echo "<pre>";
    // print_r($data_chat);
    // echo "</pre>";
} else {
    echo "Request tidak lengkap.<br>";
}
?>

<style>
    .timestamp {
        font-size: 10px;
        margin-top: -10px;
    }

    .namachat label {
        font-size: 12px;
        font-weight: bold;
    }

    .chatditerima #dikirim {
        background-color: aqua;
        border-radius: 5px;
        text-align: right;
        padding: 2px;
    }
</style>