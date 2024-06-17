<?php
if (@$_GET["akses"] == "rekap") {
    $nis = $_GET["nis"];

    echo "<script>window.location.href='semuarekap.php?nis=$nis';</script>";
}

if (@$_GET["akses"] == "presensi") {
    $nis = @$_GET["nis"];

    if ($nis) {
        // redirect ke presensi.php
        echo "<script>window.location.href='presensi.php?nis=$nis&akses=presensi';</script>";
    }
} else {
    if (@$_GET['kodedudi_'] && @$_GET['akses_']) {
        $link_next = '&kodedudi=' . $_GET['kodedudi_'] . '&akses=' . $_GET['akses_'];
    } else {
        $link_next = '';
    }
    
    $namadudi_temp = "";
    
    if ((@$_GET["akses"] == "login_nis") || (@$_GET["akses"] == "pilihtempat") || @$_GET["akses"] == "ubahpilihan" || @$_GET["akses"] == "cektempat") {
        $nis = @$_GET["nis"];
        
        if (!$nis) {
            $nis = @$_GET["nissiswa"];
        }
        
        // cek nis  di duditerisi
        include "koneksi.php";
        $sql = "SELECT * FROM duditerisi WHERE nis = '$nis'";
        $query = mysqli_query($konek, $sql);
        $data = mysqli_fetch_array($query);

        if ($data > 0 && @$_GET["akses"] != "ubahpilihan") {
            $kode_dudi = $data["kode"];

            $lokasi_query = "SELECT * FROM datadudi WHERE kode = '$kode_dudi'";
            $query_lokasi = mysqli_query($konek, $lokasi_query);
            $data_lokasi = mysqli_fetch_array($query_lokasi);
            $lokasi_dudi = $data_lokasi["map"];
            $kuota = $data_lokasi["kuotatoal"];
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
                <a href="<?= $lokasi_dudi; ?>" class="btn btn-outline-primary btn-sm" target="_blank"><i class="fa-solid fa-location-dot text-danger fa-bounce"></i>&nbsp;Lihat Lokasi di Maps</a>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-dark text-light gradient">Pembimbing </span>
                <input type="text" class="form-control" value="<?= $data_lokasi["pembimbing"]; ?>" disabled>
                <?php
                $msg_nama = str_replace(" ", "%20", $data["namasiswa"]);
                $msg_kelas = str_replace(" ", "%20", $data["kelas"]);
                $link_wa = "https://api.whatsapp.com/send?phone=" . $data_lokasi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $data["namadudi"] . ".%0A";
                ?>
                <span class="input-group-text bg-success btn-success"><a href="<?= $link_wa; ?>" class="text-light"><i class="fa-brands fa-whatsapp fa-beat" style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a></span>
            </div>

            <p>Telah memilih DU/DI ini: (Kuota Tersisa: <?= $kuota; ?>)</p>

            <?php

            $aql = "SELECT * FROM duditerisi WHERE kode LIKE '$data[kode]'";
            $hasil_telah_daftar = mysqli_query($konek, $aql);

            $i = 0;
            while ($hasil = mysqli_fetch_array($hasil_telah_daftar)) {
                $i++;
                echo "<p>" . $i . ". " . $hasil["namasiswa"] . " (" . $hasil["gander"] . ")" . " (" . $hasil["kelas"] . ")</p>";
            }

            $aql = "SELECT * FROM duditerisi WHERE kode LIKE '$data[kode]'";
            $query = mysqli_query($konek, $aql);
            $hasil = mysqli_fetch_array($query);
            ?>

            <br>
            <a onclick="alert('Tidak bisa mengganti tempat Perakrin begitu saja, silakan hubungi Pembimbing!\n\nKlik tombol dengan logo Whatsapp di kolom nama pembimbing.');" class="btn btn-warning border-0"><i class="fa-solid fa-pen-to-square fa-shake"></i>&nbsp;Ubah</a href="">
            <a href="print.php?akses=print&nis=<?= $nis; ?>&kode=<?= $hasil["kode"]; ?>" class="btn btn-info border-0"><i class="fa-solid fa-print fa-beat" style="--fa-beat-scale: 1.5; --fa-animation-duration: 0.5s;"></i>&nbsp;Cetak Surat Pernyataan</a>
            <a href="/" class="btn btn-dark border-0"><i class="fa-solid fa-angle-left fa-fade"></i>&nbsp;Kembali</a>
            <?php
        } else {

            $sql = "SELECT * FROM datasiswa WHERE nis = '$nis'";
            $query = mysqli_query($konek, $sql);
            $row = mysqli_fetch_assoc($query);

            if ($link_next && $row > 0) {
                $link_next_ = "index.php?nissiswa=" . $nis . "&namasiswa=" . $row['nama'] . "&kelas=" . $row['kelas'] . "&gander=" . $row['gander'] . $link_next;

                // header("Location: " . $link_next_);
                echo "<script>location.href='$link_next_';</script>";
            }

            if ($row > 0) {

                if (@$_GET["akses"] == "pilihtempat" || @$_GET["akses"] == "cektempat") {
                    $nis = $_GET["nissiswa"];
                    $namasiswa = $_GET["namasiswa"];
                    $kelas = $_GET["kelas"];
                    $gander = $_GET["gander"];
                    $kode_dudi_pilihan = $_GET["kodedudi"];
                }

                if (@$_GET["checkbox"] == "yakin" && @$_GET["akses"] == "pilihtempat") {

                    $sql = "SELECT * FROM datadudi WHERE kode = '$kode_dudi_pilihan'";
                    $query = mysqli_query($konek, $sql);
                    $row = mysqli_fetch_assoc($query);
                    $kuota = $row["kuotatoal"];
                    $kuota_L = $row["kuotacow"];
                    $kuota_P = $row["kuotacew"];
                    $pembimbing = $row["pembimbing"];

                    if ($kuota > 0) {
                        $kuota = $kuota - 1;

                        if ($gander == "L") {
                            if ($kuota_L > 0) {
                                $kuota_L = $kuota_L - 1;
                            } else {
                                echo "<script>alert('Kuota Laki-laki sudah habis!');</script>";
                                echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                            }
                        } else if ($gander == "P") {
                            if ($kuota_P > 0) {
                                $kuota_P = $kuota_P - 1;
                            } else {
                                echo "<script>alert('Kuota Perempuan sudah habis!');</script>";
                                echo "<script>location.href='index.php?akses=login_nis&nis=$nis;</script>";
                            }
                        }

                        $sql = "UPDATE datadudi SET kuotatoal = '$kuota', kuotacow = '$kuota_L', kuotacew = '$kuota_P' WHERE kode = '$kode_dudi_pilihan'";

                        $query = mysqli_query($konek, $sql);

                        $sql = "INSERT INTO duditerisi (namadudi, alamat, kode, nis, namasiswa, kelas, gander, pembimbing) VALUES ('$row[namadudi]', '$row[alamat]', '$row[kode]', '$nis', '$namasiswa', '$kelas', '$gander', '$pembimbing')";

                        $query = mysqli_query($konek, $sql);

                        if ($query) {
                            echo "<script>alert('Berhasil!');</script>";
                            echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                        } else {
                            echo "<script>alert('Gagal!');</script>";
                            echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                        }
                    } else {
                        echo "<script>alert('Gagal! Kuota telah kosong!');</script>";
                        echo "<script>location.href='index.php?akses=login_nis&nis=$nis';</script>";
                    }
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
                            <input type="text" class="form-control" id="namasiswa" name="namasiswa" value="<?= $row["nama"]; ?>" disabled>
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
                        <h4 for="gander" class="form-label text-dark">Pilihan DU/DI Tempat Prakerin</h4>
                        <div id="infocekbox">
                            <p class="text-primary"><i class="fa-solid fa-circle-info"></i>&nbsp;untuk melihat Informasi tempat prakerin, Pilih DUDIKA berikut, kemudian Klik tombol <span disabled="disabled" class="btn btn-info btn-sm border-0" disabled><i class="fa-solid fa-eye"></i>&nbsp;Cek info DUDIKA</span> di bawah ini.</p>
                        </div>

                        <select name="kodedudi" class="form-select" aria-label="Default select example">
                            <option value="">Belum Memilih</option>
                            <?php
                            $kuota_gander_hasil = "";
                            $kuota_gander = "";

                            if (@$row["gander"] == "L") {
                                $kuota_gander = "kuotacow";
                            } else if (@$row["gander"] == "P") {
                                $kuota_gander = "kuotacew";
                            } else {
                                $kuota_gander = "kuotatoal";
                            }

                            $sql = "SELECT * FROM datadudi WHERE $kuota_gander > 0 OR (kuotatoal > 0 AND (kuotacew = 0 AND kuotacow = 0)) ORDER BY namadudi ASC";
                            $query = mysqli_query($konek, $sql);

                            while ($rowdudi = mysqli_fetch_assoc($query)) {
                                $kode = $rowdudi["kode"];

                                if ($kode == $kode_dudi_pilihan) {
                                    $selected = " selected";
                                } else {
                                    $selected = "";
                                }
                            ?>
                                <option value="<?= $kode; ?>" <?= $selected; ?>><?= $rowdudi["namadudi"]; ?> (Kuota: <?= $rowdudi["kuotatoal"]; ?><?php if ($rowdudi["kuotacow"] || $rowdudi["kuotacew"]) { ?>) (L: <?= $rowdudi["kuotacow"]; ?>, P: <?= $rowdudi["kuotacew"]; ?><?php } ?>)</option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- <label for="cekdudi_btn" class="form-label text-info text-center mb-3"><i class="fa-solid fa-circle-question"></i>&nbsp;Klik tombol ini untuk melihat informasi tempat prakerin yang dipilih.</label> -->
                    <div class="d-grid gap-2 d-md-block">
                        <button id="cekdudi_btn" type="submit" class="btn btn-info btn-sm mb-3 border-0" name="akses" value="cektempat"><i class="fa-solid fa-eye fa-beat-fade"></i>&nbsp;&nbsp;Cek info DUDIKA</button>
                    </div>

                    <?php

                    if ((@$_GET["akses"] == "pilihtempat") || @$_GET["akses"] == "cektempat") {
                        $daftardudi = "SELECT * FROM datadudi WHERE kode = '$kode_dudi_pilihan'";
                        $querydudi = mysqli_query($konek, $daftardudi);
                        $rowdudi = mysqli_fetch_assoc($querydudi);

                        // mencari data dudi
                        $telahdaftar = "SELECT * FROM duditerisi WHERE kode LIKE '$kode_dudi_pilihan'";
                        $querytelahdaftar = mysqli_query($konek, $telahdaftar);
                        $jumlahtelahdaftar = mysqli_num_rows($querytelahdaftar);

                        // cek gander siswa
                        if (@$row["gander"] == "L") {
                            $kuota_gander = "kuotacow";
                            $gander = "Laki-laki";
                        } else if (@$row["gander"] == "P") {
                            $kuota_gander = "kuotacew";
                            $gander = "Perempuan";
                        } else {
                            $kuota_gander = "kuotatoal";
                            $gander = "Laki-laki & Perempuan";
                        }

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
                                        <span class="badge text-bg-primary"><i class="fa-solid fa-briefcase"></i>&nbsp;&nbsp;Nama DU/DI : </span><br><?= $rowdudi["namadudi"]; ?> <br>
                                        <span class="badge text-bg-primary"><i class="fa-solid fa-circle-question"></i>&nbsp;&nbsp;Keterangan : </span><br><?= $rowdudi["ket"]; ?> <br>
                                        <span class="badge text-bg-primary"><i class="fa-solid fa-map-location-dot"></i>&nbsp;&nbsp;Alamat : </span><br><?= $rowdudi["alamat"]; ?> <br>
                                        <span class="badge text-bg-light text-primary"><i class="fa-solid fa-location-crosshairs fa-beat"></i></span>&nbsp;<span class="badge text-bg-light text-primary"><i class="fa-solid fa-location-arrow fa-shake"></i></span>&nbsp;<a id="btn_loc_1" href="<?= $rowdudi["map"]; ?>" class="btn btn-light btn-sm border-0" target="_blank"><i class="fa-solid fa-location-dot fa-bounce text-danger"></i>&nbsp;Lihat Lokasi</a><br><br>
                                        <span class="badge text-bg-secondary"><i class="fa-solid fa-house"></i>&nbsp;&nbsp;Info Kos : </span><br><?= $rowdudi["kos"]; ?> <br>
                                        <span class="badge text-bg-secondary"><i class="fa-solid fa-money-bill-trend-up"></i>&nbsp;&nbsp;Biaya Bimbingan : </span><br><?= $rowdudi["beabim"]; ?> <br>
                                        <span class="badge text-bg-secondary"><i class="fa-solid fa-money-bill-wheat"></i>&nbsp;&nbsp;Biaya Hidup : </span><br><?= $rowdudi["beahidup"]; ?> <br><br>
                                        <span class="badge text-bg-dark"><i class="fa-solid fa-user-gear"></i>&nbsp;&nbsp;Pembimbing : </span><br><?= $rowdudi["pembimbing"] ? $rowdudi["pembimbing"] : "-"; ?>

                                        <?php
                                        $msg_nama = str_replace(" ", "%20", $row["nama"]);
                                        $msg_kelas = str_replace(" ", "%20", $row["kelas"]);
                                        $link_wa = "https://api.whatsapp.com/send?phone=" . @$rowdudi["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0ASaya%20ingin%20menanyakan%20tentang%20prakerin,%20di%20" . $rowdudi["namadudi"] . ".%0A";
                                        ?>
                                        <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i class="fa-brands fa-whatsapp fa-beat" style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                                        <br><br>
                                        Sisa kuota : <span class="badge text-bg-danger"><?= $rowdudi["kuotatoal"]; ?></span> <br>

                                        <?php if ($rowdudi["kuotacow"] || $rowdudi["kuotacew"]) { ?>
                                            Kuota Laki-laki (L) : <span class="badge text-bg-primary"><?= $rowdudi["kuotacow"] ? $rowdudi["kuotacow"] : "-"; ?></span><br>
                                            Kuota Perempuan (P) : <span class="badge text-bg-warning"><?= $rowdudi["kuotacew"] ? $rowdudi["kuotacew"] : "-"; ?></span><br>
                                        <?php } ?>

                                        <br>
                                        Jumlah Siswa yang telah terdaftar : <?= $jumlahtelahdaftar ? $jumlahtelahdaftar : '<span class="badge text-bg-success">- Belum ada yang memilih -</span>'; ?><br>
                                        <?php
                                        $no = 0;
                                        while ($rowtelahdaftar = mysqli_fetch_assoc($querytelahdaftar)) {
                                            $no++;
                                        ?>
                                            <?= $no; ?>. <?= $rowtelahdaftar["namasiswa"]; ?> (<?= $rowtelahdaftar["gander"]; ?>) (<?= $rowtelahdaftar["kelas"]; ?>) <br>
                                        <?php } ?>
                                    </p>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div>
                                <h6>Informasi tempat Prakerin</h6>
                                <?php
                                if (@$_GET["cektmbl"] == "pushon") { ?>
                                    <div class="alert alert-danger" role="alert">
                                        Pilih DUDIKA / Tempat Prakerin terlebih dahulu. Baru kemudian klik cek info DUDIKA.<br><br>
                                        Hubungi Admin, jika dirasa ada yang salah.
                                    <?php } else { ?>
                                        <div class="alert alert-warning" role="alert">
                                            Kuota <span class="badge text-bg-warning"><?= $gander; ?></span> untuk tempat ini <?= @$rowdudi["namadudi"] ? "(" . $rowdudi["namadudi"] . ")" : "-"; ?> sudah <b>penuh</b> / tidak ada. Silakan pilih DUDIKA yang lainnya.
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <br>

                            <?php if (@$_GET["akses"] == "cektempat") { ?>
                                <div id="infocekbox" class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="checkbox" value="yakin" id="checkbox">
                                    <label class="form-check-label text-danger" for="checkbox">Yakin? Akan memilih DU/DI ini?</label>

                                    <p>
                                        <span class="text-primary"><i class="fa-solid fa-triangle-exclamation fa-beat-fade text-danger"></i>&nbsp;Centang cek list ini setelah memilih tempat yang diinginkan! Pastikan DU/DI yang dipilih telah sesuai. Jika sudah yakin. Klik Centang, kemudian klik "pilih"</span><br>
                                        <span class="text-success"><i class="fa-solid fa-circle-info text-info fa-beat-fade"></i>&nbsp;Jika belum ingin memilih, jangan dicentang dulu.</span><br>
                                        <span class="text-warning"><i class="fa-solid fa-triangle-exclamation fa-beat-fade"></i>&nbsp;Hanya satu kali pilih!</span>
                                    </p>
                                </div>
                                <div class="d-grid gap-2 d-md-block">
                                    <button type="submit" class="btn btn-success border-0" name="akses" value="pilihtempat" onclick="return confirm(`Akan memilih \'<?= $rowdudi['namadudi']; ?>\' sebagai tempat prakerin.\nYakin?`)"><i class="fa-solid fa-circle-check fa-beat" style="--fa-beat-scale: 1.8; --fa-animation-duration: 2s;"></i>&nbsp;Pilih</button>
                                </div>
                            <?php } ?>
                </form>
                <div class="d-grid gap-2 d-md-block mt-2">
                    <a class="btn border-0" href="/"><i class="fa-solid fa-chevron-left fa-beat"></i>&nbsp;Kembali</a>
                </div>
                <br><br><br>
<?php } else {
                echo "<script>alert('NIS tidak ditemukan!');</script>";
                echo "<script>location.href='index.php';</script>";
            }
        }
        } else {
            echo "ERROR: 404";
        }
?>