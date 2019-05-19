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
//
//
//$sql = "SELECT l.text FROM language l WHERE l.page_name='" . $page_name[0] . ".title' AND l.language='" . $language . "'";
//$result = $conn->query($sql);
?>
<!--<html lang="--><?php //echo $language ?><!--">-->
<head>
    <link rel="icon" href="data:;base64,=">
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="./JS/script.js"></script>


    <meta http-equiv="X-UA-Compatible" content="IE=edge">

<!--    <title>--><?php //while($row = $result->fetch_assoc()){ echo $row['text']; } ?><!--</title>-->

    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>


<body>
<?php
include "menubar.php";
echo "<script> document.getElementById('login_user_name').innerHTML='". $userInfo[0] ."' </script>";
echo "<script> initText(document.getElementById('logoffButton'), 'logoff','".$language."') </script>";
?>
<h1>The Crew</h1>
<article>
    <div class="content">
        blabal
    </div>

    <form action="teamEvaluationOG.php" method="post" enctype="multipart/form-data">
        <?php
        $currently_selected = date('Y');
        $earliest_year = 2000;
        $latest_year = date('Y');

        echo '<select name="academicYear">';
        foreach ( range( $latest_year, $earliest_year ) as $i ) {

            $previousYear = $i-1;

            $academicYearLS = "LS " . $i . "/" . "$previousYear";
            $academicYearZS = "ZS " . $i . "/" . "$previousYear";;

            echo '<option value="'.$academicYearLS.'">'.$academicYearLS.'</option>';
            echo '<option value="'.$academicYearZS.'">'.$academicYearZS.'</option>';
        }
        echo '</select>';
        ?>
        <br>
        <input type="text" name="subjectName">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <select name="delimiter">
            <option value="," selected>,</option>
            <option value=";">;</option>
        </select>
        <br>
        <input type="submit" value="Upload" name="submit">
        <br>
    </form>
</article>
<?php
$target_dir = "/home/xtranminhh/public_html/untitled1/"; //zmenit link potom
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

        echo "Upload OK";

        $tmp = readCSVFile(basename( $_FILES["fileToUpload"]["name"]), $_POST["delimiter"]);


        $sqlCheckSubject = "SELECT * FROM Subject WHERE subject_name ='".$_POST["subjectName"]."' AND year = '".$_POST["academicYear"]."'";
        $resultCheckSubject = $conn->query($sqlCheckSubject);

        if($resultCheckSubject->num_rows == null) {
            $sqlSubject = "INSERT INTO Subject (year, subject_name)
            VALUES ('" . $_POST["academicYear"] . "', '" . $_POST["subjectName"] . "')";
            $resultSubject = $conn->query($sqlSubject);
        }

        $sqlSubID= "SELECT subject_id as subID FROM Subject WHERE subject_name = '".$_POST["subjectName"]."' AND year = '".$_POST["academicYear"]."' LIMIT 1";
        $resultSubID = $conn->query($sqlSubID);

        $rowSubID = $resultSubID->fetch_assoc();
        $subject_id = $rowSubID["subID"];


        for($i=0;$i<sizeof($tmp);$i++){
                $username = explode("@", $tmp[$i][2]);
                $fullname = $tmp[$i][1];
                $password = password_hash($tmp[$i][3], PASSWORD_DEFAULT);
                $email = $tmp[$i][2];
                $aisNumber = $tmp[$i][0];

            if(sizeof($tmp[0]) == "5") {
                $teamNumber = $tmp[$i][4];
            }
            else{
                $teamNumber = $tmp[$i][3];
            }

            echo sizeof($tmp[$i]);

                $sqlUserCheck = "SELECT * FROM users WHERE number='$aisNumber'";
                $resultUserCheck = $conn->query($sqlUserCheck);

                if ($resultUserCheck->num_rows == null && sizeof($tmp[0])=="5") {
                    $sqlUser = "INSERT INTO users (username, full_name, pass, email, number, type)
                    VALUES ('".$username[0]."', '".$fullname."' , '".$password."', '".$email."', ".$aisNumber.", 'student')";
                    $resultUser = $conn->query($sqlUser);
                }
                elseif($resultUserCheck->num_rows > 0 && sizeof($tmp[0])=="5"){
                    $sqlUser = "UPDATE users SET username='".$username[0]."', full_name='".$fullname."', pass='".$password."', email='".$email."' WHERE number ='$aisNumber'";
                    $resultUser = $conn->query($sqlUser);
                }
                elseif ($resultUserCheck->num_rows == null && sizeof($tmp[0])=="4") {
                    $sqlUser = "INSERT INTO users (username, full_name, email, number, type)
                    VALUES ('".$username[0]."', '".$fullname."' , '".$email."', ".$aisNumber.", 'student')";
                    $resultUser = $conn->query($sqlUser);
                }
                elseif ($resultUserCheck->num_rows > 0 && sizeof($tmp[0])=="4"){
                    $sqlUser = "UPDATE users SET username='".$username[0]."', full_name='".$fullname."', email='".$email."' WHERE number ='$aisNumber'";
                    $resultUser = $conn->query($sqlUser);
                }

                $sqlISTeam = "SELECT * FROM Teams WHERE subject_id = '$subject_id' AND team_number = '$teamNumber'";
                $resultISTeam = $conn->query($sqlISTeam);

                if ($resultISTeam->num_rows == null) {

                    $sqlTeams = "INSERT INTO Teams (subject_id, team_number)
                   VALUES (".$subject_id.", ".$teamNumber.")";
                    $resultTeams = $conn->query($sqlTeams);
                }

                $sqlUserID = "SELECT id FROM users WHERE number = '$aisNumber' LIMIT 1";
                $resultUserID = $conn->query($sqlUserID);

                $rowUserID = $resultUserID->fetch_assoc();
                $user_id = $rowUserID["id"];

                $sqlCheckTS = "SELECT * FROM Team_Student WHERE student_id='$user_id'";
                $resultCheckTS = $conn->query($sqlCheckTS);

                if($resultCheckTS->num_rows == null){
                    $sqlTeamID = "SELECT teams_id FROM Teams WHERE team_number = '$teamNumber' AND subject_id = '$subject_id' LIMIT 1";
                    $resultTeamID = $conn->query($sqlTeamID);

                    $rowTeamID = $resultTeamID->fetch_assoc();
                    $team_id = $rowTeamID["teams_id"];

                    $sqlTS = "INSERT INTO Team_Student ( team_id, student_id)
                    VALUES (".$team_id.", ".$user_id.")";
                    $resultTS = $conn->query($sqlTS);
                }
                else{
                    $sqlTeamID = "SELECT teams_id FROM Teams WHERE team_number = '$teamNumber' AND subject_id = '$subject_id' LIMIT 1";
                    $resultTeamID = $conn->query($sqlTeamID);

                    $rowTeamID = $resultTeamID->fetch_assoc();
                    $team_id = $rowTeamID["teams_id"];

                    $sqlTS = "UPDATE Team_Student SET team_id='$team_id' WHERE student_id ='$user_id'";
                    $resultTS = $conn->query($sqlTS);
                }
                echo "OK1";
        }

        echo "</table>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function readCSVFile($toRead,$dlm) {
    $csvArray = array_map(function($f) use ($dlm) {return str_getcsv($f,$dlm);}, file($toRead));
    return $csvArray;
}
?>

<form action="teamEvaluationOG.php" method="post" enctype="multipart/form-data">
    <?php
    $currently_selected = date('Y');
    $earliest_year = 2000;
    $latest_year = date('Y');

    echo '<select name="academicYear2">';
    foreach ( range( $latest_year, $earliest_year ) as $i ) {

        $previousYear = $i-1;

        $academicYearLS = "LS " . $i . "/" . "$previousYear";
        $academicYearZS = "ZS " . $i . "/" . "$previousYear";;

        echo '<option value="'.$academicYearLS.'">'.$academicYearLS.'</option>';
        echo '<option value="'.$academicYearZS.'">'.$academicYearZS.'</option>';
    }
    echo '</select>';
    ?>
    <input type="submit" value="Filter" name="filter">
    <br>
</form>
<form action="teamEvaluationOG.php" method="post" enctype="multipart/form-data">
    <input type='hidden' name='acaVal' value="<?php if($_POST['filter']) echo $_POST["academicYear2"]?>">
    <?php
        if($_POST['filter']) {
            echo '<select name="subjectName2">';

            $sqlSelSub = "SELECT subject_name FROM Subject WHERE year='" . $_POST['academicYear2'] . "'";
            $resultSelSub = $conn->query($sqlSelSub);

            if ($resultSelSub->num_rows > 0) {
                while ($rowSelSub = $resultSelSub->fetch_assoc()) {
                    echo '<option value="' . $rowSelSub['subject_name'] . '">' . $rowSelSub['subject_name'] . '</option>';
                }
            }
            echo '</select>';
        }
    ?>
    <input type="submit" value="Show" name="show">
</form>
<?php

    if($_POST['show']){
        if($_POST['acaVal'] && $_POST['subjectName2']) {

            $sqlSubjectID = "SELECT subject_id FROM Subject WHERE year='".$_POST['acaVal']."' AND subject_name='".$_POST['subjectName2']."' LIMIT 1";
            $resultSubjectID = $conn->query($sqlSubjectID);

            echo $_POST['acaVal'];

            if($resultSubjectID->num_rows > 0) {


                $rowSubjectID = $resultSubjectID->fetch_assoc();

                $sqlAllTeamIDs = "SELECT teams_id FROM Teams";
                $resultAllTeamIDs = $conn->query($sqlAllTeamIDs);

                if ($resultAllTeamIDs->num_rows > 0) {

                    while ($rowAllTeamIDs = $resultAllTeamIDs->fetch_assoc()) {

                        $sqlTable = "SELECT users.email, users.full_name, Team_Student.point, Team_Student.agree
                        FROM users
                        LEFT JOIN Team_Student ON Team_Student.student_id = users.id
                        LEFT JOIN Teams ON Teams.teams_id = Team_Student.team_id WHERE Team_Student.team_id = '".$rowAllTeamIDs["teams_id"]."' AND Teams.subject_id='".$rowSubjectID["subject_id"]."'";
                        $resultTable = $conn->query($sqlTable);

                        if($resultTable->num_rows > 0) {

                            echo $rowAllTeamIDs["teams_id"];
                            echo "<table>";
                            if ($resultTable->num_rows > 0) {
                                while ($rowTable = $resultTable->fetch_assoc()) {
                                    echo "<tr><td>" . $rowTable["email"] . "</td><td>" . $rowTable["full_name"] . "</td><td>" . $rowTable["point"] . "</td><td>" . $rowTable["agree"] . "</td></tr>";
                                }
                            }
                            echo "</table>";
                            echo "<hr>";
                        }
                    }
                }
            }
        }
    }

?>
<footer>
    <p>&copy; The Crew 2019</p>
</footer>
</body>
</html>