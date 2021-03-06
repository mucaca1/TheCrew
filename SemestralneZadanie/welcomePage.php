<!DOCTYPE html>
<?php
session_start();
include_once 'easyPHPmultilang/easyPHPmultilang.php';
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
    

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!--script src="./lang/welcomePage_language.js"></script-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        crossorigin="anonymous">
</script>
<!--script src="./lang/jquery.MultiLanguage.min.js"></script-->
    
    <title>Welcome Page</title>
        
    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/main.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>
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
    echo "<script> document.getElementById('login_user_name').innerHTML='Home (". $userInfo[0] .")' </script>";
    //echo "<script> initText(document.getElementById('logoffButton'), 'logoff','".$language."') </script>";
    ?>
    
    <article id="work" class="wrapper style1" style="padding: 5em 0 5em 0">
	    <h1>The Crew</h1>
    </article>
    <article id="work" class="wrapper style2">
        <div class="container">
            <a href="<?php if($type == 'student'){ echo 'student_page_points.php'; }else{ echo 'teamEvaluationOG.php'; } ?>"><button><?php $lang->printLabel(['Informácie o tímoch', 'Show team info']);?></button></a>
            <a href="<?php if($type == 'student'){ echo 'student_page.php'; }else{ echo 'admin_page.php'; } ?>"><button><?php $lang->printLabel(['Body predmetov', 'Points from subjects']);?></button></a>
            <?php
                if($type != 'student'){
                    echo "<a href='charts.php'><button>".$lang->label(['Štatisiky', 'Statictics'])."</button></a>";
                    echo "<a href='templateEditor.php'><button>".$lang->label(['Editor mailov', 'Mail editor'])."</button></a>";
                    echo "<a href='credentialMgmt.php'><button>".$lang->label(['Manažér mailov', 'Mail manager'])."</button></a>";
                }
            ?>
        </div>
        <?php include "footer.php"?>
    </article>
</body>
</html>