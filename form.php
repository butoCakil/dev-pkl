<?php
if (@$_POST["akses"] == "rekap") {
    $nis = $_POST["nis"];

    echo "<script>window.location.href='semuarekap.php?nis=$nis';</script>";
}

if (@$_POST["akses"] == "presensi") {
    $nis = @$_POST["nis"];

    if ($nis) {
        // redirect ke presensi.php
        echo "<script>window.location.href='presensi.php?nis=$nis&akses=presensi';</script>";
    }
} else {
    if (@$_POST['kodedudi_'] && @$_POST['akses_']) {
        $link_next = '&kodedudi=' . $_POST['kodedudi_'] . '&akses=' . $_POST['akses_'];
    } else {
        $link_next = '';
    }

    $namadudi_temp = "";

    if ((@$_POST["akses"] == "login_nis") || (@$_POST["akses"] == "pilihtempat") || @$_POST["akses"] == "ubahpilihan" || @$_POST["akses"] == "cektempat") {
        $nis = mysqli_escape_string($konek, @$_POST["nis"]);

        if (!$nis) {
            $nis = mysqli_escape_string($konek, @$_POST["nissiswa"]);
        }

        include "koneksi.php";

        $sql = "SELECT * FROM duditerisi WHERE nis = ?";
        $stmt = mysqli_prepare($konek, $sql);
        mysqli_stmt_bind_param($stmt, "s", $nis);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);


        if ($data > 0 && @$_POST["akses"] != "ubahpilihan") {
            $kode_dudi = mysqli_escape_string($konek, $data["kode"]);
            $stmt = mysqli_prepare($konek, "SELECT map, kuotatoal FROM datadudi WHERE kode = ?");
            mysqli_stmt_bind_param($stmt, "s", $kode_dudi);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $lokasi_dudi, $kuota);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            ?>
            <h3><i class="fa-solid fa-circle-info fa-fade text-info"></i>&nbsp;Sudah terdaftar ke tempat Perakerin!</h3>

            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">Nama </span>
                <input type="text" class="form-control" value="<?= $data["namasiswa"]; ?>" disabled>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">NIS </span>
                <input type="text" class="form-control" value="<?= $data["nis"]; ?>" disabled>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">Kelas </span>
                <input type="text" class="form-control" value="<?= $data["kelas"]; ?>" disabled>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">Nama DU/Di </span>
                <input type="text" class="form-control" value="<?= $data["namadudi"]; ?>" disabled>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">Alamat</span>
                <textarea class="form-control" rows="3" disabled><?= $data["alamat"]; ?></textarea>
            </div>
            <div class="mb-3">
                <a href="<?= $lokasi_dudi; ?>" class="btn btn-outline-primary btn-sm" target="_blank"><i
                        class="fa-solid fa-location-dot text-danger fa-bounce"></i>&nbsp;Lihat Lokasi di Maps</a>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">Pembimbing </span>
                <input type="text" class="form-control" value="<?= $data_lokasi["pembimbing"]; ?>" disabled>
                <?php
                $msg_nama = str_replace(" ", "%20", $data["namasiswa"]);
                $msg_kelas = str_replace(" ", "%20", $data["kelas"]);
                $link_wa = "https://api.whatsapp.com/send?phone=" . $data_lokasi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $data["namadudi"] . ".%0A";
                ?>
                <span class="input-group-text bg-success btn-success"><a href="<?= $link_wa; ?>" class="text-light"><i
                            class="fa-brands fa-whatsapp fa-beat"
                            style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a></span>
            </div>

            <p>Telah memilih DU/DI ini: (Kuota Tersisa: <?= $kuota; ?>)</p>

            <?php
            // Query dengan prepared statement
            $aql = "SELECT * FROM duditerisi WHERE kode LIKE ?";
            $stmt = mysqli_prepare($konek, $aql);

            // Buat parameter untuk LIKE statement
            $kode_like = '%' . $data['kode'] . '%';

            // Bind parameter ke dalam statement
            mysqli_stmt_bind_param($stmt, "s", $kode_like);

            // Jalankan statement
            mysqli_stmt_execute($stmt);

            // Ambil hasil query
            $result = mysqli_stmt_get_result($stmt);

            // Iterasi untuk menampilkan hasil
            $i = 0;
            while ($hasil = mysqli_fetch_array($result)) {
                $i++;
                echo "<p>" . $i . ". " . $hasil["namasiswa"] . " (" . $hasil["gander"] . ")" . " (" . $hasil["kelas"] . ")</p>";
            }

            // Tutup statement
            mysqli_stmt_close($stmt);

            ?>

            <br>
            <a onclick="alert('Tidak bisa mengganti tempat Perakrin begitu saja, silakan hubungi Pembimbing!\n\nKlik tombol dengan logo Whatsapp di kolom nama pembimbing.');"
                class="btn btn-warning border-0"><i class="fa-solid fa-pen-to-square fa-shake"></i>&nbsp;Ubah</a href="">
            <a href="print.php?akses=print&nis=<?= $nis; ?>&kode=<?= $hasil["kode"]; ?>" class="btn btn-info border-0"><i
                    class="fa-solid fa-print fa-beat" style="--fa-beat-scale: 1.5; --fa-animation-duration: 0.5s;"></i>&nbsp;Cetak
                Surat Pernyataan</a>
            <a href="/" class="btn btn-dark border-0"><i class="fa-solid fa-angle-left fa-fade"></i>&nbsp;Kembali</a>
            <?php
        } else {
            // Prepared statement untuk mencari data siswa berdasarkan NIS
            $sql = "SELECT * FROM datasiswa WHERE nis = ?";
            $stmt = mysqli_prepare($konek, $sql);

            // Bind parameter ke dalam statement
            mysqli_stmt_bind_param($stmt, "s", $nis);

            // Jalankan statement
            mysqli_stmt_execute($stmt);

            // Ambil hasil query
            $result = mysqli_stmt_get_result($stmt);

            // Ambil data hasil query sebagai array asosiatif
            $row = mysqli_fetch_assoc($result);

            // Periksa apakah hasil query mengembalikan data
            if ($row) {
                // Jika ada data, buat link_next_ dengan data yang ditemukan
                $link_next_ = "index.php?nissiswa=" . $nis . "&namasiswa=" . $row['nama'] . "&kelas=" . $row['kelas'] . "&gander=" . $row['gander'] . $link_next;

                // Redirect menggunakan JavaScript
                echo "<script>location.href='$link_next_';</script>";
            } else {
                // Jika tidak ada data yang ditemukan, tambahkan penanganan kesalahan atau pengalihan lainnya
                echo "Data siswa dengan NIS $nis tidak ditemukan.";
            }

            // Tutup statement
            mysqli_stmt_close($stmt);

            if ($row > 0) {

                if (@$_POST["akses"] == "pilihtempat" || @$_POST["akses"] == "cektempat") {
                    $nis = @$_POST["nissiswa"];
                    $namasiswa = @$_POST["namasiswa"];
                    $kelas = @$_POST["kelas"];
                    $gander = @$_POST["gander"];
                    $kode_dudi_pilihan = @$_POST["kodedudi"];
                }

                if (@$_POST["checkbox"] == "yakin" && @$_POST["akses"] == "pilihtempat") {
                    // Prepared statement untuk mencari data dudi berdasarkan kode
                    $sql_select_dudi = "SELECT * FROM datadudi WHERE kode = ?";
                    $stmt_select_dudi = mysqli_prepare($konek, $sql_select_dudi);
                    mysqli_stmt_bind_param($stmt_select_dudi, "s", $kode_dudi_pilihan);
                    mysqli_stmt_execute($stmt_select_dudi);
                    $result_dudi = mysqli_stmt_get_result($stmt_select_dudi);
                    $row_dudi = mysqli_fetch_assoc($result_dudi);

                    // Periksa apakah data dudi ditemukan
                    if ($row_dudi) {
                        // Ambil nilai kuota dari hasil query
                        $kuota = $row_dudi["kuotatoal"];
                        $kuota_L = $row_dudi["kuotacow"];
                        $kuota_P = $row_dudi["kuotacew"];
                        $pembimbing = $row_dudi["pembimbing"];

                        // Periksa apakah kuota masih tersedia
                        if ($kuota > 0) {
                            $kuota = $kuota - 1;

                            // Kurangi kuota berdasarkan jenis kelamin
                            if ($gander == "L") {
                                if ($kuota_L > 0) {
                                    $kuota_L = $kuota_L - 1;
                                } else {
                                    echo "<script>alert('Kuota Laki-laki sudah habis!');</script>";
                                    echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                                    exit; // Keluar dari skrip jika kuota habis
                                }
                            } else if ($gander == "P") {
                                if ($kuota_P > 0) {
                                    $kuota_P = $kuota_P - 1;
                                } else {
                                    echo "<script>alert('Kuota Perempuan sudah habis!');</script>";
                                    echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                                    exit; // Keluar dari skrip jika kuota habis
                                }
                            }

                            // Prepared statement untuk update kuota dudi
                            $sql_update_kuota = "UPDATE datadudi SET kuotatoal = ?, kuotacow = ?, kuotacew = ? WHERE kode = ?";
                            $stmt_update_kuota = mysqli_prepare($konek, $sql_update_kuota);
                            mysqli_stmt_bind_param($stmt_update_kuota, "iiis", $kuota, $kuota_L, $kuota_P, $kode_dudi_pilihan);
                            mysqli_stmt_execute($stmt_update_kuota);

                            // Prepared statement untuk insert data ke duditerisi
                            $sql_insert_duditerisi = "INSERT INTO duditerisi (namadudi, alamat, kode, nis, namasiswa, kelas, gander, pembimbing) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $stmt_insert_duditerisi = mysqli_prepare($konek, $sql_insert_duditerisi);
                            mysqli_stmt_bind_param($stmt_insert_duditerisi, "ssssssss", $row_dudi['namadudi'], $row_dudi['alamat'], $row_dudi['kode'], $nis, $namasiswa, $kelas, $gander, $pembimbing);
                            $query_insert = mysqli_stmt_execute($stmt_insert_duditerisi);

                            // Periksa apakah query insert berhasil
                            if ($query_insert) {
                                echo "<script>alert('Berhasil!');</script>";
                                echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                            } else {
                                echo "<script>alert('Gagal saat memasukkan data ke duditerisi!');</script>";
                                echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                            }
                        } else {
                            echo "<script>alert('Gagal! Kuota telah kosong!');</script>";
                            echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                        }
                    } else {
                        echo "<script>alert('Data dudi dengan kode $kode_dudi_pilihan tidak ditemukan!');</script>";
                        echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                    }

                    // Tutup statement
                    mysqli_stmt_close($stmt_select_dudi);
                }
                ?>

                <h1>Pemilihan Tempat Prakerin</h1>

                <form action="index.php" method="GET">
                    <input type="hidden" name="cektmbl" value="pushon">
                    <input type="hidden" name="nissiswa" value="<?= $row["nis"]; ?>">
                    <input type="hidden" name="namasiswa" value="<?= $row["nama"]; ?>">
                    <input type="hidden" name="kelas" value="<?= $row["kelas"]; ?>">
                    <input type="hidden" name="gander" value="<?= $row["gander"]; ?>">

                    <div class="form-disabled">
                        <div>
                            <label for="nis" class="form-label">NIS</label>
                            <input type="number" class="form-control" id="nis" name="nissiswa" value="<?= $row["nis"]; ?>" disabled>
                        </div>
                        <div>
                            <label for="namasiswa" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="namasiswa" name="namasiswa" value="<?= $row["nama"]; ?>"
                                disabled>
                        </div>
                        <div>
                            <label for="kelas" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" value="<?= $row["kelas"]; ?>" disabled>
                        </div>
                        <div>
                            <label for="gander" class="form-label">L/P</label>
                            <input type="text" class="form-control" id="gander" name="gander" value="<?= $row["gander"]; ?>" disabled>
                        </div>
                    </div>
                    <br>
                    <div class="mb-3">
                        <label for="gander" class="form-label text-dark">Pilihan DU/DI Tempat Prakerin</label>
                        <div id="infocekbox">
                            <p class="text-primary"><i class="fa-solid fa-circle-info"></i>&nbsp;Untuk melihat informasi tempat
                                prakerin, pilih DUDIKA berikut, kemudian klik tombol <button type="button"
                                    class="btn btn-info btn-sm border-0" disabled><i class="fa-solid fa-eye"></i>&nbsp;Cek info
                                    DUDIKA</button> di bawah ini.</p>
                        </div>

                        <select name="kodedudi" class="form-select" aria-label="Default select example">
                            <option value="">Belum Memilih</option>
                            <?php
                            $kuota_gander = "";

                            if (isset($row["gander"])) {
                                $gander = strtolower($row["gander"]);

                                if ($gander == "l") {
                                    $kuota_gander = "kuotacow";
                                } else if ($gander == "p") {
                                    $kuota_gander = "kuotacew";
                                } else {
                                    $kuota_gander = "kuotatoal";
                                }
                            } else {
                                $kuota_gander = "kuotatoal"; // Default jika $row["gander"] tidak ada
                            }

                            // Prepared statement untuk mengambil data dudi
                            $sql = "SELECT * FROM datadudi WHERE $kuota_gander > 0 OR (kuotatoal > 0 AND kuotacew = 0 AND kuotacow = 0) ORDER BY namadudi ASC";
                            $stmt = mysqli_prepare($konek, $sql);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);

                            // Loop untuk menampilkan opsi dalam dropdown
                            while ($rowdudi = mysqli_fetch_assoc($result)) {
                                $kode = $rowdudi["kode"];
                                $selected = ($kode == $kode_dudi_pilihan) ? "selected" : "";
                                ?>
                                <option value="<?= htmlspecialchars($kode); ?>" <?= $selected; ?>>
                                    <?= htmlspecialchars($rowdudi["namadudi"]); ?> (Kuota: <?= $rowdudi["kuotatoal"]; ?>
                                    <?php if ($rowdudi["kuotacow"] || $rowdudi["kuotacew"]) { ?>
                                        , L: <?= $rowdudi["kuotacow"]; ?>, P: <?= $rowdudi["kuotacew"]; ?>
                                    <?php } ?>
                                    )
                                </option>
                            <?php }

                            // Tutup statement
                            mysqli_stmt_close($stmt);
                            ?>
                        </select>
                    </div>


                    <!-- <label for="cekdudi_btn" class="form-label text-info text-center mb-3"><i class="fa-solid fa-circle-question"></i>&nbsp;Klik tombol ini untuk melihat informasi tempat prakerin yang dipilih.</label> -->
                    <div class="d-grid gap-2 d-md-block">
                        <button id="cekdudi_btn" type="submit" class="btn btn-info btn-sm mb-3 border-0" name="akses"
                            value="cektempat"><i class="fa-solid fa-eye fa-beat-fade"></i>&nbsp;&nbsp;Cek info DUDIKA</button>
                    </div>

                    <?php

                    if ((@$_POST["akses"] == "pilihtempat") || @$_POST["akses"] == "cektempat") {
                        // Menggunakan prepared statement untuk mencari data dudi berdasarkan kode
                        $daftardudi = "SELECT * FROM datadudi WHERE kode = ?";
                        $stmt_daftardudi = mysqli_prepare($konek, $daftardudi);
                        mysqli_stmt_bind_param($stmt_daftardudi, "s", $kode_dudi_pilihan);
                        mysqli_stmt_execute($stmt_daftardudi);
                        $result_daftardudi = mysqli_stmt_get_result($stmt_daftardudi);
                        $rowdudi = mysqli_fetch_assoc($result_daftardudi);

                        // Menggunakan prepared statement untuk mencari jumlah pendaftar berdasarkan kode dudi
                        $telahdaftar = "SELECT * FROM duditerisi WHERE kode = ?";
                        $stmt_telahdaftar = mysqli_prepare($konek, $telahdaftar);
                        mysqli_stmt_bind_param($stmt_telahdaftar, "s", $kode_dudi_pilihan);
                        mysqli_stmt_execute($stmt_telahdaftar);
                        $result_telahdaftar = mysqli_stmt_get_result($stmt_telahdaftar);
                        $jumlahtelahdaftar = mysqli_num_rows($result_telahdaftar);

                        // Cek jenis kelamin siswa
                        if ($rowdudi["gander"] == "L") {
                            $kuota_gander = "kuotacow";
                            $gander = "Laki-laki";
                        } elseif ($rowdudi["gander"] == "P") {
                            $kuota_gander = "kuotacew";
                            $gander = "Perempuan";
                        } else {
                            $kuota_gander = "kuotatoal";
                            $gander = "Laki-laki & Perempuan";
                        }

                        // Tutup statement
                        mysqli_stmt_close($stmt_daftardudi);
                        mysqli_stmt_close($stmt_telahdaftar);

                        if ((!@$rowdudi["kuotacow"] && !@$rowdudi["kuotacew"]) && @$rowdudi["kuotatoal"] > 0) {
                            $kuota_gander = "kuotatoal";
                        }

                        if ($rowdudi[$kuota_gander]) {
                            ?>
                            <div>
                                <h6>Informasi tempat Prakerin</h6>
                                <div class="alert alert-info" role="alert">
                                    <p>
                                        <?php $namadudi_temp = $rowdudi["namadudi"]; ?>
                                        <span class="badge text-bg-primary"><i class="fa-solid fa-briefcase"></i>&nbsp;&nbsp;Nama DU/DI :
                                        </span><br><?= $rowdudi["namadudi"]; ?> <br>
                                        <span class="badge text-bg-primary"><i class="fa-solid fa-circle-question"></i>&nbsp;&nbsp;Keterangan :
                                        </span><br><?= $rowdudi["ket"]; ?> <br>
                                        <span class="badge text-bg-primary"><i class="fa-solid fa-map-location-dot"></i>&nbsp;&nbsp;Alamat :
                                        </span><br><?= $rowdudi["alamat"]; ?> <br>
                                        <span class="badge text-bg-light text-primary"><i
                                                class="fa-solid fa-location-crosshairs fa-beat"></i></span>&nbsp;<span
                                            class="badge text-bg-light text-primary"><i
                                                class="fa-solid fa-location-arrow fa-shake"></i></span>&nbsp;<a id="btn_loc_1"
                                            href="<?= $rowdudi["map"]; ?>" class="btn btn-light btn-sm border-0" target="_blank"><i
                                                class="fa-solid fa-location-dot fa-bounce text-danger"></i>&nbsp;Lihat Lokasi</a><br><br>
                                        <span class="badge text-bg-secondary"><i class="fa-solid fa-house"></i>&nbsp;&nbsp;Info Kos :
                                        </span><br><?= $rowdudi["kos"]; ?> <br>
                                        <span class="badge text-bg-secondary"><i class="fa-solid fa-money-bill-trend-up"></i>&nbsp;&nbsp;Biaya
                                            Bimbingan : </span><br><?= $rowdudi["beabim"]; ?> <br>
                                        <span class="badge text-bg-secondary"><i class="fa-solid fa-money-bill-wheat"></i>&nbsp;&nbsp;Biaya
                                            Hidup : </span><br><?= $rowdudi["beahidup"]; ?> <br><br>
                                        <span class="badge text-bg-dark"><i class="fa-solid fa-user-gear"></i>&nbsp;&nbsp;Pembimbing :
                                        </span><br><?= $rowdudi["pembimbing"] ? $rowdudi["pembimbing"] : "-"; ?>

                                        <?php
                                        $msg_nama = str_replace(" ", "%20", $row["nama"]);
                                        $msg_kelas = str_replace(" ", "%20", $row["kelas"]);
                                        $link_wa = "https://api.whatsapp.com/send?phone=" . @$rowdudi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $rowdudi["namadudi"] . ".%0A";
                                        ?>
                                        <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i
                                                class="fa-brands fa-whatsapp fa-beat"
                                                style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                                        <br><br>
                                        Sisa kuota : <span class="badge text-bg-danger"><?= $rowdudi["kuotatoal"]; ?></span> <br>

                                        <?php if ($rowdudi["kuotacow"] || $rowdudi["kuotacew"]) { ?>
                                            Kuota Laki-laki (L) : <span
                                                class="badge text-bg-primary"><?= $rowdudi["kuotacow"] ? $rowdudi["kuotacow"] : "-"; ?></span><br>
                                            Kuota Perempuan (P) : <span
                                                class="badge text-bg-warning"><?= $rowdudi["kuotacew"] ? $rowdudi["kuotacew"] : "-"; ?></span><br>
                                        <?php } ?>

                                        <br>
                                        Jumlah Siswa yang telah terdaftar :
                                        <?= $jumlahtelahdaftar ? $jumlahtelahdaftar : '<span class="badge text-bg-success">- Belum ada yang memilih -</span>'; ?><br>
                                        <?php
                                        $no = 0;
                                        while ($rowtelahdaftar = mysqli_fetch_assoc($querytelahdaftar)) {
                                            $no++;
                                            ?>
                                            <?= $no; ?>. <?= $rowtelahdaftar["namasiswa"]; ?> (<?= $rowtelahdaftar["gander"]; ?>)
                                            (<?= $rowtelahdaftar["kelas"]; ?>) <br>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div>
                                <h6>Informasi tempat Prakerin</h6>
                                <?php
                                if (@$_POST["cektmbl"] == "pushon") { ?>
                                    <div class="alert alert-danger" role="alert">
                                        Pilih DUDIKA / Tempat Prakerin terlebih dahulu. Baru kemudian klik cek info DUDIKA.<br><br>
                                        Hubungi Admin, jika dirasa ada yang salah.
                                    <?php } else { ?>
                                        <div class="alert alert-warning" role="alert">
                                            Kuota <span class="badge text-bg-warning"><?= $gander; ?></span> untuk tempat ini
                                            <?= @$rowdudi["namadudi"] ? "(" . $rowdudi["namadudi"] . ")" : "-"; ?> sudah <b>penuh</b> / tidak ada.
                                            Silakan pilih DUDIKA yang lainnya.
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>

                        <br>

                        <?php if (@$_POST["akses"] == "cektempat") { ?>
                            <div id="infocekbox" class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="checkbox" value="yakin" id="checkbox">
                                <label class="form-check-label text-danger" for="checkbox">Yakin? Akan memilih DU/DI ini?</label>

                                <p>
                                    <span class="text-primary"><i
                                            class="fa-solid fa-triangle-exclamation fa-beat-fade text-danger"></i>&nbsp;Centang cek list ini
                                        setelah memilih tempat yang diinginkan! Pastikan DU/DI yang dipilih telah sesuai. Jika sudah yakin.
                                        Klik Centang, kemudian klik "pilih"</span><br>
                                    <span class="text-success"><i class="fa-solid fa-circle-info text-info fa-beat-fade"></i>&nbsp;Jika
                                        belum ingin memilih, jangan dicentang dulu.</span><br>
                                    <span class="text-warning"><i class="fa-solid fa-triangle-exclamation fa-beat-fade"></i>&nbsp;Hanya satu
                                        kali pilih!</span>
                                </p>
                            </div>
                            <div class="d-grid gap-2 d-md-block">
                                <button type="submit" class="btn btn-success border-0" name="akses" value="pilihtempat"
                                    onclick="return confirm(`Akan memilih \'<?= $rowdudi['namadudi']; ?>\' sebagai tempat prakerin.\nYakin?`)"><i
                                        class="fa-solid fa-circle-check fa-beat"
                                        style="--fa-beat-scale: 1.8; --fa-animation-duration: 2s;"></i>&nbsp;Pilih</button>
                            </div>
                        <?php } ?>
                </form>
                <div class="d-grid gap-2 d-md-block mt-2">
                    <a class="btn border-0" href="/"><i class="fa-solid fa-chevron-left fa-beat"></i>&nbsp;Kembali</a>
                </div>
                <br><br><br>
                <?php
            } else {
                echo "<script>alert('NIS tidak ditemukan!');</script>";
                echo "<script>location.href='index.php';</script>";
            }
        }

        mysqli_close($konek);
    } else {
        echo "ERROR: 404";
    }
}
