<?php
session_start();
$title = "Admin Prakerin";
$admin = true;

include "../views/header.php";
include "../views/navbar.php";

if (@$_SESSION["admin"]) {
?>

<style>
    .container .box .head {
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.1);
    }
</style>

    <div class="container">
        <div class="box">
            <div class="head">
                <div class="col-md-3">
                    <img src="../TE2.gif" class="logo_1">
                </div>
                <div id="judul">
                    <h2>Prakerin & DUDIKA</h2>
                    <!--<h4>Teknik Elektronika</h4>-->
                    <h5>SMK NEGERI BANSARI</h5>
                    <h6>2023</h6>
                </div>
            </div>
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- button grup center potition-->

                            <style>
                                .btn-group-center {
                                    display: flex;
                                    justify-content: center;
                                }
                                
                                .dropdown-toggle-list {
                                    width:250px;
                                    border-radius: 0px;
                                    border-radius: 0px 0px 0px 0px;
                                }

                                @media screen and (max-width: 992px) {
                                    .btn-group-center {
                                        display: flex;
                                        flex-direction: column;
                                        justify-content: center;
                                        border-radius: 0px;
                                    }
                                    
                                    .btn_ {
                                        margin-top: 20px;
                                        border-radius: 0px;
                                    }
                                    
                                    .dropdown-toggle-list, .dropdown-menu-list {
                                        width: 100%;
                                    }
                                    
                                    .dropdown-menu-list li a {
                                        text-align: center;
                                    }
                                }
                            </style>

                            <div class="btn-group btn-group-center" role="group" aria-label="Basic example">
                                
                                <a href="rekapabsensiswa.php" type="button" class="btn_ border-0 btn-sm btn btn-secondary">
                                    <!-- icon list user -->
                                    <i class="fas fa-list fa-bounce"></i>&nbsp;
                                    Rekap Absensi
                                </a>

                                <div class="dropdown">
                                    <button class="border-0 btn btn_ btn-sm btn-primary dropdown-toggle dropdown-toggle-list" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <!-- icon rekap -->
                                        <i class="fas fa-list-alt fa-fade"></i>
                                        Rekap DUDIKA
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-list">
                                        <li><a class="dropdown-item" href="../list.php">
                                            <i class="fas fa-list fa-beat"></i>&nbsp;
                                            List DUDIKA</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="recent.php">
                                            <i class="fas fa-history fa-spin fa-spin-reverse"></i>&nbsp;
                                            Riwayat Pilihan</a></li>
                                    </ul>
                                </div>
                                
                                <a href="datasiswa.php" type="button" class="btn_ border-0 btn-sm btn btn-danger">
                                    <!-- icon list user -->
                                    <i class="fas fa-users fa-flip"></i>&nbsp;
                                    Daftar Siswa
                                </a>

                                <?php if (@$_SESSION['admin'] == 'admin') { ?>
                                    <a href="tambahdudi.php" type="button" class="btn_ border-0 btn-sm btn btn-success">
                                        <!-- icon tambah data -->
                                        <i class="fas fa-plus fa-beat"></i>&nbsp;
                                        Tambah Dudika
                                    </a>
                                    <a href="ubahdudi.php" type="button" class="btn_ border-0 btn btn-sm btn-warning">
                                        <!-- icon ubah data -->
                                        <i class="fas fa-edit fa-shake"></i>&nbsp;
                                        Ubah Dudika
                                    </a>

                                <?php } ?>
                                
                                <a href="../" type="button" class="btn_ btn-sm border-0 btn btn-dark">
                                    <!-- icon back -->
                                    <i class="fas fa-arrow-left fa-fade"></i>&nbsp;
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } else { ?>
    <script type="text/javascript">
        window.onload = () => {
            $('#adminlogin').modal('show');
        }
    </script>
<?php } ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<?php include "../views/footer.php" ?>