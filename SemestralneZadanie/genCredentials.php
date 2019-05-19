<?php
session_start();
ini_set('display_errors', 1);
include_once("csvReader.php");

$language = "sk";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}

if(isset($_GET['language'])){
    $_SESSION['language'] = $_GET['language'];
    $language = $_GET['language'];
}

if(isset($_SESSION['csvData'])){    
    $csvArray = $_SESSION['csvData'];
    if(isset($_SESSION['dlm'])){
        $dlm = $_SESSION['dlm'];
    }
    else
        $dlm = ",";

    //generuj hesla
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $i = 0;
    foreach($csvArray as &$line)
    {
        if($i == 0)
            array_push($line,"heslo");
        else
        {
            $pass = generate_string($chars,15);
            array_push($line,$pass);
        }
        $i++;
    }

    //exportuj do CSV
    arrayToCSVDownload($csvArray,"logins.csv",$dlm);
}

//https://code.tutsplus.com/tutorials/generate-random-alphanumeric-strings-in-php--cms-32132
function generate_string($input, $strength = 16) 
{
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) 
    {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
    return $random_string;
}
?>
