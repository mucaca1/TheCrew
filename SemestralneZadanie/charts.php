<!DOCTYPE html>
<?php
session_start();
include_once 'easyPHPmultilang/easyPHPmultilang.php';
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./JS/script.js"></script>


    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php while($row = $result->fetch_assoc()){ echo $row['text']; } ?></title>

    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
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
    <form action="charts.php" method="post" enctype="multipart/form-data">
        <?php
        $currently_selected = date('Y');
        $earliest_year = 2000;
        $latest_year = date('Y');

        echo "<h3>".$lang->label(['Zobrazenie tímov', 'Show teams'])."</h3>";
        echo $lang->label(['Školský rok:', 'Accademic year:']);
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
        echo $lang->label(['Predmet:', 'Subject:']);
        echo '<select name="subjectName2" id="subjectName2">';

        $sqlSelSub = "SELECT subject_name, subject_id FROM Subject WHERE year='" . $_POST['academicYear2'] . "'";
        $resultSelSub = $conn->query($sqlSelSub);

        if ($resultSelSub->num_rows > 0) {
            while ($rowSelSub = $resultSelSub->fetch_assoc()) {
                echo '<option value="' . $rowSelSub['subject_id'] . '">' . $rowSelSub['subject_name'] . '</option>';
            }
        }
        echo '</select>';
        echo '<input type="button" value="Show" name="showCharts" id="showCharts">';
    }
    ?>
</article>
<div id="piechart" style="width: 700px; height: 400px;"></div>
<div id="piechart1" style="width: 700px; height: 400px;"></div>
<script>
    base_url = 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/';
    // base_url = 'http://147.175.121.210:8136/untitled1/upload.php/';

    $("#showCharts").click(function () {
        loadCharts();
        setInterval(loadCharts, 5000);
    });

    function loadCharts(){
        $.ajax({
            type: 'GET',
            url: base_url + "getCharts/" + $("#subjectName2").val(),
            success: function (msg) {
                // $("#output").html(msg);
                document.getElementById("piechart").innerHTML = "";
                document.getElementById("piechart1").innerHTML = "";
                var jpar = JSON.parse(msg);
                console.log(jpar['studInSub']);
                console.log(jpar['studAgree']);
                console.log(jpar['studDisagree']);
                console.log(jpar['studNull']);

                console.log(jpar['teamsInSub']);
                console.log(jpar['teamsDone']);
                console.log(jpar['teamsOpen']);
                console.log(jpar['teamsWithNull']);

                drawChart(jpar['studInSub'], jpar['studAgree'], jpar['studDisagree'], jpar['studNull']);
                drawChart1(jpar['teamsInSub'], jpar['teamsDone'], jpar['teamsOpen'], jpar['teamsWithNull']);
            }
        });
    }

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart(studInSub, studAgree, studDisagree, studNull)
    {
        var data = google.visualization.arrayToDataTable([
            ['Students', 'Count'],
            ['Number of students in subject', Number(studInSub)],
            ['Number of students Agreed with points', Number(studAgree)],
            ['Number of students Disagreed points', Number(studDisagree)],
            ['Number of students with No Answer', Number(studNull)]
        ]);
        var options = {
            // title: 'Students in subject',
            //is3D:true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(drawChart1);
    function drawChart1(teamsInSub, teamsDone, teamsOpen, teamsWithNull)
    {

        var data = google.visualization.arrayToDataTable([
            ['Teams', 'Count'],
            ['Number of teams in subject', Number(teamsInSub)],
            ['Number of closed teams', Number(teamsDone)],
            ['Number of open teams', Number(teamsOpen)],
            ['Number of teams where students did not answer', Number(teamsWithNull)]
        ]);
        var options = {
            // title: 'Teams in subject',
            //is3D:true,
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
        chart.draw(data, options);
    }
</script>
<footer>
    <p>&copy; The Crew 2019 - Lendáč, Krč, Szalay, Czerwinski, Tran Minh</p>
</footer>
</body>
</html>