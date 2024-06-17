<script type='text/javascript'>
//<![CDATA[
$(function() {
//Fullscreen
$('a.fullscreenExit').hide();
$('a.fullscreen').on('click', function() {var docElement, request;docElement = document.documentElement;request = docElement.requestFullScreen || docElement.webkitRequestFullScreen || docElement.mozRequestFullScreen || docElement.msRequestFullScreen;if(typeof request!='undefined' && request){request.call(docElement);}$(this).hide();$('a.fullscreenExit').show();return false;});
$('a.fullscreenExit').on('click', function() {var docElement, request;docElement = document;request = docElement.cancelFullScreen|| docElement.webkitCancelFullScreen || docElement.mozCancelFullScreen || docElement.msCancelFullScreen || docElement.exitFullscreen;if(typeof request!='undefined' && request){request.call(docElement);}
$(this).hide();$('a.fullscreen').show();return false;});
});
//]]>
</script>

<div class="footer">
    <div>
        <?php if (@$admin == false) { ?>
            <img src="SMKNBansari.png" class="logo_2">
            <!--<img src="SMKBOS.png" class="logo_2">-->
        <?php } else { ?>
            <img src="../SMKNBansari.png" class="logo_2">
            <!--<img src="../SMKBOS.png" class="logo_2">-->
        <?php } ?>
    </div>
    <div>
        <!--<h5>Teknik Elektronika</h5>-->
        <h6>SMK NEGERI BANSARI <br><i class="fa fa-copyright"></i> 2023-2024</h6>
    </div>
    <div>
        <?php if (@$admin == false) { ?>
            <img src="SMKBOS.png" class="logo_3">
        <?php } else { ?>
            <img src="../SMKBOS.png" class="logo_3">
        <?php } ?>
    </div>
</div>

</body>

</html>

<?php
// mysqli_close(@$konek);
?>