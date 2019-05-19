<!DOCTYPE html>
<?php
session_start();
$language = "sk";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}

if(isset($_GET['language'])){
    $_SESSION['language'] = $_GET['language'];
    $language = $_GET['language'];
}
?>
<?php
        $page_name = explode(".", basename($_SERVER['PHP_SELF']));
        include_once "config.php";  //include database. Use $conn.
        
        
        $sql = "SELECT l.text FROM language l WHERE l.page_name='" . $page_name[0] . ".title' AND l.language='" . $language . "'";
        $result = $conn->query($sql);
    ?>
<html lang="<?php echo $language ?>">
<head>
    <link rel="icon" href="data:;base64,=">
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="./JS/script.js"></script>
    
    <script src="./JS/tabulkajs.js"></script>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Auth</title>
    


    <script src="./lang/index_language.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        crossorigin="anonymous">
</script>
<script src="./lang/jquery.MultiLanguage.min.js"></script>

    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/form.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>

<body onload="createTable();">
    <?php include "menubar.php";?>
    <h1>The Crew</h1>
    <article>
        <div class="content">
            <h2></h2>
        <form action="./authentification.php" method="post">
            <?php
            if(isset($_GET['invalid_pass'])){
                echo "<div id='index_passwordStatusText'></div>";
            }
            ?>
            <?php
                echo "<div id='index_username'></div>";
            ?>
            <input type="text" name="username">
            <?php
                echo "<div id='index_password'></div>";
            ?>
            
            <input type="password" name="pass">
            <br>
            <button type="submit" id="index_submit"></button>
        </form>

        <div id="table" class="centerd"></div>
        </div>
    </article>
    

    <footer>
        <p>&copy; The Crew 2019 - Lendáč, Krč, Szalay, Czerwinski, Tran Minh</p>
    </footer>
</body>
</html>