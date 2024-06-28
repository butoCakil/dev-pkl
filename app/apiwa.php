<?php
// if (@$_GET['apiwa'] == "woit70953487ogy2") {
// $token = "@USo9EJ4cicpFQ1t9n0n";
// $token = "Zo126dide#ijK#twX16P";
// $nomor = "6288220083720";
// $pesan = "Ketik \"Presensi\", bukan \"Absensi\"";
//     $token = @$_GET['t'];
// $nomor = "62811377323";
// $nomor = "6282241863393";
//     $nomor = @$_GET['n'];
//     $pesan = @$_GET['p'];
//     $nis = @$_GET['nis'];

if (isset($token)) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $nomor,
            'message' => $pesan,
            // 'url' => 'https://md.fonnte.com/images/wa-logo.png',
            // 'filename' => 'filename',
            // 'schedule' => '0',
            // 'typing' => false,
            // 'delay' => '2',
            // 'countryCode' => '62',
            // 'location' => '-7.983908, 112.621391',
            // 'buttonJSON' => '{"message":"fonnte button message","footer":"fonnte footer message","buttons":[{"id":"mybutton1","message":"hello fonnte"},{"id":"mybutton2","message":"fonnte pricing"},{"id":"mybutton3","message":"tutorial fonnte"}]}',
            // 'templateJSON' => '{"message":"fonnte template message","footer":"fonnte footer message","buttons":[{"message":"fonnte","url":"https://fonnte.com"},{"message":"call me","tel":"6282227097005"},{"id":"mybutton1","message":"hello fonnte"}]}',
            // 'listJSON' => '{"message":"fonnte list message","footer":"fonnte footer message","buttonTitle":"fonnte\'s packages","title":"fonnte title","buttons":[{"title":"text only","list":[{"message":"regular","footer":"10k messsages/month","id":"list-1"},{"message":"regular pro","footer":"25k messsages/month","id":"list-2"},{"message":"master","footer":"unlimited messsages/month","id":"list-3"}]},{"title":"all feature","list":[{"message":"super","footer":"10k messsages/month","id":"list-4"},{"message":"advanced","footer":"25k messsages/month","id":"list-5"},{"message":"ultra","footer":"unlimited messsages/month","id":"list-6"}]}]}'
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: $token"
        ),
    )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

    // {
    //     "detail":"success! message in queue",
    //     "id":["11908632"],
    //     "process":"processing",
    //     "status":true,
    //     "target":["6285747881171"]
    // }
    echo "<br><br>";

    $json = json_decode($response, TRUE);
    echo "json: " . $json['detail'] . "<br>";

    ?>
    <script>
        // window.location.href = 'prevpresensi.php?nis=<?= $nis; ?>&akses=presensi';
    </script>
    <?php
} else {
    echo "<h2>";
    echo "Akses ditolak";
    echo "<br>";
    echo "sek sek mas, arep ngopo? XD ";
    echo "<br>";
    echo '<a href="pkl.smknbansari.sch.id">Kembali</a>';
    echo "</h2>";
} 