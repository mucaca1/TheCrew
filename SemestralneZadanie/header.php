<?php
echo "<!DOCTYPE html>";

$language = "sk";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}

if(isset($_GET['language'])){
    $_SESSION['language'] = $_GET['language'];
    $language = $_GET['language'];
}

        $page_name = explode(".", basename($_SERVER['PHP_SELF']));
        include_once "config.php";  //include database. Use $conn.
        
        
        $sql = "SELECT l.text FROM language l WHERE l.page_name='" . $page_name[0] . ".title' AND l.language='" . $language . "'";
        $result = $conn->query($sql);

echo "<html lang=".$language.">";
echo "
<head>
    <link rel='icon' href='data:;base64,='>
    <meta charset='utf-8'>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js'></script>
    <script src='./JS/script.js'></script>
    
    
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    
    <title>" . while($row = $result->fetch_assoc()){ echo $row['text']; } ." </title>
        
    <!--Zakladne CSS-->
    <link href='./style.css' media='all' rel='stylesheet' type='text/css'/>
    <!--CSS pre tlac-->
    <link rel='stylesheet' href='./CSS/print-style.css' type='text/css' media='print,projection'>
</head>
";

?>
