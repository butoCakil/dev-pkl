<?php
session_start();
$admin = true;
include "../views/header.php";
include "../views/navbar.php";

if (@$_SESSION['admin']) {
    include "../koneksi.php";

    $result_pembimbing = mysqli_query($konek, "SELECT * FROM datapembimbing");
    $result_dudi = mysqli_query($konek, "SELECT * FROM datadudi");
?>

    <style>
        #nohp {
            font-size: 10px;
            margin: 0;
        }

        .col-4 label:hover {
            cursor: pointer;
        }

        .daftar-kontak {
            height: 50vh;
            width: 50vh;
            overflow: auto;
            border: 1px solid #ccc;
        }

        #chat-box {
            padding: 5px;
            height: 50vh;
            width: 100vh;
            background-color: lightgray;
            border-radius: 5px;
        }

        #chat-box-msg {
            background-color: lightcyan;
            height: 50vh;
            width: 100vh;
            overflow: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #chat-box-reply {
            height: 20vh;
            width: 100vh;
            overflow: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        @media screen and (max-width: 900px) {

            .daftar-kontak,
            #chat-box,
            #chat-box-msg,
            #chat-box-reply {
                width: 100%;
            }
        }
    </style>

    <style>
        .chat-container {
            margin: 20px auto;
            background-color: #fff;
            /* box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2); */
        }

        .chat-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .chat-messages {
            padding: 10px;
        }

        .message {
            background-color: #e0e0e0;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
            position: relative;
            max-width: 100%;
        }

        .message.self {
            background-color: #007bff;
            color: #fff;
            align-self: flex-end;
            text-align: right;
        }

        .message .username {
            font-weight: bold;
        }

        .message .timestamp {
            font-size: 10px;
            color: #777;
            position: absolute;
            bottom: -1px;
            right: 5px;
            color: lightgrey;
        }

        .message-input {
            width: 100%;
            padding: 10px;
            border: none;
            border-top: 1px solid #ccc;
            box-sizing: border-box;
            resize: none;
        }

        .send-button {
            display: block;
            width: 100%;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="container">
        <h2>Chat Pembimbing</h2>

        <div class="dropdown m-2">
            <a class="btn btn-success btn-sm border-0 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Pembimbing
            </a><br>
            <!--<label for="">Pilih Pembimbing</label>-->

            <ul class="dropdown-menu">
                <?php
                foreach ($result_pembimbing as $dtp) {
                    echo '<li><a class="dropdown-item" href="chatbot.php?p=' . $dtp["id"] . '">' . '[' . $dtp["jur"] . '] ' . $dtp["nama"] . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <?php
        if (@$_GET) {
            $id_pemb = @$_GET['p'];

            $cari_nama_pemb = mysqli_query($konek, "SELECT * FROM datapembimbing WHERE id = '$id_pemb'");

            $hasil_pembimbing = mysqli_fetch_array($cari_nama_pemb);
            $pembimbing = $hasil_pembimbing['nama'];

            $cari_siswa_dari_id_pemb = mysqli_query($konek, "SELECT * FROM duditerisi WHERE pembimbing LIKE '%$pembimbing%'");
        ?>

            <div class="m-0">
                <label for="">Daftar Siswa bimbingan dari : <?= $pembimbing; ?></label>
                <hr>
                <div class="row container">
                    <div class="col-4 daftar-kontak">
                        <?php
                        foreach ($cari_siswa_dari_id_pemb as $datasis) {
                            $namasiswa = $datasis['namasiswa'];
                            $nis = $datasis['nis'];

                            $cari_no_hp = mysqli_query($konek, "SELECT nohp, kelas FROM datasiswa WHERE nis = '$nis'");

                            $datasiswa = mysqli_fetch_array($cari_no_hp);
                            $kelas = $datasiswa['kelas'];
                            $nohp = $datasiswa['nohp'];

                            if ($nohp == "-") {
                                $nohp = "";
                            }

                            $ada_pesan = '';

                            if ($nohp) {
                                $sql_query_chatbot = mysqli_query($konek, "SELECT nomor FROM loadchat WHERE nomor LIKE '%$nohp%' AND status = 'diterima'");
                                if (mysqli_num_rows($sql_query_chatbot) > 0) {
                                    $ada_pesan = '<i class="far fa-message text-success"></i>';
                                }
                            }
                        ?>
                            <label onclick="tampilchat('<?= $nis; ?>', '<?= $nohp; ?>');"><?= $namasiswa; ?>&nbsp;<?= $ada_pesan; ?></label>
                            <br>
                            <label id="nohp" for=""><?= $kelas; ?>&nbsp;(<?= $nis; ?>)</label>
                            <br>
                            <label id="nohp" for=""><?= $nohp ? $nohp : "tidak ada nomor WA"; ?></label>
                            <hr>
                        <?php
                            $ada_pesan = '';
                        } ?>
                    </div>
                    <div id="chat-box" class="col-8">
                        <div class="row">

                            <div id="chat-box-msg">
                                <!-- Pesan-pesan akan ditambahkan di sini -->
                                <div id="contentContainer"></div>
                                <div id="chat-messages"></div>

                            </div>
                        </div>
                        <div class="row">
                            <div id="chat-box-reply">
                                <textarea class="message-input" id="message-input" placeholder="Tulis pesan di sini..."></textarea>
                                <button class="send-button" id="send-button">Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container"></div>
        <?php } else {
            echo "Pilih Pembimbing<br>";
        } ?>
    <?php } else { ?>
        <?php
        $_SESSION['url_go'] = $_SERVER['REQUEST_URI'];
        // echo $_SESSION['url_go'];
        ?>
        <script type="text/javascript">
            window.onload = () => {
                $('#adminlogin').modal('show');
            }
        </script>
    <?php } ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <script>
        var nomorhp = '';

        function ubah_nomorhp(_no) {
            nomorhp = _no;
            // return nomorhp;
        }

        function tampilchat(_nis, _nohp) {
            if (!_nohp || _nohp == "-") {
                _nohp = "-"
            }

            ubah_nomorhp(_nohp);

            // const chatboxmsg = document.getElementById('chat-box-msg');
            // chatboxmsg.scrollTop = chatboxmsg.scrollHeight;
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.innerHTML = '';

            const url = `loadchat.php?nis=${_nis}&nohp=${_nohp}`;
            fetch(url) // Ganti dengan URL yang sesuai ke file PHP Anda
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contentContainer').innerHTML = data;
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                });

            // alert(_nis + ", " + _nohp);
        }

        // Event listener untuk tombol "Send"
        const sendButton = document.getElementById('send-button');
        sendButton.addEventListener('click', function() {
            sendMessage();
        });

        // Fungsi untuk mengirim pesan
        function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const message = messageInput.value; // Mengambil nilai pesan dari textarea
            let nomor = nomorhp;
            const timestamp = getCurrentTimestamp();

            // Mengirim data pesan ke server menggunakan AJAX
            $.ajax({
                type: 'POST',
                url: 'send_message.php', // Ganti dengan URL yang sesuai ke file PHP Anda
                data: {
                    message: message,
                    nomor: nomor,
                    timestamp: timestamp,
                    key: '!234'
                },
                success: function(response) {
                    // Tampilkan pesan balasan dari server (jika ada)
                    // alert(message + '\n' + nomor + '\n' + timestamp);

                    // Tambahkan pesan ke textarea
                    const chatMessages = document.getElementById('chat-messages');
                    const chatboxmsg = document.getElementById('chat-box-msg');
                    chatMessages.innerHTML += `<div class="message self"><span class="username">Anda:</span> ${message}<span class="timestamp">${getCurrentTimestamp()}</span></div>`;

                    // Scroll ke bawah secara otomatis
                    chatboxmsg.scrollTop = chatboxmsg.scrollHeight;
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengirim pesan.');
                }
            });

            // Mengosongkan textarea setelah mengirim pesan
            messageInput.value = '';
        }

        function getCurrentTimestamp() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }
    </script>
    <?php
    include "../views/footer.php";
    ?>