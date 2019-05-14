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
        <select name="academicYear">
            <option value="LS 2018/2019" selected>LS 2018/2019</option>
        </select>
        <br>
        <input type="text" name="subjectName">
        <br>
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
$target_dir = "/home/xkrc/public_html/SemestralneZadanie/"; //treba pridat do tabulky Predmety stlpec typ? lebo v ulohe 1 a 2 su rozlicne subory.
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
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
//        for($i=0;$i<sizeof($tmp);$i++){
////            if(sizeof($tmp[$i]) == "5"){
////
////                $sql= "INSERT INTO students SET full_name = '".$tmp[$i][1]."', email = '".$tmp[$i][2]."', password = '".$tmp[$i][3]."', team ='".$tmp[$i][4]."'";
////                $result = $conn->query($sql);
////                echo "OK1";
////            }
////            elseif (sizeof($tmp[$i]) == 4){
////
////                $sql= "INSERT INTO students SET full_name = '".$tmp[$i][1]."', email = '".$tmp[$i][2]."', team ='".$tmp[$i][3]."'";
////                $result = $conn->query($sql);
////                echo "OK2";
////            }
////        }
        $sql= "INSERT INTO Predmety SET skolsky_rok = '".$_POST["academicYear"]."', nazov_predmetu = '".$_POST["subjectName"]."', csv_subor = '".basename( $_FILES["fileToUpload"]["name"])."', oddelovac_csv ='".$_POST["delimiter"]."'";
        $result = $conn->query($sql);
        echo "OK1";


        $sql = "SELECT csv_subor FROM Predmety";
        $result = $conn->query($sql);

        echo "<table>";
        echo "<tr><th></th><th>" .$_POST["academicYear"] ." ".$_POST["subjectName"]. "</th></tr>";
        if ($result->num_rows) {
            while ($row = $result->fetch_assoc()) {

                $tmp = readCSVFile(basename( $_FILES["fileToUpload"]["name"]), $_POST["delimiter"]);

                for($i=0;$i<sizeof($tmp);$i++) {
                    echo "<tr>";
                    echo "<td>" .$tmp[$i][2]."</td>";
                    echo "<td>" .$tmp[$i][1]."</td>";
                    echo "<td></td>";
                    echo "</tr>";
                    echo "</table>";
                }
            }
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
<footer>
    <p>&copy; The Crew 2019</p>
</footer>
</body>
</html>