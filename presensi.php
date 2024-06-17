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


if (@$_GET["nis"] && (@$_GET["akses"] == "presensi" || @$_GET["akses"] == "presensilagi")) {

    $nis = $_GET["nis"];
    $akses = $_GET["akses"];

    // ambil data siswa dari database
    include "koneksi.php";

    // cek dulu di presensi
    $sql = "SELECT * FROM presensi WHERE nis = '$nis' AND timestamp LIKE '%$tanggal%'";
    $result = mysqli_query($konek, $sql);

    // jika sudah ada maka redirect ke halaman preview
    if (mysqli_num_rows($result) > 0 && @$_GET["akses"] != "presensilagi") {
        // header("location: prevpresensi.php?nis=$nis&akses=presensi");
        echo "<script>
                window.location.href = 'prevpresensi.php?nis=$nis&akses=presensi';
            </script>";
    }

    $sql = "SELECT * FROM datasiswa WHERE nis = '" . $nis . "'";
    $result = mysqli_query($konek, $sql);
    $row = mysqli_fetch_assoc($result);
    $adadata = mysqli_num_rows($result);

    $sql2 = "SELECT * FROM duditerisi WHERE nis = '" . $nis . "'";
    $result2 = mysqli_query($konek, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    $kode_dudi = $row2['kode'];

    $pembimbing_query = "SELECT * FROM datadudi WHERE kode = '$kode_dudi'";
    $query_pembimbing = mysqli_query($konek, $pembimbing_query);
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

        .form-presensi {
            /* form ke tengah */
            margin-top: 0px;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .form-presensi .alert {
            font-size: 0.8em;
            margin: -30px 0 0 0;
            padding: 0 10px;
        }

        .form-presensi .label-datadiri-presensi label {
            width: 100px;
        }

        .form-presensi .label-datadiri-presensi strong {
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

        .form-presensi .preview {
            padding: 10px;
            border: 1px solid #ccc;
            width: 0 auto;
        }

        .form-presensi .preview img {
            height: 100px;
            width: 100px;
            object-fit: contain;
            margin: 0 auto;
            /* object-position: center; */
        }

        .form-presensi .preview #preview {
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
        <?php if ($adadata) { ?>
            <?php if ($akses != "presensilagi") { ?>
                <h1>Presensi Prakerin</h1>
            <?php } else { ?>
                <h1>Pengisian Jurnal Harian</h1>
            <?php } ?>
            <!-- alert info dengan button close -->
            
            <!--<div id="liveAlertPlaceholder"></div>-->
            <!--<button type="button" class="btn btn-primary" id="liveAlertBtn">Info</button>-->
            
            <!--<div class="info-alert-presensi alert alert-warning alert-dismissible fade show" role="alert">-->
            <!--    <strong>Info!</strong>-->
            <!--    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
            <!--    <p>-->
            <!--        <li>-->
            <!--            <strong>Klik tombol "Kamera"</strong> untuk mengambil foto dari kamera.-->
            <!--        </li>-->

            <!--        <?php if ($akses != "presensilagi") { ?>-->
            <!--            <li>-->
            <!--                <strong>Pilih "Keterangan"</strong> untuk mengisi keterangan Presensi (Masuk, Pulang, Ijin, Sakit, atau Libur).-->
            <!--            </li>-->
            <!--            <li>-->
            <!--                <strong>Isikan catatan</strong> kegiatan hari ini sebagai <strong>Jurnal harian</strong>-->
            <!--            </li>-->
            <!--        <?php } else { ?>-->
            <!--            <li>-->
            <!--                <strong>Isikan catatan</strong> sebagai <strong>Jurnal harian</strong>-->
            <!--            </li>-->
            <!--        <?php } ?>-->

            <!--        <li>-->
            <!--            <strong>Klik tombol "Upload"</strong> untuk menyelesaikanya.-->
            <!--        </li>-->
            <!--    </p>-->
            <!--</div>-->

            <div class="col form-presensi">
                <form action="_presensi.php" method="post" enctype="multipart/form-data">

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
                                <strong>:&nbsp;<?= $row2['namadudi']; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label for="">Hari, tanggal</label>
                                <strong>:&nbsp;<?= $hari_indonesia . ", " . $tgl . " " . $bulan_indonesia . " " . $thn; ?></strong><br>
                            </div>
                            <div class="label-datadiri-presensi">
                                <label>Jam</label>
                                <strong>:&nbsp;<?= $jam; ?></strong><br>
                            </div>
                            </p>
                        </div>
                    <?php } ?>

                    <input type="hidden" name="kodedudika" value="<?= $row2['kode']; ?>">
                    <input type="hidden" name="nis" value="<?= $row['nis']; ?>">
                    <input type="hidden" name="nama" value="<?= $row['nama']; ?>">
                    <input type="hidden" name="namadudi" value="<?= $row2['namadudi']; ?>">
                    <input type="hidden" name="kelas" value="<?= $row['kelas']; ?>">

                    <div class="form-group input-form-presensi">
                        <label for="foto">Foto</label>
                        <!-- <input type="file" class="form-control" id="foto" name="foto[]" multiple> -->
                        <div>

                            <?php if ($ua->is_mobile()) { ?>
                                <div id="tombolkamera">
                                    <label class="custom-file-input custom-file-input-kamera" for="UploadCam"></label>
                                    <input id="UploadCam" type="file" name="_photos" accept="image/*" style="visibility: hidden" onchange="showPreview(event);" capture="environment" required oninvalid="alert('Ambil Foto Kamera terlebih dahulu!');">
                                </div>
                            <?php } else { ?>
                                <div id="tombolfile">
                                    <label class="custom-file-input custom-file-input-file" for="fileupload"></label>
                                    <!--<input id="fileupload" type="button" multiple="multiple" name="file_photos[]" accept="image/*" style="visibility: hidden" required oninvalid="alert('Pilih Beberapa file foto terlebih dahulu!');">-->
                                    <button id="fileupload" type="button" multiple="multiple" name="file_photos[]" style="visibility: hidden" required onclick="alert('Gunakan SmartPhone untuk mengambil Foto / Non-Aktifkan mode Dekstop');" />
                                </div>
                            <?php } ?>
                        </div>

                        <style>
                            .input-form-presensi .alert-info-tombol-kamera p {
                                font-size: 10px;
                                margin: 0;
                            }

                            .input-form-presensi .alert-info-tombol-kamera {
                                margin-bottom: 5px;
                                padding: 5px;
                            }
                        </style>

                        <span class="alert-info-tombol-kamera alert alert-info">
                            <p>
                                <i class="fas fa-circle-info fa-bounce"></i>
                                Foto yang diupload adalah foto selfie berlatar belakang tempat Prakerin / kegiatan selama Prakerin.
                            </p>
                        </span>
                    </div>
                    <div class="form-group preview">
                        <img id="dvPreview">
                        <div id="preview"></div>
                    </div>

                    <?php if ($akses != "presensilagi") { ?>
                        <!-- input select keterangan -->
                        <div class="form-group input-form-presensi">
                            <label for="keterangan">Keterangan (Pilih)</label>
                            <select class="form-control" id="keterangan" name="keterangan" required>
                                <!--<option value="">Pilih Keterangan</option>-->
                                <option value="Masuk">Masuk</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Tidak_Masuk">Tidak Masuk / Libur</option>
                            </select>
                        </div>
                        <!-- input text area -->
                        <div class="form-group input-form-presensi">
                            <label for="catatanjurnal">Catatan / Jurnal</label>
                            <textarea class="form-control" id="catatanjurnal" name="catatanjurnal" rows="4" placeholder="Isikan juga Agenda/rencana/kegiatan hari ini di tempat prakerin (PKL)." required></textarea>
                        </div>
                    <?php } ?>

                    <?php
                    $sqlcek = "SELECT * FROM presensi WHERE nis = '$nis' AND kode = '" . $row2['kode'] . "' AND timestamp LIKE '%$tanggal%'";
                    $querycek = mysqli_query($konek, $sqlcek);
                    $rowcek = mysqli_num_rows($querycek);

                    if ($rowcek > 0) {
                    ?>
                        <!-- input text area -->
                        <div class="form-group input-form-presensi">
                            <label for="catatanjurnal">Catatan / Jurnal</label>
                            <textarea class="form-control" id="catatanjurnal" name="catatanjurnal" rows="4" placeholder="Isikan catatan/jurnal kegiatan hari ini di tempat prakerin sesuai foto."></textarea>
                        </div>
                    <?php } ?>

                    <input type="hidden" name="nowapemb" value="<?= $data_pembimbing["nowa"]; ?>">
                    <input type="hidden" name="tok" value="qnNDCgY0F4F3MVna2LoF">

                    <div class="tombol-upload-presensi">
                        <button type="submit" class="btn btn-primary btn-sm border-0">
                            <!-- <i class="fas fa-upload fa-bounce"></i>&nbsp; -->
                            <i class="fa-solid fa-cloud-arrow-up fa-beat-fade"></i> Upload
                        </button>
                        <a href="index.php" class="btn btn-dark btn-sm border-0">
                            <!-- <i class="fas fa-times fa-beat-fade"></i> -->
                            <i class="fa-solid fa-xmark"></i> Batal
                        </a>
                        <a href="rekap.php?nis=<?= $row['nis']; ?>&akses=rekapabsen" class="btn btn-success btn-sm border-0">
                            <i class="fas fa-file-alt"></i> Rekap&nbsp;Saya
                        </a>
                        <!--<button type="button" class="btn btn-secondary btn-sm border-0" onClick="document.location.reload(true)">-->
                            <!-- icon reload -->
                        <!--    <i class=" fas fa-redo-alt fa-spin"></i> Refresh-->
                        <!--</button>-->
                    </div>
                </form>
                <div class="input-group input-group-sm mt-3 mb-5">
                    <span class="input-group-text bg-dark text-light gradient">Pembimbing </span>
                    <input type="text" class="form-control" value="<?= $data_pembimbing["pembimbing"]; ?>" disabled>
                    <?php
                    $msg_nama = str_replace(" ", "%20", $row['nama']);
                    $msg_kelas = str_replace(" ", "%20", $row['kelas']);
                    $dudika_ = str_replace(" ", "%20", $row2['namadudi']);
                    $link_wa = "https://api.whatsapp.com/send?phone=" . $data_pembimbing["nowa"] . "&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0Atempat%20prakerin:%20" . $dudika_ . "%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20..";
                    ?>
                    <span class="input-group-text bg-success btn-success"><a href="<?= $link_wa; ?>" class="text-light"><i class="fa-brands fa-whatsapp fa-beat" style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a></span>
                </div>
                
                <div class="info-alert-presensi alert alert-danger alert-dismissible fade show pb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <p class="p-2 mb-0">
                    <strong>Bantuan (?)</strong><br>
                        Jika ada kendala saat presensi prakerin,<br>silakan hubungi Pak Benny<br>(Sertakan Screenshoot)
                             
                         <?php
                            $msg_nama = str_replace(" ", "%20", $row['nama']);
                            $msg_kelas = str_replace(" ", "%20", $row['kelas']);
                            $dudika_ = str_replace(" ", "%20", $row2['namadudi']);
                             $link_wa = "https://api.whatsapp.com/send?phone=6282241863393&text=Assalamu'alaikum,%0ASaya%20" . $msg_nama . "%0AKelas:%20" . $msg_kelas . ",%0Atempat%20prakerin:%20" . $dudika_ . "%0AMaaf%20mengganggu%20waktunya,%20Saya%20ingin%20melaporkan%20kendala%20dalam%20presensi%0AKendalanya: ...";
                        ?>
                    </p>
                    <a href="<?= $link_wa; ?>" class="bg-success btn-success text-light p-1 m-0 rounded text-decoration-none">Chat WA <i class="fa-brands fa-whatsapp" style="--fa-beat-scale: 1.5; --fa-animation-duration: 1s;"></i></a>
                </div>
            </div>
            
        <?php } else { ?>
            <h1>Tidak ada data</h1>
            <a href="index.php" class="btn btn-dark btn-sm border-0">
                <!-- <i class="fas fa-times fa-beat-fade"></i> -->
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

                reader.addEventListener("load", function() {
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


    //     const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
    //     const appendAlert = (message, type) => {
    //     const wrapper = document.createElement('div')
    //     wrapper.innerHTML = [
    //         `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    //         `   <div>${message}</div>`,
    //         '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    //         '</div>'
    //       ].join('')
        
    //     alertPlaceholder.append(wrapper)
    //     }
        
    //     const alertTrigger = document.getElementById('liveAlertBtn')
    //     if (alertTrigger) {
    //       alertTrigger.addEventListener('click', () => {
    //         appendAlert('
    //         <p>
    //                 <li>
    //                     <strong>Klik tombol "Kamera"</strong> untuk mengambil foto dari kamera.
    //                 </li>

    //                 <?php if ($akses != "presensilagi") { ?>
    //                     <li>
    //                         <strong>Pilih "Keterangan"</strong> untuk mengisi keterangan Presensi (Masuk, Pulang, Ijin, Sakit, atau Libur).
    //                     </li>
    //                     <li>
    //                         <strong>Isikan catatan</strong> kegiatan hari ini sebagai <strong>Jurnal harian</strong>
    //                     </li>
    //                 <?php } else { ?>
    //                     <li>
    //                         <strong>Isikan catatan</strong> sebagai <strong>Jurnal harian</strong>
    //                     </li>
    //                 <?php } ?>

    //                 <li>
    //                     <strong>Klik tombol "Upload"</strong> untuk menyelesaikanya.
    //                 </li>
    //             </p>
            
    //         ', 'success')
    //       })
    //     }
    </script>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
    <script>
        $("input").on("change", function() {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD")
                .format(this.getAttribute("data-date-format"))
            )
        }).trigger("change")

        $(function() {
            $('#jam').datetimepicker({
                use24hours: true,
                format: 'HH:mm'
            });
        });
    </script>

    <script>
        $(function() {
            $('#datetimepicker1').datetimepicker({
                format: 'HH:mm'
            });

            $('#datetimepicker2').datetimepicker({
                format: 'MM/DD/YYYY HH:mm'
            });
        });
    </script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

<?php } else { ?>

    <?php include "views/header.php" ?>
    <?php include "views/navbar.php" ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<?php include "views/footer.php" ?>