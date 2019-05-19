<!DOCTYPE html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
    </article>
	<form action="send_id.php" method="post" enctype="multipart/form-data">
	<div id='studentIDText'><script>initText(document.getElementById('studentIDText'),'".$page_name[0].".studentId', '".$language."')</script><br><!-- ID studenta: -->
	<input type="text" name="student_id" id="student_id"><br>
	<input type="submit" value="Submit" name="submit"><br>
	
	</form>
    <?php
	if(isset($_GET['student_id'])){
	$id_studenta = $_GET['student_id'];
	} else {
		$id_studenta = "";
	}
	echo $id_studenta;
	$query = "SELECT * FROM Predmety WHERE csv_subor LIKE '%" . $id_studenta . "%';"; 
	$result = mysqli_query($conn, $query);
	while (list($pk, $skolsky_rok, $nazov_predmetu, $csv_subor, $oddelovac_csv) = $result->fetch_row()) {
		echo $skolsky_rok . "<br>" . $nazov_predmetu . "<br>";
		//$csvArray = str_getcsv($csv_subor);
		$my_file = 'test.csv';
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		$data = $csv_subor;
		fwrite($handle, $data);
		fclose($handle);
		$row = 1;
		if (($handle = fopen("test.csv", "r")) !== FALSE) {
			echo "<table>";
		while (($data = fgetcsv($handle, 1000, $oddelovac_csv)) !== FALSE) {
			
			if($row == 1) {
				echo '<thead>
				<tr>';
				$num1 = count($data);
				for ($c=0; $c < $num1; $c++) {
					echo '<th>' . $data[$c] . '</th>';
				}
				echo '</tr>
				</thead>';
				echo '<tbody>';
			} else {
			$num2 = count($data);
			echo '<tr>';
			if($data[0]==$id_studenta){
			for ($s=0; $s < $num2; $s++) {
					echo '<td>' . $data[$s] . '</td>';
			}
			}
			echo '</tr>';
			}
			$row++;
		}
		echo '</tbody>';
		echo '</table>';
		}
		fclose($handle);
		echo "<br>";
	}
	?>

    <footer>
	<p>&copy; The Crew 2019 - Lendáč, Krč, Szalay, Czerwinski, Tran Minh</p>
    </footer>
</body>
</html>