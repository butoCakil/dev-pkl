<?php
header('Content-Type: application/json; charset=utf-8');

$json = file_get_contents('php://input');
$data = json_decode($json, true);

// $newfile = fopen("json.txt ", "w");
// $str = $data;
// fwrite($newfile, $str);
// fclose($newfile
$string = json_encode($json, true);
// $string = implode(",",$data);
$file = fopen('json.txt', 'w');
fwrite($file , $string);  
fclose($file );  

$device = $data['device'];
$sender = $data['sender'];
$message = $data['message'];
$text = $data['text']; //button text
$member = $data['member']; //group member who send the message
$name = $data['name'];
$location = $data['location'];
$pollname = $data['pollname'];
$choices = $data['choices'];

$hasil = "device: $device
sender: $sender
message: $message
text: $text
member: $member
name: $name
location: $location
pollname: $pollname
choices: $choices
";

$file = fopen('hasil.txt', 'w');
fwrite($file , $hasil);  
fclose($file );  

//data below will only received by device with all feature package
//start
$url =  @$data['url'];
$filename =  @$data['filename'];
$extension =  @$data['extension'];
//end

function sendFonnte($target, $data)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.fonnte.com/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'target' => $target,
            'message' => $data['message'],
            'url' => $data['url'],
            'filename' => $data['filename'],
        ),
        CURLOPT_HTTPHEADER => array(
            "Authorization: @USo9EJ4cicpFQ1t9n0n"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

if ($message == "test") {
    $reply = [
        "message" => "working great!",
    ];
} elseif ($message == "presensi" || $message == "Presensi" || $message == "PRESENSI" || $message == "ABSEN" || $message == "absen" || $message == "Absen") {
    $reply = [
        "message" => "Langkah Presensi:
1. Ambil *foto selfie*,
2. *Berikan caption/keterangan* di foto

*Format Caption*:
   Presensi#NIS#keterangan#catatan kegiatan

*note*: perhatikan penempatan tanda  pagar \"#\", satu baris tanpa \"enter\".
dan tanpa \"Spasi\" diantara tanda pagar \"#\".

*Contoh*:

presensi#2788#Masuk#Perbaikan rangkaian power supply

3. *Kirim*
Tunggu ± 1 menit untuk menerima balasan status presensi.
Pastikan mendapatkan balasan Berhasil.
jika tidak menerima balasan dalam 1 menit, periksa format apakah sesuai?
Atau kirim ulang / teruskan ke nomor ini lagi.
   

pkl.smknbansari.sch.id © 2023",
    ];
// } elseif ($message == "audio") {
    // $reply = [
        // "message" => "audio message",
        // "url" => "https://filesamples.com/samples/audio/mp3/sample3.mp3",
        // "filename" => "music",
    // ];
// } elseif ($message == "video") {
    // $reply = [
        // "message" => "video message",
        // "url" => "https://filesamples.com/samples/video/mp4/sample_640x360.mp4",
    // ];
// } elseif ($message == "file") {
    // $reply = [
        // "message" => "file message",
        // "url" => "https://filesamples.com/samples/document/docx/sample3.docx",
        // "filename" => "document",
    // ];
// } elseif ($text == "foto"){
    // $reply = [
        // "message" => "file fotonya 1: $url",
    // ];
} elseif (strtolower($message) == "presentasi" || strtolower($message) == "persensi" || strtolower($message) == "persentasi") {
    $reply = [
        "message" => "Ketik \"Presensi\", bukan \"$message\"",
    ];
} else {
    if ($location != ""){
        $reply = [
            "message" => "Lokasimu : https://www.google.com/maps/place/$location",
        ];
    }
}
// else {
// 	$reply = [
// 		"message" => "Sorry, i don't understand. Please use one of the following keyword :

// Test
// Audio
// Video
// Image
// File",
// ];
// }

sendFonnte($sender, $reply);
