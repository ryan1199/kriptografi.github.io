<?php

//file ini untuk download file kunci
//get p and q value from javascript
//p and q are primes number
$p = intval($_POST["prime_p"]);
$q = intval($_POST["prime_q"]);
$n = intval($_POST["n"]);
$e = intval($_POST["e"]);
$d = intval($_POST["d"]);

//prepare to write file for key
//public key file and private key file
$file_public_key = "terupload\\public_key.txt";
$file_private_key = "terupload\\private_key.txt";
$content_public_key = "e=".strval($e)."n=".strval($n);
$content_private_key = "d=".strval($d)."n=".strval($n);

//download key files
if(isset($_POST["download_public_key_file"])){
    $handle = fopen($file_public_key, "w");
    fwrite($handle, $content_public_key);
    fclose($handle);
    header('Content-Type: application/txt');
    header('Content-Disposition: attachment; filename="public_key.txt"');
    readfile("terupload/public_key.txt");
    unlink("terupload/public_key.txt");
}
if(isset($_POST["download_private_key_file"])){
    $handle = fopen($file_private_key, "w");
    fwrite($handle, $content_private_key);
    fclose($handle);
    header('Content-Type: application/txt');
    header('Content-Disposition: attachment; filename="private_key.txt"');
    readfile("terupload/private_key.txt");
    unlink("terupload/private_key.txt");
}

?>