<!doctype html>
<html lang="en">

<head>
    <!-- Histats.com  START  (aync)-->
    <!--<script type="text/javascript">-->
    <!--    var _Hasync = _Hasync || [];-->
    <!--    _Hasync.push(['Histats.start', '1,4682653,4,0,0,0,00010000']);-->
    <!--    _Hasync.push(['Histats.fasi', '1']);-->
    <!--    _Hasync.push(['Histats.track_hits', '']);-->
    <!--    (function() {-->
    <!--        var hs = document.createElement('script');-->
    <!--        hs.type = 'text/javascript';-->
    <!--        hs.async = true;-->
    <!--        hs.src = ('//s10.histats.com/js15_as.js');-->
    <!--        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);-->
    <!--    })();-->
    <!--</script>-->

    <!--<noscript><a href="/" target="_blank"><img src="//sstatic1.histats.com/0.gif?4682653&101" alt="" border="0"></a></noscript>-->
    <!-- Histats.com  END  -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS file -->

    <?php if (@$admin == false) { ?>
        <!--<link rel="shortcut icon" href="Logo_TAV_v2.png">-->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="skincare_.css">
        <link rel="stylesheet" href="vendor/fontawesome/css/all.css">
        <script src="vendor/fontawesome/js/all.js"></script>
    <?php } else { ?>
        <!--<link rel="shortcut icon" href="../Logo_TAV_v2.png">-->
        <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="../skincare_.css">
        <link rel="stylesheet" href="../vendor/fontawesome/css/all.css">
        <script src="../vendor/fontawesome/js/all.js"></script>
    <?php } ?>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- Fontawesome CSS -->
    <!--<script src="https://kit.fontawesome.com/3239833d25.js" crossorigin="anonymous"></script>-->
    <link rel="stylesheet" href="vendor/fontawesome/css/all.css">
    <script src="vendor/fontawesome/js/all.js"></script>

    <!-- datatables -->
    <!--   <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <!--   <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>-->

    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>-->
    <!--<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">-->
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">-->


    <title><?= @$title ? @$title : "Perakrin Teknik Elektronika"; ?></title>
</head>

<body>