<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
ini_set('max_input_time', '-1');

function rsa_encrypt($file, $key, $file1) {
    //$file is One Time Pad key
    //$key is public key
    //$file1 is encrypted One Time Pad key using rsa

    //prepare file
    $handle = fopen($file, "r");
    $file_content = fread($handle, filesize($file));
    fclose($handle);

    //prepare key
    $handle = fopen($key, "r");
    $key_content = fread($handle, filesize($key));
    fclose($handle);

    //get content of file
    $file_content_array = array();
    $file_content_array = str_split($file_content);

    //get content of key
    $key_content_array = array();
    $key_content_array = str_split($key_content);

    //get e and n from key
    //take only the numbers as array
    $e_array_string = array();
    $n_array_string = array();
    for($i = 0; $i < count($key_content_array); $i++) {
        if($key_content_array[$i] == "e") {
            $flag = "e";
        }
        if($key_content_array[$i] == "n") {
            $flag = "n";
        }
        if($key_content_array[$i] != "=") {
            if($key_content_array[$i] != "e" && $flag == "e") {
                array_push($e_array_string, $key_content_array[$i]);
            }
            if($key_content_array[$i] != "n" && $flag == "n") {
                array_push($n_array_string, $key_content_array[$i]);
            }
        }
    }
    $e_string = join("", $e_array_string);
    $n_string = join("", $n_array_string);
    $e = intval($e_string);
    $n = intval($n_string);
    

    //encrypt proccess
    //file character to decimal
    $file_content_array_int = array();
    for($i = 0; $i < count($file_content_array); $i++) {
        array_push($file_content_array_int, gmp_mod(gmp_pow(ord($file_content_array[$i]), $e), $n));
    }

    //number to string
    $file_content_array_string = array();
    for($i = 0; $i < count($file_content_array_int); $i++) {
        array_push($file_content_array_string, strval($file_content_array_int[$i]));
    }

    //writing file
    $file_content_string = join(" ", $file_content_array_string);
    $handle = fopen($file1, "w");
    fwrite($handle, $file_content_string);
    fclose($handle);

}

function rsa_decrypt($file, $key) {
    //$file is encrypted One Time Pad key using rsa
    //$key is private key

    //prepare file
    $handle = fopen($file, "r");
    $file_content = fread($handle, filesize($file));
    fclose($handle);

    //prepare key
    $handle = fopen($key, "r");
    $key_content = fread($handle, filesize($key));
    fclose($handle);

    //get content of file
    $file_content_array = array();
    $file_content_array = str_split($file_content);

    //get content of key
    $key_content_array = array();
    $key_content_array = str_split($key_content);

    //get d and n from key
    //take only the numbers as array
    $d_array_string = array();
    $n_array_string = array();
    for($i = 0; $i < count($key_content_array); $i++) {
        if($key_content_array[$i] == "d") {
            $flag = "d";
        }
        if($key_content_array[$i] == "n") {
            $flag = "n";
        }
        if($key_content_array[$i] != "=") {
            if($key_content_array[$i] != "d" && $flag == "d") {
                array_push($d_array_string, $key_content_array[$i]);
            }
            if($key_content_array[$i] != "n" && $flag == "n") {
                array_push($n_array_string, $key_content_array[$i]);
            }
        }
    }
    $d_string = join("", $d_array_string);
    $n_string = join("", $n_array_string);
    $d = intval($d_string);
    $n = intval($n_string);

    //encrypted One Time Pad key
    //remove space between numbers
    $file_content_array_string = array();
    $group_string = "";
    array_push($file_content_array, " ");
    for($i = 0; $i < count($file_content_array); $i++) {
        if($file_content_array[$i] != " ") {
            $group_string = $group_string . $file_content_array[$i];
        }
        //$group_string = $group_string . $file_content_array[$i];
        if($file_content_array[$i] == " ") {
            array_push($file_content_array_string, $group_string);
            $group_string = "";
        }
    }

    //decrypt proccess
    $file_content_array_int = array();
    for($i = 0; $i < count($file_content_array_string); $i++) {
        array_push($file_content_array_int, gmp_mod(gmp_pow($file_content_array_string[$i], $d), $n));
    }

    //decimal to character
    $file_content_array = array();
    for($i = 0; $i < count($file_content_array_int); $i++) {
        array_push($file_content_array, chr(gmp_intval($file_content_array_int[$i])));
    }

    //writing file
    $file_content = join("", $file_content_array);
    $handle = fopen($file, "w");
    fwrite($handle, $file_content);
    fclose($handle);

}

function vernam_encrypt($file, $key) {
    //$file is file that you want to encrypt
    //$key is One Time Pad key

    //prepare file
    $handle = fopen($file, "rb");
    $file_content = fread($handle, filesize($file));
    fclose($handle);

    //prepare key
    //generate random key
    //array of printable ASCII characters in decimal
    $list_for_key = array(32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127);
    $key_int = array();
    for($i = 0; $i < filesize($file); $i++) {
        $random_list_for_key = rand(0, 93);
        array_push($key_int, $list_for_key[$random_list_for_key]);
    }

    //get content of file
    $file_content_array = array();
    $file_content_array = str_split($file_content);

    //proccess encryption using vernam cipher algorythm
    //file character to decimal
    //perform xor operation between file and key
    $file_content_array_ecrypted = array();
    for($i = 0; $i < filesize($file); $i++) {
        array_push($file_content_array_ecrypted, ord($file_content_array[$i]) ^ $key_int[$i]);
    }

    //prepare to write file
    //decimal to character
    $file_content_array_ecrypted_string = array();
    for($i = 0; $i < filesize($file); $i++) {
        array_push($file_content_array_ecrypted_string, chr($file_content_array_ecrypted[$i]));
    }
    $file_content_string = join("", $file_content_array_ecrypted_string);

    //prepare to write key
    //decimal to character
    $key_content_array_string = array();
    for($i = 0; $i < filesize($file); $i++) {
        array_push($key_content_array_string, chr($key_int[$i]));
    }
    $key_content_string = join("", $key_content_array_string);

    //writing file
    $handle = fopen($file, "wb");
    fwrite($handle, $file_content_string);
    fclose($handle);

    //writing key
    $handle = fopen($key, "wb");
    fwrite($handle, $key_content_string);
    fclose($handle);
}

function vernam_decrypt($file, $key) {
    //$file is file that you encrypt before
    //$key is One Time Pad key that you generated

    //prepare file
    $handle = fopen($file, "rb");
    $file_content = fread($handle, filesize($file));
    fclose($handle);

    //prepare key
    $handle = fopen($key, "rb");
    $key_content = fread($handle, filesize($key));
    fclose($handle);

    //get content of file
    $file_content_array = array();
    $file_content_array = str_split($file_content);

    //get content of key
    $key_content_array = array();
    $key_content_array = str_split($key_content);

    //proccess decryption using vernam cipher algorythm
    //file and key character to decimal
    //perform xor operation between file and key
    $file_content_array_ecrypted = array();
    for($i = 0; $i < filesize($file); $i++) {
        array_push($file_content_array_ecrypted, ord($file_content_array[$i]) ^ ord($key_content_array[$i]));
    }

    //prepare to write file
    //decimal to character
    $file_content_array_ecrypted_string = array();
    for($i = 0; $i < filesize($file); $i++) {
        array_push($file_content_array_ecrypted_string, chr($file_content_array_ecrypted[$i]));
    }
    $file_content_string = join("", $file_content_array_ecrypted_string);

    //writing file
    $handle = fopen($file, "wb");
    fwrite($handle, $file_content_string);
    fclose($handle);
}

?>