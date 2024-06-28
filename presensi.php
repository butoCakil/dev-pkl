<?php
session_start();
$title = "Presensi Prakerin 2023";
$admin = false;

// Cek Device
include_once 'user_agent.php';
//create an instance of UserAgent class
$ua = new UserAgent();

include "views/header.php";
include "views/navbar.php";

// time zone
date_default_timezone_set('Asia/Jakarta');
// tanggal dan jam hari ini
$tanggal = date('Y-m-d');
$jam = date('H:i');
$tgl = date('d');
$thn = date('Y');

// hari bahasa indonesia
$hari = date('l', strtotime($tanggal));
$hari_indonesia = array(
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
);

$hari_indonesia = $hari_indonesia[$hari];

// bulan indonesia
$bulan = date('F', strtotime($tanggal));
$bulan_indonesia = array(
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
);

$bulan_indonesia = $bulan_indonesia[$bulan];


if (@$_POST["nis"] && (@$_POST["akses"] == "presensi" || @$_POST["akses"] == "presensilagi")) {

    $nis = $_POST["nis"];

    if (!ctype_digit($nis)) {
        // NIS bukan angka, kembalikan ke halaman sebelumnya dengan alert
        echo "<script>alert('NIS harus berupa angka.'); window.history.back();</script>";
        exit; // Keluar dari skrip PHP setelah menampilkan alert
    }

    $akses = $_POST["akses"];

    // ambil data siswa dari database
    include "koneksi.php";

    // cek ketersediaan data
    $sql_datasiswa = "SELECT * FROM datasiswa WHERE nis = ?";
    $stmt_datasiswa = mysqli_prepare($konek, $sql_datasiswa);
    mysqli_stmt_bind_param($stmt_datasiswa, "s", $nis);
    mysqli_stmt_execute($stmt_datasiswa);
    $result_datasiswa = mysqli_stmt_get_result($stmt_datasiswa);
    $row = mysqli_fetch_assoc($result_datasiswa);
    $adadata = mysqli_num_rows($result_datasiswa);

    // dpatkan nick sebagai sandi
    $nick = $row["nama"];
    $db_pass = $row["password"];
    $nick_no_spaces = str_replace(' ', '', $nick);
    $nick_lower = strtolower($nick_no_spaces);
    $nick_8_chars = substr($nick_lower, 0, 8);

    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $inputform = isset($_POST['form']) ? $_POST['form'] : '';
    $status_ganti_password = false;

    // Cek apakah password disubmit
    if (isset($password)) {
        // Jika password tidak diisi
        if (empty($password)) {
            echo '<script>alert("Harus masukkan password");</script>';
            echo '<script>window.history.back();</script>';
            exit;
        }

        // Jika password tidak ada di database, cek ke password default
        if (empty($db_pass)) {
            if ($password == $nick_8_chars) {
                $status_ganti_password = true;
                // echo '<script>alert("Password default");</script>';
                // echo "Password benar";
                // Lanjutkan proses sesuai kebutuhan
            } else {
                echo '<script>alert("Password default salah");</script>';
                echo '<script>window.history.back();</script>';
                exit;
            }
        } else {
            // Jika password ada di database, cek dengan md5
            if ($inputform == "form") {
                if ($password == $db_pass) {
                    // Lanjutkan proses sesuai kebutuhan
                    $status_ganti_password = false;
                    // echo '<script>alert("Password Sudah diganti");</script>';
                } else {
                    echo '<script>alert("Password form salah");</script>';
                    echo '<script>window.history.back();</script>';
                    exit;
                }
            } else {
                if (md5($password) == $db_pass) {
                    // Lanjutkan proses sesuai kebutuhan
                    $status_ganti_password = false;
                    // echo '<script>alert("Password Sudah diganti");</script>';
                } else {
                    echo '<script>alert("Password salah");</script>';
                    echo '<script>window.history.back();</script>';
                    exit;
                }
            }
        }
    } else {
        echo "Harus masukkan password";
        // munculkan alert dan kembali ke halaman sebelumnya
        echo '<script>alert("Harus masukkan password");</script>';
        echo '<script>window.history.back();</script>';
        exit;
    }

    // cek dulu di presensi
    $sql_presensi = "SELECT * FROM presensi WHERE nis = ? AND timestamp LIKE ?";
    $stmt_presensi = mysqli_prepare($konek, $sql_presensi);
    mysqli_stmt_bind_param($stmt_presensi, "ss", $nis, $tanggal_param);
    $tanggal_param = "%$tanggal%";
    mysqli_stmt_execute($stmt_presensi);
    $result_presensi = mysqli_stmt_get_result($stmt_presensi);

    // jika sudah ada maka redirect ke halaman preview
    if (mysqli_num_rows($result_presensi) > 0 && @$_POST["akses"] != "presensilagi") {
        echo "<script>
            window.location.href = 'prevpresensi.php?nis=$nis&akses=presensi';
          </script>";
        exit; // menghentikan eksekusi lebih lanjut jika mengarahkan ke halaman preview
    }

    $sql_duditerisi = "SELECT * FROM duditerisi WHERE nis = ?";
    $stmt_duditerisi = mysqli_prepare($konek, $sql_duditerisi);
    mysqli_stmt_bind_param($stmt_duditerisi, "s", $nis);
    mysqli_stmt_execute($stmt_duditerisi);
    $result_duditerisi = mysqli_stmt_get_result($stmt_duditerisi);
    $row2 = mysqli_fetch_assoc($result_duditerisi);
    $kode_dudi = @$row2['kode'];

    $pembimbing_query = "SELECT * FROM datadudi WHERE kode = ?";
    $stmt_pembimbing = mysqli_prepare($konek, $pembimbing_query);
    mysqli_stmt_bind_param($stmt_pembimbing, "s", $kode_dudi);
    mysqli_stmt_execute($stmt_pembimbing);
    $query_pembimbing = mysqli_stmt_get_result($stmt_pembimbing);
    $data_pembimbing = mysqli_fetch_array($query_pembimbing);
    ?>

    <style>
        h1 {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0px;
        }

        .info-alert-presensi {
            text-align: center;
            font-size: 0.8em;
            padding: 5px 0;
            margin: 5px 0;
        }

        .label-datadiri-presensi label {
            width: 100px;
        }

        .label-datadiri-presensi strong {
            margin-left: 50px;
            width: 150px;
        }

        .form-presensi .input-form-presensi {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }

        .form-presensi .input-form-presensi input,
        .form-presensi select,
        .form-presensi textarea {
            width: 80%;
        }

        .form-presensi .tombol-upload-presensi .btn {
            width: 23%;
        }

        .form-presensi .tombol-upload-presensi {
            /* geser tombol button ke tengah */
            display: flex;
            justify-content: space-between;
            margin-top: 20px;

        }

        #tombolfile {
            margin-top: 20px;
            margin-bottom: -20px;
        }

        .preview {
            padding: 10px;
            border: 1px solid #ccc;
            width: 0 auto;
        }

        .preview img {
            height: 100px;
            width: 100px;
            object-fit: contain;
            margin: 0 auto;
            /* object-position: center; */
        }

        .preview #preview {
            margin: 0 auto;
        }

        #dvPreview {
            display: none;
        }

        @media screen and (max-width: 900px) {
            .form-presensi .tombol-upload-presensi .btn {
                margin-top: 15px;
                width: auto;
            }

            .form-presensi .tombol-upload-presensi {
                display: flex;
                flex-direction: column;
                margin: auto 20%;
            }

            .form-presensi {
                /* form ke tengah */
                margin-top: 50px;
                margin-left: auto;
                margin-right: auto;
                width: 80%;
            }

            #tombolfile {
                margin-top: 0px;
                margin-bottom: 0px;
            }

            #tombolkamera,
            #tombolfile {
                margin-left: 25px;
                margin-bottom: -20px;
            }

            .form-presensi textarea {
                width: 100%;
            }

            .form-presensi .label-datadiri-presensi label {
                width: 80px;
                font-size: 12px;
                margin-right: -20px;
            }

            .form-presensi .label-datadiri-presensi strong {
                margin-left: 40px;
                width: 150px;
                font-size: 12px;
            }
        }

        /*  */
        .tanggalindo {
            position: relative;
            width: 150px;
            height: 40px;
            color: white;
        }

        .tanggalindo:before {
            position: absolute;
            /* top: 5px; */
            /* left: 5px; */
            content: attr(data-date);
            display: inline-block;
            color: black;
        }

        .tanggalindo::-webkit-datetime-edit,
        .tanggalindo::-webkit-inner-spin-button,
        .tanggalindo::-webkit-clear-button {
            display: none;
        }

        .tanggalindo::-webkit-calendar-picker-indicator {
            position: absolute;
            /* top: 5px; */
            right: 0;
            color: black;
            opacity: 1;
        }

        /*  */
    </style>

    <style>
        .custom-file-input::-webkit-file-upload-button {
            visibility: hidden;
        }

        .custom-file-input-file::before {
            content: "Pilih Beberapa File";
        }

        .custom-file-input-kamera::before {
            content: "📷 Ambil Foto";
        }

        .custom-file-input::before {
            /* content: 'Pilih Beberapa File'; */
            display: inline-block;
            background: -webkit-linear-gradient(top, #f9f9f9, #e3e3e3);
            border: 1px solid #999;
            border-radius: 3px;
            padding: 5px 8px;
            outline: none;
            white-space: nowrap;
            -webkit-user-select: none;
            cursor: pointer;
            text-shadow: 1px 1px #fff;
            font-weight: 700;
            font-size: 10pt;
        }

        .custom-file-input:hover::before {
            border-color: black;
        }

        .custom-file-input:active::before {
            background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
        }
    </style>

    <div class="container">
        <?php if ($adadata) {
            if ($akses != "presensilagi") { ?>
                <h1>Presensi Prakerin</h1>
            <?php } else { ?>
                <h1>Pengisian Jurnal Harian</h1>
            <?php } ?>

            <?php if ($status_ganti_password == true) { ?>
                <div class="alert alert-primary mb-3 text-center" role="alert">
                    Segera Ganti password<br>
                    <button class="btn btn-sm btn-primary border-0" data-bs-toggle="modal"
                        data-bs-target="#modalGantiPassword">Ganti
                        Password</button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modalGantiPassword" tabindex="-1" aria-labelledby="modalGantiPasswordLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalGantiPasswordLabel">Ganti Password</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Form untuk mengganti password -->
                                <form action="app/proses_ganti_password.php" method="POST" onsubmit="return validateForm()">
                                    <input type="hidden" name=nis value="<?= $nis; ?>">
                                    <input type="hidden" name=password value="<?= $password ?>">
                                    <input type="hidden" name=token value="<?= $password . $nis; ?>">
                                    <div class="mb-3">
                                        <label for="inputPasswordBaru" class="form-label">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="inputPasswordBaru" name="new_password"
                                                required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePasswordBaru">
                                                <i class="fa fa-eye-slash"></i>
                                            </button>
                                        </div>
                                        <div id="passwordLengthError" class="text-danger" style="display: none;">Minimal 8 karakter.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPasswordBaru" class="form-label">Konfirmasi Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirmPasswordBaru"
                                                name="confirm_password" required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPasswordBaru">
                                                <i class="fa fa-eye-slash"></i>
                                            </button>
                                        </div>
                                        <div id="passwordError" class="text-danger" style="display: none;">Password tidak sesuai.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>

                                <!-- Script untuk toggle tampilan password -->
                                <script>
                                    // Function untuk toggle tampilan password
                                    function togglePasswordVisibility(inputId, buttonId) {
                                        var passwordInput = document.getElementById(inputId);
                                        var toggleButton = document.getElementById(buttonId);

                                        if (passwordInput.type === "password") {
                                            passwordInput.type = "text";
                                            toggleButton.innerHTML = '<i class="fa fa-eye"></i>';
                                        } else {
                                            passwordInput.type = "password";
                                            toggleButton.innerHTML = '<i class="fa fa-eye-slash"></i>';
                                        }
                                    }

                                    // Event listener untuk toggle password baru
                                    document.getElementById('togglePasswordBaru').addEventListener('click', function () {
                                        togglePasswordVisibility('inputPasswordBaru', 'togglePasswordBaru');
                                    });

                                    // Event listener untuk toggle konfirmasi password baru
                                    document.getElementById('toggleConfirmPasswordBaru').addEventListener('click', function () {
                                        togglePasswordVisibility('confirmPasswordBaru', 'toggleConfirmPasswordBaru');
                                    });

                                    // Validasi form sebelum submit
                                    function validateForm() {
                                        var newPassword = document.getElementById('inputPasswordBaru').value;
                                        var confirmPasswordBaru = document.getElementById('confirmPasswordBaru').value;
                                        var passwordError = document.getElementById('passwordError');
                                        var passwordLengthError = document.getElementById('passwordLengthError');

                                        // Check if password meets minimum length requirement
                                        if (newPassword.length < 8) {
                                            passwordLengthError.style.display = 'block';
                                            passwordError.style.display = 'none'; // Hide mismatch error if length is insufficient
                                            return false;
                                        } else {
                                            passwordLengthError.style.display = 'none';

                                            // Check if passwords match
                                            if (newPassword !== confirmPasswordBaru) {
                                                passwordError.style.display = 'block';
                                                return false;
                                            } else {
                                                passwordError.style.display = 'none';
                                                return true;
                                            }
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- <div class="col form-presensi"> -->
            <style>
                .forminfo {
                    font-size: 14px;
                }
            </style>
            <div class="">
                <form action="_presensi.php" method="post" class="forminfo" enctype="multipart/form-data">
                    <?php if ($akses != "presensilagi") { ?>
                        <div class="alert alert-secondary">
                            <p>
                            <div class="label-datadiri-presensi">
                                <label>NIS</label>
                                <strong>:&nbsp;<?= $row['nis']; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label for="">Nama</label>
                                <strong>:&nbsp;<?= $row['nama']; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label for="">Kelas</label>
                                <strong>:&nbsp;<?= $row['kelas']; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label for="">Jurusan</label>
                                <strong>:&nbsp;<?= $row['jur']; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label for="">Nama DUDIKA</label>
                                <strong>:&nbsp;<?= isset($row2['namadudi']) ? $row2['namadudi'] : "-Belum ada Data-"; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label for="">Hari, tanggal</label>
                                <strong>:&nbsp;<?= $hari_indonesia . ", " . $tgl . " " . $bulan_indonesia . " " . $thn; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label>Waktu</label>
                                <strong>:&nbsp;<?= $jam; ?></strong><br>
                            </div>
                            </p>
                        </div>

                        <style>
                            .alert-info-tombol-kamera {
                                font-size: 12px;
                                text-align: center;
                            }
                        </style>

                        <div class="alert-info-tombol-kamera alert alert-warning">
                            <p>
                                <i class="fas fa-circle-info fa-bounce"></i>
                                Foto yang diupload adalah foto selfie berlatar belakang tempat Prakerin / kegiatan selama
                                Prakerin.
                            </p>
                        </div>
                    <?php } ?>

                    <input type="hidden" name="kodedudika" value="<?= $row2['kode']; ?>">
                    <input type="hidden" name="nis" value="<?= $row['nis']; ?>">
                    <input type="hidden" name="nama" value="<?= $row['nama']; ?>">
                    <input type="hidden" name="namadudi" value="<?= $row2['namadudi']; ?>">
                    <input type="hidden" name="kelas" value="<?= $row['kelas']; ?>">

                    <div class="form-group input-form-presensi">
                        <label for="foto">Foto
                            <span class="text-danger">*</span>
                        </label>
                        <!-- <input type="file" class="form-control" id="foto" name="foto[]" multiple> -->

                        <div>
                            <?php if ($ua->is_mobile()) { ?>
                                <div id="tombolkamera">
                                    <label class="custom-file-input custom-file-input-kamera" for="UploadCam"></label>
                                    <input id="UploadCam" type="file" name="_photos" accept="image/*" style="visibility: hidden"
                                        onchange="showPreview(event);" capture="environment" required
                                        oninvalid="alert('Ambil Foto Kamera terlebih dahulu!');">
                                </div>
                            <?php } else { ?>
                                <div id="tombolfile">
                                    <label class="custom-file-input custom-file-input-file" for="fileupload"></label>
                                    <!--<input id="fileupload" type="button" multiple="multiple" name="file_photos[]" accept="image/*" style="visibility: hidden" required oninvalid="alert('Pilih Beberapa file foto terlebih dahulu!');">-->
                                    <button id="fileupload" type="button" multiple="multiple" name="file_photos[]"
                                        style="visibility: hidden" required
                                        onclick="alert('Gunakan SmartPhone untuk mengambil Foto / Non-Aktifkan mode Dekstop');" />
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group preview">
                        <img id="dvPreview">
                        <div id="preview"></div>
                    </div>

                    <?php if ($akses != "presensilagi") { ?>
                        <!-- input select keterangan -->
                        <div class="form-group input-form-presensi mb-3 mt-3">
                            <label for="keterangan">Keterangan (Pilih)
                                <span class="text-danger">*</span></label>
                            <select class="form-control" id="keterangan" name="keterangan" required>
                                <!--<option value="">Pilih Keterangan</option>-->
                                <option value="Masuk">Masuk</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Tidak_Masuk">Tidak Masuk / Libur</option>
                            </select>
                        </div>
                        <!-- input text area -->
                        <div class="form-group input-form-presensi mb-3">
                            <label for="catatanjurnal">Catatan / Jurnal
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="catatanjurnal" name="catatanjurnal" rows="4"
                                placeholder="Isikan juga Agenda/rencana/kegiatan hari ini di tempat prakerin (PKL)."
                                required></textarea>
                        </div>
                    <?php } ?>

                    <?php
                    // Persiapkan kueri SQL dengan prepared statement
                    $sqlcek = "SELECT COUNT(*) FROM presensi WHERE nis = ? AND kode = ? AND timestamp LIKE ?";
                    $stmt = $konek->prepare($sqlcek);

                    if ($stmt) {
                        // Bind parameter ke prepared statement
                        $nis_param = $nis;
                        $kode_param = isset($row2['kode']) ? $row2['kode'] : "";
                        $tanggal_param = "%$tanggal%";
                        $stmt->bind_param("sss", $nis_param, $kode_param, $tanggal_param);

                        // Lakukan eksekusi prepared statement
                        $stmt->execute();

                        // Ambil hasil dari prepared statement
                        $stmt->bind_result($rowcek);
                        $stmt->fetch();

                        // Gunakan $rowcek untuk keperluan selanjutnya (jumlah baris yang ditemukan)
            
                        // Bebaskan prepared statement
                        $stmt->close();
                    } else {
                        // Handle kesalahan jika persiapan kueri gagal
                        echo "Error: " . $konek->error;
                    }

                    if ($rowcek > 0) {
                        ?>
                        <!-- input text area -->
                        <div class="form-group input-form-presensi mt-3 mb-3">
                            <label for="catatanjurnal">Catatan / Jurnal</label>
                            <textarea class="form-control" id="catatanjurnal" name="catatanjurnal" rows="4"
                                placeholder="Isikan catatan/jurnal kegiatan hari ini di tempat prakerin sesuai foto."></textarea>
                        </div>
                    <?php } ?>

                    <input type="hidden" name="nowapemb" value="<?= $data_pembimbing["nowa"]; ?>">
                    <input type="hidden" name="tok" value="qnNDCgY0F4F3MVna2LoF">

                    <div class="tombol-upload-presensi">
                        <?php
                        if (isset($row2['kode'])) {
                            ?>
                            <button type="submit" class="btn btn-primary btn-sm border-0">
                                <!-- <i class="fas fa-upload fa-bounce"></i>&nbsp; -->
                                <i class="fa-solid fa-cloud-arrow-up fa-beat-fade"></i> Upload
                            </button>
                            <?php
                        } else {
                            ?>
                            <button onclick="alert('Belum bisa presensi, Dudi belum terhubung.');"
                                class="btn btn-primary btn-sm border-0">
                                <i class="fa-solid fa-cloud-arrow-up fa-beat-fade"></i> Upload
                            </button>
                            <?php
                        }
                        ?>
                        <a href="index.php" class="btn btn-dark btn-sm border-0">
                            <!-- <i class="fas fa-times fa-beat-fade"></i> -->
                            <i class="fa-solid fa-xmark"></i> Batal
                        </a>
                        <a href="rekap.php?nis=<?= $row['nis']; ?>&akses=rekapabsen" class="btn btn-success btn-sm border-0">
                            <i class="fas fa-file-alt"></i> Rekap&nbsp;Saya
                        </a>
                    </div>

                    <?php
                    if (isset($row2['kode'])) {
                        ?>
                    </form>
                    <div class="input-group input-group-sm mt-3 mb-5">
                        <span class="input-group-text bg-dark text-light gradient">Pembimbing </span>
                        <input type="text" class="form-control" value="<?= @$data_pembimbing["pembimbing"]; ?>" disabled>
                        <?php
                        $msg_nama = str_replace(" ", "%20", isset($row['nama'])) ? $row['nama'] : '';
                        $msg_kelas = str_replace(" ", "%20", isset($row['kelas'])) ? $row['kelas'] : '';
                        $dudika_ = str_replace(" ", "%20", isset($row2['namadudi'])) ? $row2['namadudi'] : '';
                        $link_wa = "https://api.whatsapp.com/send?phone=" . isset($data_pembimbing["nowa"]) ? @$data_pembimbing["nowa"] : '' . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0Atempat%20prakerin:%20" . $dudika_ . "%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20..";
                        ?>
                        <span class="input-group-text bg-success btn-success">
                            <a href="<?= $link_wa; ?>" class="text-light"><i class="fa-brands fa-whatsapp fa-beat"
                                    style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i>
                            </a>
                        </span>
                    </div>

                <?php } ?>

                <div class="info-alert-presensi alert alert-info alert-dismissible fade show pb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <p class="p-2 mb-0">
                        <strong>Bantuan (?)</strong><br>
                        Jika ada kendala saat presensi prakerin,<br>silakan hubungi Pak Benny<br>(Sertakan Screenshoot)

                        <?php
                        $msg_nama = str_replace(" ", "%20", @$row['nama']);
                        $msg_kelas = str_replace(" ", "%20", @$row['kelas']);
                        $dudika_ = str_replace(" ", "%20", @$row2['namadudi']);
                        $link_wa = "https://api.whatsapp.com/send?phone=6282241863393&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0Atempat%20prakerin:%20" . $dudika_ . "%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20melaporkan%20kendala%20dalam%20presensi%0AKendalanya: ...";
                        ?>
                    </p>
                    <a href="<?= $link_wa; ?>"
                        class="bg-success btn-success text-light p-1 m-0 rounded text-decoration-none">Chat WA <i
                            class="fa-brands fa-whatsapp" style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                </div>
            </div>

        <?php } else { ?>
            <h1>Tidak ada data</h1>
            <a href="index.php" class="btn btn-dark btn-sm border-0">
                <i class="fa-solid fa-arrow-left"></i>&nbsp;Kembali
            </a>
        <?php } ?>
    </div>

    <div class="container"></div>

    <script>
        // preview image kamera
        function showPreview(event) {
            if (event.target.files.length > 0) {
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("dvPreview");
                preview.src = src;
                preview.style.display = "block";
            }
        }

        // preview image file (multiple)
        function previewImages() {

            var preview = document.querySelector('#preview');

            if (this.files) {
                [].forEach.call(this.files, readAndPreview);
            }

            function readAndPreview(file) {

                // Make sure `file.name` matches our extensions criteria
                if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
                    return alert(file.name + " is not an image");
                } // else...

                var reader = new FileReader();

                reader.addEventListener("load", function () {
                    var image = new Image();
                    image.height = 100;
                    image.title = file.name;
                    image.src = this.result;
                    preview.appendChild(image);
                });

                reader.readAsDataURL(file);
            }
        }

        document.querySelector('#fileupload').addEventListener("change", previewImages);
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

    <?php
    mysqli_close($konek);
} else {
    ?>
    <div class="container">
        <!-- tombol kembali javascript -->
        <script>
            function kembali() {
                history.back(-1);
            }

            // setTimeout(kembali, 1000);
        </script>

        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Akses ditolak!</h4>
            <p>Anda tidak memiliki akses untuk mengakses halaman ini.</p>
            <hr>
            <p class="mb-0">
                <button type="button" class="btn btn-primary border-0" onClick="kembali()">Kembali</button>
            </p>
        </div>
    </div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
    crossorigin="anonymous"></script>
<?php include "views/footer.php" ?>