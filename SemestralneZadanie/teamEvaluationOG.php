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
    <link href="./CSS/main.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="./CSS/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>
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
echo "<script> initText(document.getElementById('logoffButton'), 'logoff','".$language."') </script>";
?>
<article id="work" class="wrapper style1" style="padding: 5em 0 5em 0">
	    <h1>The Crew</h1>
</article>
<article id="work" class="wrapper style2">
    <h3>Upload</h3>
    <form action="teamEvaluationOG.php" method="post" enctype="multipart/form-data">
        <?php
        $currently_selected = date('Y');
        $earliest_year = 2000;
        $latest_year = date('Y');

        echo "Školský rok: ". '<select name="academicYear">';
        foreach ( range( $latest_year, $earliest_year ) as $i ) {

            $previousYear = $i-1;

            $academicYearLS = "LS-" . $i . "-" . "$previousYear";
            $academicYearZS = "ZS-" . $i . "-" . "$previousYear";

            echo '<option value="'.$academicYearLS.'">'.$academicYearLS.'</option>';
            echo '<option value="'.$academicYearZS.'">'.$academicYearZS.'</option>';
        }
        echo '</select>';
        ?>
        <br>
        <p>Predmet: <input type="text" name="subjectName"></p>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <br>
        <p>Delimiter:
        <select name="delimiter">
            <option value="," selected>,</option>
            <option value=";">;</option>
        </select>
        </p>
        <input type="submit" value="Upload" name="submit">
        <br>
        <hr>
    </form>
</article>
<?php
$target_dir = "/CSV/";
//$target_dir = "/home/xtranminhh/public_html/untitled1/";
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
<article id="work" class="wrapper style2">

<form action="teamEvaluationOG.php" method="post" enctype="multipart/form-data">
    <?php
    $currently_selected = date('Y');
    $earliest_year = 2000;
    $latest_year = date('Y');

    echo "<h3>"."Zobrazenie tímov"."</h3>";
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

            $sqlSelSub = "SELECT subject_name, subject_id FROM Subject WHERE year='" . $_POST['academicYear2'] . "'";
            $resultSelSub = $conn->query($sqlSelSub);

            if ($resultSelSub->num_rows > 0) {
                while ($rowSelSub = $resultSelSub->fetch_assoc()) {
                    echo '<option value="' . $rowSelSub['subject_id'] . '">' . $rowSelSub['subject_name'] . '</option>';
                }
            }
            echo '</select>';
            echo '<input type="button" value="Show" name="show" id="show">';
        }
    ?>
<div id="output"></div>
<script>

    base_url = 'upload.php';
    // base_url = 'http://147.175.121.210:8136/untitled1/upload.php/';

    $("#show").click(function () {
        loadTables();
        setInterval(loadTables, 5000);
    });

    function loadTables(){
        $.ajax({
            type: 'GET',
            url: base_url + "getDataForAdmin/" + $("#subjectName2").val() + "/" + $("#academVal").val(),
            success: function (msg) {
                // $("#output").html(msg);
                document.getElementById("output").innerHTML = "";
                var jpar = JSON.parse(msg);
                var i = 0;
                jpar.forEach(item => {
                    if (i == 0) {
                        var h = document.createElement('h3');
                        h.innerHTML = item['subject_name'] + " " + item['year'];
                        document.getElementById('output').appendChild(h);
                        var hr = document.createElement('hr');
                        document.getElementById('output').appendChild(hr);
                    }
                    makeTable(item['team_id'], item['subject_name'], item['year'], item['points'], item, item['admin_accept']);
                    i++;
                });
            }
        });
    }

    function makeTable(team_id, subject_name, year, points, row, ink){

        var team = document.createElement('p');
        team.innerHTML = "Tím "+ team_id;
        document.getElementById('output').appendChild(team);
        var p = document.createElement('p');

        if(ink == true || ink == false){
            p.innerHTML = "Body " + '<input type="number" id="fullPoints_'+team_id+'" value="'+points+'" disabled>' + " " + '<input type="button" id="changePoints_'+team_id+'" value="Change" onclick="setPoints(this)" disabled>';
        }
        else{
            p.innerHTML = "Body " + '<input type="number" id="fullPoints_'+team_id+'" value="'+points+'">' + " " + '<input type="button" id="changePoints_'+team_id+'" value="Change" onclick="setPoints(this)">';
        }
        document.getElementById('output').appendChild(p);


        if(ink != null){
            var p2 = document.createElement('p');
            if(ink == true){
                p2.innerHTML = "Suhlasim s hodnotenim";
            }
            else{
                p2.innerHTML = "Nesuhlasim s hodnotenim";
            }
            document.getElementById('output').appendChild(p2);
        }
        var table = document.createElement('table');
        table.setAttribute("id", "predmet_table");
        var head = document.createElement('thead');
        head.innerHTML = "<tr><th>Meno</th><th>Email</th><th>Body</th><th>Odsúhlasenie bodov</th></tr>";
        table.appendChild(head);
        var body = document.createElement('tbody');

        var i = 0;
        while(row[i] != null)
        {
            let t = document.createElement('tr');
            var text2 = "<small>-</small>";
            if(row[i]['agree'] == true){
                text2 = "<small>&#128077;</small>";
            }
            else if(row[i]['agree'] == false){
                text2 = "<small>&#128078;</small>";
            }

            t.innerHTML = "<td>" + row[i]["full_name"] + "</td><td>" + row[i]["email"] + "</td><td>" + row[i]["point"] +"</td><td id = '"+row[i]["username"]+ team_id + "'>" + text2 + "</td>";
            body.appendChild(t);
            i++;
        }
        table.appendChild(body);
        document.getElementById('output').appendChild(table);
        if(points != null) {
            if(ink == null) {
                var adminDecision = document.createElement('p');
                adminDecision.innerHTML = '<input type="button" id="acceptTeam_' + team_id + '_1" value="&#128077;" onclick="pointsDecision(this)">' + " " + '<input type="button" id="declineTeam_' + team_id + '_0" value="&#128078;" onclick="pointsDecision(this)">';
                document.getElementById('output').appendChild(adminDecision);
            }
        }
        var hr = document.createElement('hr');
        document.getElementById('output').appendChild(hr);
    }

    function setPoints(data) {
        console.log(data);
        var a = data.id.split('_');
        console.log(a);
        var points = document.getElementById("fullPoints_"+a[1]).value;
        console.log(points);
        var team_id = a[1];

        $.ajax({
            type: 'POST',
            url: base_url + 'adminChangePoints/' + points + "/" + team_id,
            success: function(msg){
                console.log(msg);
            }
        });
    }

    function pointsDecision(data) {
        console.log(data);
        var a = data.id.split('_');
        console.log(a);
        var team_id = a[1];
        var decision = a[2];

        $.ajax({
            type: 'POST',
            url: base_url + 'adminAccept/' + team_id + '/' + decision,
            success: function(msg){
                console.log(msg);
            }
        });
    }
</script>
    <?php include "footer.php"?>
</article>
</body>
</html>