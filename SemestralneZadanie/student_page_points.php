<!DOCTYPE html>
<?php
session_start();
include_once 'easyPHPmultilang/easyPHPmultilang.php';
//$_SESSION['accountID'] = 6;
//ak nie je prihlaseny
if(!isset($_SESSION['accountID'])){
    //echo $_SESSION['accountID'];
    header("Location:index.php");
}
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
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
        crossorigin="anonymous">
</script>
<script src="./lang/jquery.MultiLanguage.min.js"></script>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>TeamPoints</title>
        
    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/main.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>

    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>

<?php
    $sql = "SELECT u.number FROM users u WHERE u.id = " . $_SESSION['accountID'] . " LIMIT 1";
    $result = $conn->query($sql);
    $ais_id = null;
    //nacitanie ais id
    while($row = $result->fetch_assoc()){
        $ais_id = $row['number'];
    }
?>
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
<body onload = 'enableButton(); disableButton(); getChanges(<?php echo $_SESSION["accountID"] ?>, false); setId(<?php echo $_SESSION["accountID"] ?>); enLang();'>
    <?php
	include "menubar.php";
	echo "<script> document.getElementById('login_user_name').innerHTML='Home (". $userInfo[0] .")' </script>";
    echo "<script> initText(document.getElementById('logoffButton'), 'logoff','".$language."') </script>";
	?>
    <article id="work" class="wrapper style1" style="padding: 5em 0 5em 0">
	    <h1>The Crew</h1>
    </article>
    <article id="work" class="wrapper style2">
        <?php
        /*    $sql = "SELECT u.number FROM users u WHERE u.id = " . $_SESSION['accountID'] . " LIMIT 1";
            $result = $conn->query($sql);
            $ais_id = null;
            //nacitanie ais id
            while($row = $result->fetch_assoc()){
                $ais_id = $row['number'];
            }
            if($ais_id == null){
                //zle
            }

            //nacitanie informacii ohladom vstetkych timov v ktorych som.
            $sql = "SELECT t.teams_id FROM Team_Student ts JOIN Teams t ON ts.team_id = t.teams_id WHERE ts.student_id = " . $_SESSION['accountID'];
            $result = $conn->query($sql);
            $team_number = array();
            
            while($row = $result->fetch_assoc()){
                array_push($team_number, $row['teams_id']);
            }

            //vypisanie info o kazdom time
            foreach ($team_number as $team) {

                //1. zistim pre aky predmet je to tabulka.
                //2. zistim ci maju pridane body
                //3. zistim kto je moj spolupracovnik.
                //      -> ak nemaju body, nevedia si ich rozdelit.
                //      -> ak maju body ale uz su vyplnene nevedi editovat.
                //      -> ak su body vyplnene vedia odsuhlasit.


                //1. -> pre ak predmet je tabulka
                
                $sql = "SELECT s.subject_name, s.year FROM Subject s JOIN Teams t ON t.subject_id = s.subject_id WHERE t.teams_id = " . $team . "";
            
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    echo "<h3>" . $row['subject_name'] . " " . $row['year'] . "</h3>";
                }
            

                //2. -> zistim ci maju pridane body
                
                $sql = "SELECT t.points FROM Teams t WHERE t.teams_id = " . $team;
                $result = $conn->query($sql);
                $isPointsNull = true;
                while($row = $result->fetch_assoc()){
                    if($row['points'] != null){
                        echo "<p>body: " . $row['points'] . "</p>";
                        $isPointsNull = false; // ak hodnotenie este nie je
                    }
                    else{
                        $isPointsNull = true;
                    }
                }


                //3. -> zistim kto je moj spolupracovnik.
                
                $sql = "SELECT u.id, u.username, u.email, ts.point, ts.agree, t.team_lider_id FROM Teams t JOIN Team_Student ts ON ts.team_id = t.teams_id JOIN users u ON u.id = ts.student_id WHERE t.teams_id = " . $team;
                $result = $conn->query($sql);
                echo "<table id = 'predmet_table'>";
                echo "<thead><tr> <td>Meno</td> <td>Email</td> <td>Body</td> <td>Odsúhlasenie bodov</td> </tr></thead>";
                echo "<tbody>";
                while($row = $result->fetch_assoc()){
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td><td>" . $row['email'] . "</td>";
                    if($isPointsNull){
                        //ak nemaju hodnotenie neviem nastavit.
                        echo "<td></td><td></td>";
                    }
                    else{
                        // ak maju, uvazujem dve moznosti:
                        // a.) hodnotenie je NULL a som team lider mozem vypisovat, alebo nik nie je team lider
                        // b.) hodnotenie je NULL a nie som team lider mozem cakat ak team leader nie je.
                        // c.) hodnotenie je zadane a mam na vyber suhlasit alebo nesuhlasit.


                        //vypisanie inputu a/b
                        echo "<td><input ";
                        if($row['team_lider_id'] == null){
                            echo "class = 'enable_for_all'";
                        }
                        else if ($row['team_lider_id']  == $_SESSION['accountID']){
                            echo "class = 'enable_for_all'";
                        }
                        else{
                            echo "class = 'disable_for_you'";
                        }
                        echo " type='number' name='points' id='idinput' class='member_points_t" . $row['$team'] . "' onchange='newChangeInTable();'";
                        if($row['point'] != null){
                            echo "value = " . $row['point'];
                        }
                        echo "></td>";


                        //vypisanie skuhlasu nesuhlsu c
                        if ($row['point']  != NULL){
                            //suhlas/nesuhlas

                            if($row['agree'] != null){
                                if($row['agree'] != 0){
                                    echo "<td>Suhlasi</td>";
                                }
                                else{
                                    echo "<td>Nesuhlasi</td>";
                                }
                            }
                            else{
                                if($row['id'] == $_SESSION['accountID']){
                                //ak som to ja
                                    echo "<td><button class = 'enable_for_all'>Suhlas</button> / <button class = 'enable_for_all'>Nesuhlas</button></td>" ;
                                }
                                else{
                                    echo "<td><button class = 'disable_for_you'>Suhlas</button> / <button class = 'disable_for_you'>Nesuhlas</button></td>" ;
                                }
                            }
                        }
                        else{
                            echo "<td></td>";
                        }
                    }
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } */ 
        ?>

        </div>
        <?php include "footer.php"?>
    </article>
    <script>
    $(document).ready(function(){
    setInterval(function() {
        getChanges(mainId, true);
    }, 1000);
});
    </script>
</body>
</html>