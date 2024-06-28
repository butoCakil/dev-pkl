<div id="btn_home" class="btn-group">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary border-0" data-bs-toggle="modal" data-bs-target="#modallogin">
        <i class="fa-solid fa-right-to-bracket fa-beat"></i>&nbsp;
        Masuk
    </button>

    <!--<a href="dudi" class="btn btn-warning border-0"><i class="fa-solid fa-right-to-bracket fa-beat"></i>&nbsp;<i class="fa-solid fa-briefcase"></i>&nbsp;Login DU/DI</a>-->
    <a href="list.php" class="btn btn-success border-0"><i class="fa-solid fa-rectangle-list fa-bounce"></i>&nbsp;List
        DU/DI</a>
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
<div class="modal fade" id="modallogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="modalloginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalloginLabel">Prakerin (Praktik Kerja Industri)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="logo_login">
                    <img src="SMKNBansari.png" class="logo_4" alt="">
                    <img src="at.png" class="logo_size_3" alt="">
                    <img src="dkv.png" class="logo_size_3" alt="">
                    <img src="te.png" class="logo_size_3" alt="">
                    <img src="SMKBOS.png" class="logo_4" alt="">
                </div>
                <form action="presensi.php" method="post">
                    <div class="mb-3">
                        <div id="nis" class="form-text">
                            <label for="nis" class="form-label">N I S <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="nis" name="nis"
                                placeholder="Nomor Induk Siswa (NIS)" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div id="pass" class="form-text">
                            <label for="inputpassword" class="form-label">Password <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="inputpassword" name="password"
                                    placeholder="Password" required>
                                <button type="button" id="togglePassword" class="btn btn-outline-secondary"
                                    onclick="togglePasswordVisibility()">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                            <span class="fst-italic text-danger">Password default: 8 karakter dari nama <br>(tanpa
                                spasi, huruf
                                kecil semua)
                                Contoh: `Elsa Fadila` menjadi `elsafadi`
                            </span>
                        </div>
                    </div>

                    <script>
                        function togglePasswordVisibility() {
                            var passwordInput = document.getElementById("inputpassword");
                            var toggleIcon = document.getElementById("togglePassword").querySelector("i");

                            if (passwordInput.type === "password") {
                                passwordInput.type = "text";
                                toggleIcon.classList.remove("fa-eye");
                                toggleIcon.classList.add("fa-eye-slash");
                            } else {
                                passwordInput.type = "password";
                                toggleIcon.classList.remove("fa-eye-slash");
                                toggleIcon.classList.add("fa-eye");
                            }
                        }
                    </script>

                    <input type="hidden" name="kodedudi_" value="<?= $kodedudi_next; ?>">
                    <input type="hidden" name="akses_" value="<?= $akses_next; ?>">

                    <div class="d-flex mb-3" style="justify-content: space-between;">
                        <button type="submit" name="akses" value="presensi" class="btn btn-sm btn-warning border-0">
                            <i class="fa-solid fa-user fa-bounce"></i>&nbsp;
                            Presensi
                        </button>

                        <a href="list.php" class="btn btn-sm btn-info border-0">
                            <i class="fa-solid fa-rectangle-list fa-fade"></i>&nbsp;
                            Lihat Tempat Prakerin
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                Alternatif Presensi
                <div class="mt-3">
                    <div class="alert alert-info alert-dismissible fade show" role="alert" style="font-size: 12px;">
                        Coba Presensi menggunakan Whatsapp.
                        <br>
                        Chat dengan "presensi" (tanpa anda petik) untuk mendapatkan balasan berupa petunjuk cara
                        presensi.
                    </div>
                    <a href="#" class="btn btn-sm btn-success border-0 w-100" onclick="return alert('Belum Aktif');"
                        disabled>Presensi Via
                        Whatsapp&nbsp;<i class="fa-brands fa-whatsapp fa-beat"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>