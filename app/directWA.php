<?php
function encryptPhoneNumber($number)
{
    // Implementasi enkripsi sesuai kebutuhan Anda
    // Misalnya, enkripsi sederhana XOR
    if (isset($number) && $number != "") {
        $encrypted = base64_encode($number);
    } else {
        $encrypted = base64_encode("");
    }

    return $encrypted;
}

// Fungsi untuk mendekripsi nomor WhatsApp
function decryptPhoneNumber($encrypted)
{
    // Implementasi dekripsi sesuai kebutuhan Anda
    // Misalnya, dekripsi base64
    if (isset($encrypted) && $encrypted != "") {
        $decrypted = base64_decode($encrypted);
    } else {
        $decrypted = base64_decode("");
    }

    return $decrypted;
}



?>