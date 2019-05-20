<!DOCTYPE html>
<?php
include_once 'easyPHPmultilang/easyPHPmultilang.php';
session_start();

?>
<?php
        //$page_name = explode(".", basename($_SERVER['PHP_SELF']));
        include_once "config.php";  //include database. Use $conn.
        
        
        //$sql = "SELECT l.text FROM language l WHERE l.page_name='" . $page_name[0] . ".title' AND l.language='" . $language . "'";
        //$result = $conn->query($sql);
    ?>
<html lang="<?php echo $lang->current_language ?>">
<head>
    <link rel="icon" href="data:;base64,=">
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="./JS/script.js"></script>
	<script src="./JS/jquery.scrolly.min.js"></script>
	<script src="./JS/browser.min.js"></script>
	<script src="./JS/breakpoints.min.js"></script>
	<script src="./JS/util.js"></script>
	<script src="./JS/main.js"></script>
    <script src="./JS/tabulkajs.js"></script>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Auth</title>
    


    <!--script  src="./lang/index_language.js"></script-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        crossorigin="anonymous">
</script>
<!--script src="./lang/jquery.MultiLanguage.min.js"></script-->

    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/main.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/form.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>

<body class="is-preload" onload="createTable();">
    <!--Rozdielny nav pre index page-->
    <nav id='nav'>
          <ul>
            <li><a href="<?php $lang->printLanguageToggle("sk") ?>">SK</a></li>
			<li><a href="<?php $lang->printLanguageToggle("en") ?>">EN</a></li>
          </ul>
    </nav>

    <article id="work" class="wrapper style1" style="padding: 5em 0 5em 0">
	    <h1>The Crew</h1>
    </article>
    <article id="work" class="wrapper style2">
        <div class="container">
            <h2></h2>
        <form action="authentification.php" method="post">
            <?php
            if(isset($_GET['invalid_pass'])){
                echo "<div>".$lang->printLabel(['Chybné heslo', 'Invalid password'])."</div>";
            }
            ?>
            <?php
                echo "<div>".$lang->printLabel(['Meno používateľa', 'Username'])."</div>";
            ?>
            <input type="text" name="username">
            <?php
                echo "<div>".$lang->printLabel(['Heslo', 'Password'])."</div>";
            ?>
            
            <input type="password" name="pass">
            <br>
            <button type="submit" id="index_submit"><?php $lang->printLabel(['Prihlásiť sa', 'Log in']);?></button>
        </form>
    </article>
    <article id="work" class="wrapper style4">
        <div id="table"></div>
    </div>

    <?php include "footer.php"?>
    </article>
    
</body>
</html>