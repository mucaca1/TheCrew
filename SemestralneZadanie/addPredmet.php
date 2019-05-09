<?php
function updateDatabse() {
	if ($_FILES["csv_subor"]["error"] > 0)
	{
	echo "Error: " . $_FILES["csv_subor"]["error"] . "<br />";
	}
	else
	{
	/*echo "Upload: " . $_FILES["csv_subor"]["name"] . "<br />";
	echo "Type: " . $_FILES["csv_subor"]["type"] . "<br />";
	echo "Size: " . ($_FILES["csv_subor"]["size"] / 1024) . " Kb<br />";
	echo "Stored in: " . $_FILES["csv_subor"]["tmp_name"];*/
	$csv_subor = file_get_contents($_FILES["csv_subor"]["tmp_name"], true);
	//echo $csv_subor;
	}
	$skolsky_rok =$_POST['skolsky_rok'];
	$nazov_predmetu =$_POST['nazov_predmetu'];
	
	$oddelovac_csv =$_POST['oddelovac_csv'];
	
	include('config.php');	
	$query_insert = "INSERT INTO Predmety (skolsky_rok, nazov_predmetu, csv_subor, oddelovac_csv) values ('$skolsky_rok', '$nazov_predmetu', '$csv_subor', '$oddelovac_csv');";
	$result = mysqli_query($conn, $query_insert);
	header("Location: http://147.175.121.210:8159/SemestralneZadanie/index.php");
	die();
}
?>
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
        </div>
    </article>
    <?php 
	updateDatabse();
	?>

    <footer>
        <p>&copy; The Crew 2019</p>
    </footer>
</body>
</html>