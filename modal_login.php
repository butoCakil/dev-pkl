<div id="btn_home" class="btn-group">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary border-0" data-bs-toggle="modal" data-bs-target="#modallogin">
        <i class="fa-solid fa-right-to-bracket fa-beat"></i>&nbsp;
        Masuk
    </button>

    <!--<a href="dudi" class="btn btn-warning border-0"><i class="fa-solid fa-right-to-bracket fa-beat"></i>&nbsp;<i class="fa-solid fa-briefcase"></i>&nbsp;Login DU/DI</a>-->
    <a href="list.php" class="btn btn-success border-0"><i class="fa-solid fa-rectangle-list fa-bounce"></i>&nbsp;List DU/DI</a>
</div>

<?php
if (@$_GET['kodedudi_next'] && @$_GET['akses_next']) {
    $kodedudi_next = $_GET['kodedudi_next'];
    $akses_next = $_GET['akses_next'];
} else {
    $kodedudi_next = '';
    $akses_next = '';
}
?>

<!-- Modal -->
<div class="modal fade" id="modallogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalloginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalloginLabel">Prakerin (Praktik Kerja Industri)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="logo_login">
                    <img src="SMKNBansari.png" class="logo_4" alt="">
                    <img src="SMKBOS.png" class="logo_4" alt="">
                </div>
                <form action="index.php" method="GET">
                    <div class="mb-3">
                        <input type="hidden" name="kodedudi_" value="<?= $kodedudi_next; ?>">
                        <input type="hidden" name="akses_" value="<?= $akses_next; ?>">

                        <!--<?php if (@$_SESSION["admin"]) { ?>-->
                        <!--<?php } ?>-->

                        <div id="nis" class="form-text">
                            <!--Isikan NIS (Nomor Induk Siswa) dan klik "Masuk" untuk mendaftar tempat prakerin. Cek "Lihat Tempat Prakerin" untuk melihat daftar semua DUDIKA tempat prakerin lebih detail.<br>-->
                            <div class="alert alert-info">
                                <i class="fa-solid fa-circle-info text-info fa-beat-fade"></i>&nbsp;
                                <!--Pastikan telah berdiskusi dengan orang tua masing-masing tentang tempat prakerin yang ada dan yang ingin dipilih.-->
                                Lakukan presensi <b>setiap hari kerja</b>, Ketikkan NIS lalu klik tombol "Presensi" di bawah ini.
                            </div>

                            <label for="nis" class="form-label">N I S</label>
                            <input type="number" class="form-control" id="nis" name="nis" placeholder="Nomor Induk Siswa (NIS)" required>

                            <!--Presensi selama prakerin, ketik NIS lalu klik tombol "presensi".-->
                            <!--<div class="alert alert-warning alert-dismissible fade show" role="alert">-->
                                <!--<i class="fa-solid fa-circle-info text-danger fa-beat-fade"></i>&nbsp;-->
                                <!--Foto Presensi: Selfie dengan background tempat Prakerin atau kegiatan yang sedang dilakukan.<br>-->
                                <!--Waktu Presensi: Tidak terbatas waktu asal masih di tanggal yang sama.-->
                                <!--Pendaftaran tempat Prakerin <b>hanya melalui Pak Arif</b>.<br>-->
                                <!--Siswa(i) bisa memilih tempat prakerin yang ada di List berikut ini, -->
                                <!--Atau mencari tempat prakerin sendiri kemudian <b>dilaporkan</b> dan <b>dikonfirmasikan</b> ke Pak Arif-->
                                <!--<?php $link_wa = "https://api.whatsapp.com/send?phone=" . "6287735512475" . "&text=Assalamu'alaikum,%20Pak%20Arif%0ASaya:%20%0AKelas:%20%0Atempat%20prakerin:%20%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20melaporkan%20soal%20prakerin.%20"; ?>-->
                                <!--<a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i class="fa-brands fa-whatsapp text-light"></i>&nbsp;Chat ke Pak Arif</a>-->
                                <!--    <strong><i class="fa-solid fa-warning text-danger fa-beat"></i>&nbsp;Info:</strong> Jika ada masalah dalam aplikasi ini, hubungi Pak Benny <i>(sertakan screenshot)</i>&nbsp;📷.<br>-->
                                <!--    <?php $link_wa = "https://api.whatsapp.com/send?phone=" . "6282241863393" . "&text=Assalamu'alaikum,%0ASaya:%20%0AKelas:%20%0Atempat%20prakerin:%20%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20melaporkan%20masalah%20di%20aplikasi%20prakerin.%20Masalahnya%20seperti%20ini:%20"; ?>-->
                                <!--    <a href="<?= $link_wa; ?>" class="btn btn-sm btn-success border-0"><i class="fa-brands fa-whatsapp text-light"></i>&nbsp;Chat</a>-->
                                <!--    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
                            <!--</div>-->
                        </div>
                    </div>

                    <div class="d-flex" style="justify-content: space-between;">
                        <button type="submit" name="akses" value="presensi" class="btn btn-sm btn-warning border-0">
                            <i class="fa-solid fa-user fa-bounce"></i>&nbsp;
                            Presensi
                        </button>

                        <button type="submit" name="akses" value="rekap" class="btn btn-sm btn-success border-0">
                            <i class="fa-solid fa-bars"></i>&nbsp;
                            Rekap&nbsp;
                        </button>

                        <?php if (@$_SESSION["admin"]) { ?>
                            <button type="submit" class="btn btn-sm btn-primary border-0" name="akses" value="login_nis">
                                <i class="fa-solid fa-right-to-bracket fa-beat"></i>
                                Daftar&nbsp;
                            </button>

                            <button type="submit" class="btn btn-sm btn-dark border-0" name="akses" value="login_nis">
                                <i class="fa-solid fa-bars"></i>
                                Rekap&nbsp;
                            </button>
                        <?php } ?>


                        <a href="list.php" class="btn btn-sm btn-info border-0">
                            <i class="fa-solid fa-rectangle-list fa-fade"></i>&nbsp;
                            Lihat Tempat Prakerin
                        </a>
                        <!--<a href="dudi" class="btn btn-success border-0 btn-sm"><i class="fa-solid fa-briefcase"></i>&nbsp;Login DU/DI</a>-->
                    </div>
                    
                    <hr>
                    
                    <div class="mt-3">
                        <div class="alert alert-info alert-dismissible fade show" role="alert" style="font-size: 12px;">
                            Coba Presensi menggunakan Whatsapp. 
                            <br>
                            Chat dengan "Presensi" (tanpa anda petik) untuk mendapatkan balasan berupa petunjuk cara presensi.
                        </div>
                        <a href="http://wa.me/6285602869114" class="btn btn-sm btn-success border-0 w-100">Presensi Vie Whatsapp&nbsp;<i class="fa-brands fa-whatsapp fa-beat"></i></a>
                    </div>

                    <!-- <button type="button" class="btn btn-sm btn-light nganan border-0" data-bs-toggle="modal" data-bs-target="#aboutinfo">
                        <i class="fas fa-question-circle text-info"></i>&nbsp;
                    </button> -->
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div> -->
        </div>
    </div>
</div>