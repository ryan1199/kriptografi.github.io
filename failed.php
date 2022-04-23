<?php

session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Encrypt and Decrypt file</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta&effect=neon|outline|emboss|shadow-multiple|3d|3d-float">
    </head>
    <body>
        <div class="navigation">
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="encryption_form.html">Encryption</a></li>
                <li><a href="decryption_form.html">Decryption</a></li>
                <li><a href="generate_key_form.html">Generate Key</a></li>
            </ul>
        </div>
        <div class="container">
            <h1 class="message"><?php echo($_SESSION["message"]); ?></h1>
        </div>
    </body>
</html>
<?php

session_unset();
session_destroy();

?>