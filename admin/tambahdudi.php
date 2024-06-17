<?php
session_start();

if (@$_SESSION["admin"]) {
    if (@$_SESSION["admin"] == "admin") {

    if (@$_POST["tambahdudi"] == "tambahdudi") {
        include "../koneksi.php";
        // alert
        // echo "<script>
        // alert('Data Sudah Tersimpan');
        // </script>";

        $id_pembimbing_dudi = @$_POST["pembimbing"];

        $sql_pembimbing = mysqli_query($konek, "SELECT * FROM datapembimbing WHERE id='$id_pembimbing_dudi'");
        $data_pembimbing = mysqli_fetch_array($sql_pembimbing);
        $pembimbing_dudi = $data_pembimbing["nama"];
        $nomor_pemb = $data_pembimbing["cp"];

        // tambah data datadudi
        $status_dudi = @$_POST["status"];
        $jur_dudi = @$_POST["jur"];
        $nama_dudi = @$_POST["nama"];
        $alamat_dudi = @$_POST["alamat"];
        $kota_dudi = @$_POST["kota"];
        $kuota_total = @$_POST["kuota"];
        $kuota_L = @$_POST["kuota_L"];
        $kuota_P = @$_POST["kuota_P"];
        $keterangan_dudi = @$_POST["keterangan"];
        $lokasi_dudi = @$_POST["lokasi"];
        $info_kos = @$_POST["kos"];
        $info_bea_bim = @$_POST["beabim"];
        $info_bea_hidup = @$_POST["beahidup"];

        // menghilangkan spasi di nama dudi
        $namadudi_temp = str_replace(" ", "", $nama_dudi);
        // jagikan huruf kapital
        $namadudi_temp = strtoupper($namadudi_temp);
        // mengambil 6 karakter huruger dari nama dudi
        $kode = substr($namadudi_temp, 0, 6);
        // random angka 4 digit
        $i = rand(1000, 9999);
        $kode_dudi = $kode . ($i + 1);

        // tambah data datadudi
        $sql = "INSERT INTO datadudi (namadudi, alamat, kota, kuotacow, kuotacew, kuotatoal, kode, pembimbing, nowa, kos, beabim, beahidup, ket, map, jur, status) VALUES ('$nama_dudi', '$alamat_dudi', '$kota_dudi', '$kuota_L', '$kuota_P', '$kuota_total', '$kode_dudi', '$pembimbing_dudi', '$nomor_pemb', '$info_kos', '$info_bea_bim', '$info_bea_hidup', '$keterangan_dudi', '$lokasi_dudi', '$jur_dudi', '$status_dudi')";
        $result = mysqli_query($konek, $sql);

        if ($result) {
            $_SESSION["ok"] = 'Data Sudah ' . $nama_dudi . ' Tersimpan';

            // hapus POST
            unset($_POST);
            $_POST = "";

            // redirect
            if ($status_dudi && $jur_dudi) {
                echo "<script>window.location.href='ubahdudi.php';</script>";
            } elseif (!$status_dudi) {
                $_SESSION["error"] = 'Status Dudika Belum Dipilih';
            } elseif (!$jur_dudi) {
                $_SESSION["error"] = 'Jurusan Dudika Belum Dipilih';
            } else {
                $_SESSION["error"] = 'Status Dudika dan Jurusan Belum Dipilih';
            }
        } else {
            $_SESSION["error"] = "Data Gagal Tersimpan" . mysqli_error($konek);
        }
    }

    if (@$_GET["edit"] == "dudi") {

        $s = '<i class="fas fa-edit fa-flip text-danger"></i>&nbsp;&nbsp;';
        $judul_hal = $s . "Ubah";

        include "../koneksi.php";
        $kode = @$_GET["kode"];
        $sql = "SELECT * FROM datadudi WHERE kode = '$kode'";
        $result = mysqli_query($konek, $sql);

        if ($result) {

            $row = mysqli_fetch_assoc($result);
            $status_dudi = $row["status"];
            $jur_dudi = $row["jur"];
            $nama_dudi = @$row["namadudi"];
            $alamat_dudi = @$row["alamat"];
            $kota_dudi = @$row["kota"];
            $pembimbing_dudi = @$row["pembimbing"];
            $nomor_pemb = @$row["nowa"];
            $kuota_total = @$row["kuotatoal"];
            $kuota_L = @$row["kuotacow"];
            $kuota_P = @$row["kuotacew"];
            $keterangan_dudi = @$row["ket"];
            $lokasi_dudi = @$row["map"];
            $info_kos = @$row["kos"];
            $info_bea_bim = @$row["beabim"];
            $info_bea_hidup = @$row["beahidup"];
        } else {
            $_SESSION["error"] = "Data Gagal Ditampilkan" . mysqli_error($konek);
        }
    }

    if (@$_POST["edit"] == "ubahdudi") {

        include "../koneksi.php";

        $id_pembimbing_dudi = @$_POST["pembimbing"];
        $id_pembimbing_dudi_lama = @$_POST["pembimbing_lama"];

        $sql_pembimbing = mysqli_query($konek, "SELECT * FROM datapembimbing WHERE id='$id_pembimbing_dudi'");
        $data_pembimbing = mysqli_fetch_array($sql_pembimbing);
        $pembimbing_dudi = @$data_pembimbing["nama"] ? $data_pembimbing["nama"] : $id_pembimbing_dudi_lama;
        $nomor_pemb = $data_pembimbing["cp"];

        $status_dudi = @$_POST["status"];
        $jur_dudi = @$_POST["jur"];
        $nama_dudi = @$_POST["nama"];
        $alamat_dudi = @$_POST["alamat"];
        $kota_dudi = @$_POST["kota"];
        $kuota_total = @$_POST["kuota"];
        $kuota_L = @$_POST["kuota_L"];
        $kuota_P = @$_POST["kuota_P"];
        $keterangan_dudi = @$_POST["keterangan"];
        $lokasi_dudi = @$_POST["lokasi"];
        $info_kos = @$_POST["kos"];
        $info_bea_bim = @$_POST["beabim"];
        $info_bea_hidup = @$_POST["beahidup"];

        $kode = @$_POST["kode"];

        // update database 
        $sql = "UPDATE datadudi SET namadudi = '$nama_dudi', alamat = '$alamat_dudi', kota = '$kota_dudi', pembimbing = '$pembimbing_dudi', nowa = '$nomor_pemb', kuotacow = '$kuota_L', kuotacew = '$kuota_P', kuotatoal = '$kuota_total', ket = '$keterangan_dudi', map = '$lokasi_dudi', kos = '$info_kos', beabim = '$info_bea_bim', beahidup = '$info_bea_hidup', jur = '$jur_dudi', status = '$status_dudi' WHERE kode = '$kode'";
        $result = mysqli_query($konek, $sql);

        // update database dudi terisi
        $sql = "UPDATE duditerisi SET namadudi = '$nama_dudi', alamat = '$alamat_dudi', pembimbing = '$pembimbing_dudi' WHERE kode = '$kode'";
        $result = mysqli_query($konek, $sql);
        
        if ($result) {
            $_SESSION["ok"] = "Data Sudah Terubah";

            // hapus POST
            unset($_POST);
            $_POST = "";

            // redirect
            echo "<script>window.location.href='ubahdudi.php';</script>";
        } else {
            $_SESSION["error"] = "Data Gagal Terubah" . mysqli_error($konek);
        }
    }


    $title = "Tambah DUDIKA";
    $admin = true;
    include "../views/header.php";
    include "../views/navbar.php";

?>

    <style>
        h4 {
            text-align: center;
            margin-bottom: 10px;
        }

        #form_tambahdudi .form-group {
            display: flex;
            margin-top: 20px;
        }

        #form_tambahdudi .form-group label {
            width: 30%;
        }

        #form_tambahdudi .form-group input {
            width: 70%;
        }
    </style>

    <div class="container mb-5">

        <?php if (@$_SESSION["error"]) {
            $pesan = $_SESSION["error"];
            unset($_SESSION["error"]);
        ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $pesan; ?>
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <?php if (@$_SESSION["ok"]) {
            $pesan = $_SESSION["ok"];
            unset($_SESSION["ok"]);
        ?>
            <div class="alert alert-success text-center" role="alert">
                <?= $pesan; ?>
                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <div class="mx-5">
            <h4><?= @$judul_hal ? $judul_hal : '<i class="fa-solid fa-circle-plus text-success fa-beat"></i>&nbsp;&nbsp;Tambah' ?> DUDIKA</h4>
            <!-- form tambah dudi -->
            <form id="form_tambahdudi" method="POST">
                <!-- alert -->
                <div class="alert alert-info text-center" role="alert">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    <!-- <strong>Perhatian!</strong> -->
                    <!-- <p>Pastikan data yang anda masukkan sudah benar</p> -->
                    <label><span class="text-danger">*</span>&nbsp;<i class="text-secondary">Wajib diisi!</i></label>
                </div>
                <div class="form-group">
                    <label for="nama">Nama DUDIKA<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama" name="nama" required placeholder="Nama DUDIKA" value="<?= @$nama_dudi; ?>" autofocus>
                </div>

                <!-- select pilih status dudi -->
                <div class="form-group">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <div class="d-flex">
                        <select class="form-control" id="status" name="status" required>
                            <option value="">-- Pilih Status DUDIKA --</option>

                            <?php
                            if ($status_dudi == "semua") {
                                $selected1 = "selected";
                                $selected2 = "";
                                $selected3 = "";
                                $selected0 = "";
                            } elseif ($status_dudi == "prakerin") {
                                $selected1 = "";
                                $selected2 = "selected";
                                $selected3 = "";
                                $selected0 = "";
                            } elseif ($status_dudi == "magang") {
                                $selected1 = "";
                                $selected2 = "";
                                $selected3 = "selected";
                                $selected0 = "";
                            } elseif ($status_dudi == "hidden") {
                                $selected1 = "";
                                $selected2 = "";
                                $selected3 = "";
                                $selected0 = "selected";
                            } else {
                                $selected1 = "";
                                $selected2 = "";
                                $selected3 = "";
                                $selected0 = "";
                            }
                            ?>
                            <option value="prakerin" <?= $selected2; ?>>Prakerin</option>
                            <option value="magang" <?= $selected3; ?>>Magang</option>
                            <option value="semua" <?= $selected1; ?>>Tampilkan di Semua List</option>
                            <option value="hidden" <?= $selected0; ?>>Sembunyikan dari List</option>
                        </select>
                    </div>
                </div>

                <!-- select pilih jurusan dudi -->
                <div class="form-group">
                    <label for="jur">Jurusan<span class="text-danger">*</span></label>
                    <div class="d-flex">
                        <select class="form-control" id="jur" name="jur" required>
                            <option value="">-- Pilih Jurusan DUDIKA --</option>
                            <?php
                            if ($jur_dudi) {
                                $ssqqjur = "SELECT * FROM infojurusan";
                                $resultjur = mysqli_query($konek, $ssqqjur);
                                while ($rowjur = mysqli_fetch_assoc($resultjur)) {
                                    if ($jur_dudi == $rowjur["kelas"]) {
                                        $selected = "selected";
                                    } else {
                                        $selected = "";
                                    }
                                    echo '<option value="' . $rowjur["kelas"] . '"' . $selected . '>' . $rowjur["jurusan"] . '&nbsp;[' . $rowjur["kelas"] . ']</option>';
                                }
                            } else {
                            ?>
                                <option value="AT">Agribisnis Tanaman&nbsp;[AT]</option> -->
                                <option value="DKV">Design Komunikasi Visual&nbsp;[DKV]</option>
                                <option value="TE">Teknik Elektronika&nbsp;[TE]</option>
                                <option value="ALL">-- Semua Jurusan --</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat DUDIKA</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" required placeholder="Alamat DUDIKA" value="<?= @$alamat_dudi ? $alamat_dudi : "-"; ?>">
                </div>
                <div class="form-group">
                    <label for="kota">Kota/Kabupaten<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="kota" name="kota" required placeholder="kota/kab DUDIKA" value="<?= @$kota_dudi; ?>">
                </div>

                <div class="form-group">
                    <label for="kuota">Kuota Total</label>
                    <div class="col-2">
                        <input type="number" class="form-control" id="kuota" name="kuota" required placeholder="Kuota DUDIKA" value="<?= @$kuota_total ? $kuota_total : 0; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="kuota_L">Kuota Laki-laki</label>
                    <div class="col-2">
                        <input type="number" class="form-control" id="kuota_L" name="kuota_L" required placeholder="L" value="<?= @$kuota_L ? $kuota_L : 0; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="kuota_P">Kuota Perempuan</label>
                    <div class="col-2">
                        <input type="number" class="form-control" id="kuota_P" name="kuota_P" required placeholder="P" value="<?= @$kuota_P ? $kuota_P : 0; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= @$keterangan_dudi ? $keterangan_dudi : '-'; ?>" required placeholder="(Services, Sounds, Home App, dlsb)">
                </div>
                <div class="form-group">
                    <label for="lokasi">Lokasi Maps</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= @$lokasi_dudi ? $lokasi_dudi : "Belum ada lokasi maps"; ?>" required placeholder="Link lokasi di GMap">
                </div>
                <div class="form-group">
                    <label for="kos">Kos</label>
                    <input type="text" class="form-control" id="kos" name="kos" value="<?= @$info_kos ? $info_kos : "-"; ?>" required placeholder="Info Kos">
                </div>
                <div class="form-group">
                    <label for="beabim">Biaya Bimbingan</label>
                    <input type="text" class="form-control" id="beabim" name="beabim" value="<?= @$info_bea_bim ? $info_bea_bim : "-"; ?>" required placeholder="Info Biaya Bimbingan ke DUDIKA">
                </div>
                <div class="form-group">
                    <label for="beahidup">Biaya Hidup</label>
                    <input type="text" class="form-control" id="beahidup" name="beahidup" value="<?= @$info_bea_hidup ? $info_bea_hidup : "-"; ?>" required placeholder="Info Biaya Hidup selama Prakerin">
                </div>

                <input type="hidden" name="pembimbing_lama" value="<?= @$pembimbing_dudi; ?>">
                <?php
                include "../koneksi.php";
                $sql = "SELECT * FROM datapembimbing";
                $result = mysqli_query($konek, $sql);
                ?>

                <div class="form-group mb-3">
                    <label for="pembimbing">Pembimbing</label>
                    <div class="d-flex">
                        <select class="form-control form-select" id="pembimbing" name="pembimbing" required placeholder="Pilih Pembimbing">
                            <option value="<?= @$pembimbing_dudi ? @$pembimbing_dudi : "-"; ?>"><?= @$pembimbing_dudi ? @$pembimbing_dudi : "Pilih Pembimbing"; ?></option>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <option value="<?= $row["id"]; ?>">[<?= $row["jur"]; ?>]&nbsp;<?= $row["nama"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="float-end mb-3">

                    <?php if (@$_GET["edit"] == "dudi") { ?>
                        <input type="hidden" name="kode" value="<?= @$kode; ?>">
                        <button type="submit" class="btn btn-warning mx-3 border-0" name="edit" value="ubahdudi">
                            <!-- <i class="fas fa-save fa-beat"></i>&nbsp; -->
                            <?= @$judul_hal ? $judul_hal : "Simpan" ?>
                        </button>
                        <a href="ubahdudi.php" class="btn btn-dark border-0">
                            <!-- cancel icon -->
                            <i class="fas fa-times fa-fade"></i>&nbsp;
                            Batal
                        </a>
                    <?php } else { ?>
                        <button type="submit" class="btn btn-success mx-3 border-0" name="tambahdudi" value="tambahdudi">
                            <i class="fas fa-save fa-beat"></i>&nbsp;
                            Simpan
                        </button>
                        <a href="../admin/" class="btn btn-dark border-0">
                            <!-- cancel icon -->
                            <i class="fas fa-times fa-fade"></i>&nbsp;
                            Batal
                        </a>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <div class="container mb-5"></div>

    <!--  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <!-- import jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Bootstrap Bundle with Popper -->
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>-->

<?php
    include "../views/footer.php";
    } else {
            echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../admin';
        </script>";
    }
} else {
    // alert 
    echo "<script>
            alert('Anda tidak memiliki akses ke halaman ini!');
            window.location.href='../';
        </script>";

    // header("location: ../");
} ?>