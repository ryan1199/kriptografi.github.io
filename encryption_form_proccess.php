<?php
require "encrypt_decrypt_proccess.php";

// ambil data file
$nama_file = $_FILES['berkas']['name'];
$nama_sementara_file = $_FILES['berkas']['tmp_name'];
$file_ext = pathinfo($nama_file, PATHINFO_EXTENSION);

// ambil data key public
$nama_kunci_public = $_FILES['kunci_public_rsa']['name'];
$nama_sementara_kunci_public = $_FILES['kunci_public_rsa']['tmp_name'];
$kunci_public_ext = pathinfo($nama_kunci_public, PATHINFO_EXTENSION);

// tentukan lokasi file akan dipindahkan
$dirUpload = "terupload/";

// pindahkan file
$terupload_file = move_uploaded_file($nama_sementara_file, $dirUpload.$nama_file);

// pindahkan public key
$terupload_public_key = move_uploaded_file($nama_sementara_kunci_public, $dirUpload.$nama_kunci_public);

$file = $dirUpload.$nama_file;
$key = $dirUpload."key.txt";
$key1 = $dirUpload."key1.txt";
$public_key = $dirUpload.$nama_kunci_public;

echo vernam_encrypt($file, $key);
echo rsa_encrypt($key, $public_key, $key1);

$zip = new ZipArchive();
    $zip->open('terupload/your_files.zip', ZipArchive::CREATE);
    $zip->addFile('terupload/'.$nama_file, $nama_file);
    $zip->addFile('terupload/key.txt', 'otp_key.txt');
    $zip->addFile('terupload/key1.txt', 'rsa_key.txt');
    $zip->close();

    if ($terupload_file && $terupload_public_key) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="your_files.zip"');
        readfile("terupload/your_files.zip");
        unlink($file);
        unlink($key);
        unlink($key1);
        unlink($public_key);
        unlink("terupload/your_files.zip");
    } else {
        echo "Upload Gagal!";
    }
?>