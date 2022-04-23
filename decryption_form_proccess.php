<?php
require "encrypt_decrypt_proccess.php";

// ambil data file
$nama_file = $_FILES['berkas']['name'];
$nama_sementara_file = $_FILES['berkas']['tmp_name'];
$file_ext = pathinfo($nama_file, PATHINFO_EXTENSION);

// ambil data key RSA
$nama_key = $_FILES['kunci']['name'];
$nama_sementara_key = $_FILES['kunci']['tmp_name'];
$key_ext = pathinfo($nama_key, PATHINFO_EXTENSION);

// ambil data key private
$nama_kunci_private = $_FILES['kunci_private_rsa']['name'];
$nama_sementara_kunci_private = $_FILES['kunci_private_rsa']['tmp_name'];
$kunci_private_ext = pathinfo($nama_kunci_private, PATHINFO_EXTENSION);

// tentukan lokasi file akan dipindahkan
$dirUpload = "terupload/";

// pindahkan file
$terupload_file = move_uploaded_file($nama_sementara_file, $dirUpload.$nama_file);

// pindahkan key
$terupload_key = move_uploaded_file($nama_sementara_key, $dirUpload.$nama_key);

// pindahkan private key
$terupload_private_key = move_uploaded_file($nama_sementara_kunci_private, $dirUpload.$nama_kunci_private);

$file = $dirUpload.$nama_file;
$key = $dirUpload.$nama_key;
$private_key = $dirUpload.$nama_kunci_private;

echo rsa_decrypt($key, $private_key);
echo vernam_decrypt($file, $key);

if ($terupload_file && $terupload_key && $terupload_private_key) {
    header('Content-Type: application/'.$file_ext);
    header('Content-Disposition: attachment; filename="'.$nama_file.'"');
    readfile("terupload/".$nama_file);
    unlink($file);
    unlink($key);
    unlink($private_key);
} else {
    echo "Upload Gagal!";
}

?>