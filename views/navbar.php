<style>
    .nganan {
        position: absolute;
        right: 0;
        margin-right: 60px;
    }

    .nganan2 {
        position: absolute;
        right: 0;
        margin-right: 160px;
    }

    .logo_size_3 {
        margin-top: auto;
        height: 30px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: auto;
    }

    .logo_4 {
        margin-top: 10px;
        height: 70px;
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 20px;
    }
</style>

<?php if (@$admin == false) {
    // session_start();
    $relogin = "";
} else {
    $relogin = "../";
} ?>

<nav class="navbar navbar-expand-lg bg-light">

    <a class="navbar-brand mx-3" href="/">
        <?php if (@$admin == false) { ?>
            <img src="SMKNBansari.png" class="logo_2">
        <?php } else { ?>
            <img src="../SMKNBansari.png" class="logo_2">
        <?php } ?>

    </a>
    <a class='fullscreen mx-3' href='#' title='Layar Penuh'>
        <i class="fa-solid fa-maximize fa-beat"></i>
    </a>
    <a class='fullscreenExit mx-3' href='#' style='display:none;' title='Keluar Layar Penuh'>
        <i class="fa-solid fa-minimize fa-beat"></i>
    </a>

    &nbsp;&nbsp;
    <a href="/">
        <i class="fa-solid fa-house text-dark"></i>
    </a>

    <?php if (@$dudika == false) { ?>
        <?php if (@$_SESSION["admin"]) { ?>
            <?php if (@$admin == false) { ?>
                <a href="admin/logout.php" class="btn btn-sm btn-danger nganan border-0 text-light">
                <?php } else { ?>
                    <a href="logout.php" class="btn btn-sm btn-danger nganan border-0 text-light">
                    <?php } ?>
                    <!-- icon log out -->
                    <i class="fas fa-sign-out-alt fa-beat-fade"></i>&nbsp;
                    Logout
                </a>

                <?php if (@$admin == false) {
                    $link = "admin/";
                } else {
                    $link = "";
                    if (@$app = true) {
                        $link = "../admin/";
                    }
                } ?>

                <style>
                    .dropdown-admin {
                        position: absolute;
                        right: 0px;
                        margin-right: 160px;
                    }
                </style>
                <!---->
                <div class="dropdown dropdown-admin">
                    <button class="btn btn-warning btn-sm dropdown-toggle border-0" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-user fa-shake"></i>&nbsp;
                        Menu Admin
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?= $link; ?>../admin" class="dropdown-item">
                                <i class="fas fa-user-cog"></i>&nbsp;
                                Halaman Admin
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <?php if (@$_SESSION['admin'] == 'admin' || @$_SESSION['admin'] == 'pembimbing') {
                            if (@$admin == true) {
                                $_ll = "../";
                            } else {
                                $_ll = "";
                            }
                            ?>
                            <li>
                                <a href="<?= $_ll; ?>app/inputabsen.php" class="dropdown-item">
                                    <!-- icon back -->
                                    <i class="far fa-edit text-success"></i>&nbsp;
                                    Input Absen
                                </a>
                            </li>
                        <?php } ?>

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a href="<?= $link; ?>rekapabsensiswa.php" class="dropdown-item">
                                <!-- icon list user -->
                                <i class="fas fa-list fa-bounce"></i>&nbsp;
                                Rekap Absensi Siswa
                            </a>
                        </li>
                        <li>
                            <a href="<?= $link; ?>datasiswa.php" class="dropdown-item">
                                <!-- icon list user -->
                                <i class="fas fa-users fa-flip"></i>&nbsp;
                                Daftar Siswa
                            </a>
                        </li>

                        <li>
                            <a href="<?= $link; ?>recent.php" class="dropdown-item">
                                <!-- icon list -->
                                <i class="fas fa-history fa-spin fa-spin-reverse"></i>&nbsp;
                                Riwayat Pendaftaran
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a href="<?= $link; ?>pembimbing.php" class="dropdown-item">
                                <!-- icon list user -->
                                <i class="fas fa-list fa-bounce"></i>&nbsp;
                                Data Pembimbing
                            </a>
                        </li>

                        <?php if (@$_SESSION['admin'] == 'admin') { ?>
                            <li>
                                <a href="<?= $link; ?>tambahpembimbing.php?akses=tambah" class="dropdown-item">
                                    <!-- icon ubah data -->
                                    <i class="fas fa-plus fa-beat"></i>&nbsp;
                                    Tambah Pembimbing
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a href="<?= $link; ?>tambahdudi.php" class="dropdown-item">
                                    <!-- icon tambah data -->
                                    <i class="fas fa-plus fa-beat"></i>&nbsp;
                                    Tambah DUDIKA
                                </a>
                            </li>

                            <li>
                                <a href="<?= $link; ?>ubahdudi.php" class="dropdown-item">
                                    <!-- icon ubah data -->
                                    <i class="fas fa-edit fa-shake"></i>&nbsp;
                                    Ubah DUDIKA
                                </a>
                            </li>

                        <?php } ?>

                        <li>
                            <a href="../list.php" class="dropdown-item">
                                <!-- icon list -->
                                <i class="fas fa-list-alt fa-bounce"></i>&nbsp;
                                Daftar DUDIKA
                            </a>
                        </li>

                        <?php if (@$_SESSION['admin'] == 'admin') { ?>
                            <?php
                            if (@$admin == true) {
                                $_ll = "../";
                            } else {
                                $_ll = "";
                            }
                            ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="<?= $_ll; ?>data" class="dropdown-item">
                                    <!-- icon back -->
                                    <i class="fas fa-database text-danger"></i>&nbsp;
                                    Input Data
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="<?= $_ll; ?>app/brotkes.php" class="dropdown-item">
                                    <!-- icon back -->
                                    <i class="far fa-message text-warning"></i>&nbsp;
                                    Pesan Broadcast
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (@$_SESSION['admin']) { ?>
                            <li>
                                <a href="<?= $_ll; ?>app/chatbot.php" class="dropdown-item">
                                    <!-- icon back -->
                                    <i class="far fa-message text-success"></i>&nbsp;
                                    Pesan Whatsapp Presensi
                                </a>
                            </li>
                        <?php } ?>

                        <?php if (@$admin == true) { ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a href="../admin" class="dropdown-item">
                                    <!-- icon back -->
                                    <i class="fas fa-arrow-left fa-fade"></i>&nbsp;
                                    Kembali
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
                <!---->

            <?php } else { ?>
                <!-- button about -->
                <button type="button" class="btn btn-light nganan2 btn-sm border-0" data-bs-toggle="modal"
                    data-bs-target="#aboutinfo">
                    <!-- info tanya -->
                    <i class="fas fa-question-circle fa-shake text-info"></i>&nbsp;
                </button>
                <a class="btn btn-light btn-sm nganan border-0" data-bs-toggle="modal" data-bs-target="#adminlogin">
                    <!-- icon admin -->
                    &nbsp;<i class="fas fa-user-cog fa-fade"></i>&nbsp;Login
                </a>
                <!-- <a href="admin" class="btn nganan2 border-0 btn-sm btn-warning">Admin</a> -->
            <?php } ?>
        <?php } else {
        if (@$_SESSION['userdudi']) {
            ?>
                <a href="../admin/logout.php" class="btn btn-sm btn-danger nganan border-0 text-light">
                    <!-- icon log out -->
                    <i class="fas fa-sign-out-alt fa-beat-fade"></i>&nbsp;
                    Logout
                </a>
            <?php }
    } ?>
</nav>


<!-- Modal -->
<div class="modal fade" id="adminlogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="adminloginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="text-center mb-3">Login&nbsp;<?= (@$dudika == true) ? "DUDIKA" : "Admin"; ?></h5>
                <div class="logo_login">
                    <img src="SMKNBansari.png" class="logo_4" alt="">
                    <img src="SMKBOS.png" class="logo_4" alt="">
                </div>
                <form action="<?= $relogin; ?>admin/login.php" method="GET">
                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input type="text" id="form2Example1" name="username" class="form-control" required
                            placeholder="Username" />
                    </div>

                    <div class="form-outline mb-4 d-flex">
                        <input type="password" id="form2Example2" name="password" class="form-control" required
                            placeholder="Password" />
                        <button type="button" id="togglePassword" class="btn" onclick="togglePasswordVisibility()">
                            <i id="eyeIcon" class="far fa-eye-slash"></i>
                        </button>
                    </div>

                    <script>
                        function togglePasswordVisibility() {
                            var passwordField = document.getElementById("form2Example2");
                            var eyeIcon = document.getElementById("eyeIcon");

                            if (passwordField.type === "password") {
                                passwordField.type = "text";
                                eyeIcon.classList.remove("far", "fa-eye-slash");
                                eyeIcon.classList.add("fas", "fa-eye");
                            } else {
                                passwordField.type = "password";
                                eyeIcon.classList.remove("fas", "fa-eye");
                                eyeIcon.classList.add("far", "fa-eye-slash");
                            }
                        }
                    </script>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">
                    <i class="fas fa-times fa-beat"></i>&nbsp;
                    Tutup</button>
                <button type="submit" name="login" value="admin" class="btn btn-primary border-0">
                    <i class="fa-solid fa-right-to-bracket fa-fade"></i>&nbsp;
                    Login</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal info about -->
<style>
    .logo_login {
        display: flex;
    }

    .modal-about .logo_about {
        display: flex;
    }

    .modal-about .logo_about img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .modal-about .logo_about label {
        font-size: 14px;
        font-weight: 800;
        display: flex;
        justify-content: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .modal-about .text-about {
        flex-wrap: wrap;
        text-align: justify;
        font-size: 12px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .modal-about h3,
    .modal-about h6,
    #aboutinfoLabel {
        text-align: center;
    }

    .modal-about .nganan_pol {
        position: absolute;
        right: 0;
        margin-right: 10px;
        margin-top: -10px;
    }

    .btn-centered {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    @media screen and (max-width: 768px) {
        .modal-about .logo_about {
            display: flex;
            justify-content: space-around;
        }

    }
</style>

<div class="modal fade" id="aboutinfo" tabindex="-1" aria-labelledby="aboutinfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body modal-about">
                <button type="button" class="btn-close nganan_pol mt-2" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <h5 class="modal-title" id="aboutinfoLabel">About&nbsp;
                    <i class="fas fa-question-circle text-info"></i>
                </h5>
                <h3>Pemilihan Tempat Prakerin</h3>
                <h6>(Web Application)</h6>
                <div class="logo_about">
                    <div class="col-md-6">
                        <img src="SMKNBansari.png" id="logoSMK1" class="logo_1">
                        <label for="logoSMK1">SMK NEGERI BANSARI</label>
                    </div>
                    <div class="col-md-6">
                        <img src="TE2.gif" id="logoTE1" class="logo_1">
                        <label for="logoTE1">TEKNIK ELEKTRONIKA</label>
                    </div>
                </div>
                <div class="text-about">
                    <p>
                        Web Application ini dibuat untuk memudahkan para siswa(i) memilih tempat prakerin serta
                        memudahkan pendataan.
                        Siswa(i) dapat melihat list (daftar) DUDIKA (tempat Prakerin) secara detail, baik nama, alamat,
                        lokasi maps, pembimbing, serta informasi kos dan biaya (jika ada). <br>
                        Siswa(i) akan memilih tempat prakerin sesuai dengan kuota yang masih tersedia. Siswa(i) tidak
                        perlu mengisi data diri cukup memasukkan NIS, karena semua telah otomatis terisi dari database.
                        <br>
                        Siswa(i) dapat menghubungi guru pembimbing dari DUDIKA masing-masing melalui tombol chat
                        Whatsapp untuk menanyakan/mengetahui informasi lebih lanjut tentang tempat prakerin yang
                        akan/telah dipilih. <br>
                        Surat ijin dan sebagaimya telah otomatis dapat dicetak (print) melalui aplikasi web ini.
                        Pembimbing dapat memamtau pendaftaran Siswa(i) dan mengubah pilihan siswa(i) dan mengubah serta
                        menambah info DUDIKA.<br><br>

                        Segala pertanyaan, dukungan, aduan, kritik dan saran mengenai web aplikasi ini, bisa menghubungi
                        pengembang melalui kontak berikut.<br>
                    </p>
                    <!-- kirim email -->
                    <!-- button grup -->
                    <div class="btn-centered">
                        <a href="mailto:ben@smknbansari.sch.id" class="btn btn-sm btn-warning border-0" target="_blank">
                            <i class="fas fa-envelope"></i></a>
                        </a>
                        <!-- instagram -->
                        <a href="https://www.instagram.com/te.skaneba/" class="btn btn-sm btn-danger border-0"
                            target="_blank">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <!-- whatsapp -->
                        <a href="https://api.whatsapp.com/send?phone=6282241863393&text=About%20pkl.smknbansari.sch.id%0A%0A"
                            class="btn btn-sm btn-success border-0" target="_blank">
                            <i class="fab fa-whatsapp"></i></a>
                        </a>
                    </div>
                    <div class="mt-2 text-center">
                        <label>TE Skaneba <i class="fa fa-copyright"></i> 2023</label>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm border-0" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<?php
function encryptPhoneNumber($number)
{
    // Implementasi enkripsi sesuai kebutuhan Anda
    // Misalnya, enkripsi sederhana XOR
    if (isset($number) && $number != "") {
        $encrypted = base64_encode($number);
    } else {
        $encrypted = base64_encode("");
    }

    return $encrypted;
}

// Fungsi untuk mendekripsi nomor WhatsApp
function decryptPhoneNumber($encrypted)
{
    // Implementasi dekripsi sesuai kebutuhan Anda
    // Misalnya, dekripsi base64
    if (isset($encrypted) && $encrypted != "") {
        $decrypted = base64_decode($encrypted);
    } else {
        $decrypted = base64_decode("");
    }

    return $decrypted;
}
