<?php
session_start();
$title = "Praktik Kerja Industri Teknik ELEKTRONIKA SMK NEGERI BANSARI Tahun 2022/2023";
$admin = false;

include "views/header.php"; 
include "views/navbar.php";
?>

<style>
    .container .box .head {
        background-color: #ffffff;
        border-radius: 20px;
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container">
    <?php
    
    if (@$_GET["akses"]) {
        include "form.php";
    } else {
    ?>
        <div class="box">
            <div class="head">
                <div class="col-md-3">
                    <img src="TE2.gif" class="logo_1">
                </div>
                <div id="judul">
                    <h2>Praktik Kerja Industri</h2>
                    <!--<h4>Teknik Elektronika</h4>-->
                    <h5>SMK NEGERI BANSARI</h5>
                    <h6>2023</h6>
                </div>
            </div>

        <?php
        include "modal_login.php";
    }
        ?>
        </div>

</div>
<div class="container"></div>

<!--  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<!-- import jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Bootstrap Bundle with Popper -->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>-->

<!--Modal JS Script -->
<script type="text/javascript">
    window.onload = () => {
        $('#modallogin').modal('show');
    }
</script>

<?php include "views/footer.php" ?>