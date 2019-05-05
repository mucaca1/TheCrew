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
    
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?php while($row = $result->fetch_assoc()){ echo $row['text']; } ?></title>
        
    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>

<body>
    <?php include "menubar.php";?>
    <h1>The Crew</h1>
    <article>
        <div class="content">
        <h2>Nazov zadania</h2>
        <form action="./authentification.php" method="post">
            <?php
            if(isset($_GET['invalid_pass'])){
                echo "<div id='passwordStatusText'><script>initText(document.getElementById('passwordStatusText'),'".$page_name[0].".passwordStatus', '".$language."')</script></div>";
            }
            ?>
            <?php
                echo "<div id='usernameText'><script>initText(document.getElementById('usernameText'),'".$page_name[0].".username', '".$language."')</script></div>";
            ?>
            <input type="text" name="username">
            <?php
                echo "<div id='passwordText'><script>initText(document.getElementById('passwordText'),'".$page_name[0].".password', '".$language."')</script></div>";
            ?>
            
            <input type="password" name="pass">
            <br>
            <button type="submit" id="submitText">
            <?php
                echo "<script>initText(document.getElementById('submitText'),'".$page_name[0].".submit', '".$language."')</script>";
            ?>
            </button>
        </form>
        </div>
    </article>
    

    <footer>
        <p>&copy; The Crew 2019</p>
    </footer>
</body>
</html>