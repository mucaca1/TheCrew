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

    <script src="./lang/welcomePage_language.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        crossorigin="anonymous">
</script>
<script src="./lang/jquery.MultiLanguage.min.js"></script>
    
    <title>WelcomePage</title>
        
    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/buttn.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>

<?php
//ak nie je prihlaseny
    if(!isset($_SESSION['accountID'])){
        //echo $_SESSION['accountID'];
        header("Location:index.php");
    }
    else{
        //ak je tak zober vsetky data co viem.
        $sql = "SELECT u.username, u.email, u.number, u.type FROM users u WHERE u.id='" . $_SESSION['accountID'] . "'";
        $userInfo = array();
        $result = $conn->query($sql);
        while( $row = $result->fetch_assoc() ) {
            array_push($userInfo, $row['username']);
            array_push($userInfo, $row['email']);
            array_push($userInfo, $row['number']);
            array_push($userInfo, $row['type']);

            $type = $row['type'];
        }
    }
?>

<body>
    <?php
    include "menubar.php";
    echo "<script> document.getElementById('login_user_name').innerHTML='". $userInfo[0] ."' </script>";
    echo "<script> initText(document.getElementById('logoffButton'), 'logoff','".$language."') </script>";
    ?>
    
    <h1>The Crew</h1>
    <article>
        <div class="content">
            <a href="https://147.175.121.210:4159/SemestralneZadanie/<?php if($type == 'student'){ echo 'student_page_points.php'; }else{ echo ''; } ?>"><button id='welcomePage_teams'>Prezeranie timv</button></a>
            <a href="https://147.175.121.210:4159/SemestralneZadanie/<?php if($type == 'student'){ echo 'student_page.php'; }else{ echo 'admin_page.php'; } ?>"><button id='welcomePage_points'>Body</button></a>
            <?php
                if($type != 'student'){
                    echo "<a href='https://147.175.121.210:4159/SemestralneZadanie/templateEditor.php'><button id='welcomePage_mailEditor'>Mail Editor</button></a>";
                    echo "<a href='https://147.175.121.210:4159/SemestralneZadanie/credentialMgmt.php'><button id='welcomePage_mailManager'>Mail Manager</button></a>";
                }
            ?>
        </div>
    </article>
    


    <footer>
    <p>&copy; The Crew 2019 - Lendáč, Krč, Szalay, Czerwinski, Tran Minh</p>
    </footer>
</body>
</html>