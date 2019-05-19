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
    <script src="JS/script.js"></script>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php while($row = $result->fetch_assoc()){ echo $row['text']; } ?></title>

    <!--Zakladne CSS-->
    <link href="CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">

    <style>
        #tableTable {
            font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #tableTable td, #predmet_table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #tableTable tr:nth-child(even){background-color: #f2f2f2;}

        #tableTable tr:hover {background-color: #ddd;}

        #tableTable th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
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
    </div>

    <form action="teamEvaluationOG.php" method="post" enctype="multipart/form-data">
        <?php
        $currently_selected = date('Y');
        $earliest_year = 2000;
        $latest_year = date('Y');

        echo "Školský rok: ". '<select name="academicYear">';
        foreach ( range( $latest_year, $earliest_year ) as $i ) {

            $previousYear = $i-1;

            $academicYearLS = "LS-" . $i . "-" . "$previousYear";
            $academicYearZS = "ZS-" . $i . "-" . "$previousYear";;

            echo '<option value="'.$academicYearLS.'">'.$academicYearLS.'</option>';
            echo '<option value="'.$academicYearZS.'">'.$academicYearZS.'</option>';
        }
        echo '</select>';
        ?>
        <br>
        <p>Predmet: </p> <input type="text" name="subjectName">
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <p>Delimiter: </p>
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
$target_dir = "/home/xkrc/public_html/SemestralneZadanie/CSV/";
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
        }

    } else {
//        echo "Sorry, there was an error uploading your file.";
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

    echo 'Školský rok: ';
    echo '<select name="academicYear2">';
    echo '<option value="--">--</option>';
    foreach ( range( $latest_year, $earliest_year ) as $i ) {

        $previousYear = $i-1;

        $academicYearLS = "LS-" . $i . "-" . "$previousYear";
        $academicYearZS = "ZS-" . $i . "-" . "$previousYear";

        echo '<option value="'.$academicYearLS.'">'.$academicYearLS.'</option>';
        echo '<option value="'.$academicYearZS.'">'.$academicYearZS.'</option>';
    }
    echo '</select>';
    ?>
    <input type="submit" value="Filter" name="filter">
    <br>
</form>
    <input type='hidden' name='acaVal' value="<?php if($_POST['filter']) echo $_POST["academicYear2"]?>" id="academVal">
    <?php
        if($_POST['filter']) {
            echo "Predmet: ";
            echo '<select name="subjectName2" id="subjectName2">';

            $sqlSelSub = "SELECT subject_name FROM Subject WHERE year='" . $_POST['academicYear2'] . "'";
            $resultSelSub = $conn->query($sqlSelSub);

            if ($resultSelSub->num_rows > 0) {
                while ($rowSelSub = $resultSelSub->fetch_assoc()) {
                    echo '<option value="' . $rowSelSub['subject_name'] . '">' . $rowSelSub['subject_name'] . '</option>';
                }
            }
            echo '</select>';
            echo '<input type="button" value="Show" name="show" id="show">';
        }
    ?>
<div id="output"></div>
<script>

    base_url = 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/';

    $("#show").click(function () {
        $.ajax({
            type: 'GET',
            url: base_url + "showTable/" + $("#academVal").val() + "/" + $("#subjectName2").val(),
            success: function (msg) {
                $("#output").html(msg);
            }
        });
    });
</script>
<footer>
    <p>&copy; The Crew 2019</p>
</footer>
</body>
</html>