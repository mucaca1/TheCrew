<?php

    $servername = "localhost";
    $username = "meno_noveho_administratora";
    $password = "velmi_silne_heslo";
    $database = "semestralne_zadanie";

    
    //pripojenie na databazu
    $conn = new mysqli($servername, $username, $password, $database);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>