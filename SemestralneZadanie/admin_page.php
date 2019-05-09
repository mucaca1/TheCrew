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
	
	<form action="addPredmet.php" method="post" enctype="multipart/form-data">
	<h3>Pridanie predmetu</h3>
	Skolsky rok:<br>
	<input type="text" name="skolsky_rok" id="skolsky_rok"><br>
	Nazov predmetu:<br>
	<input type="text" name="nazov_predmetu" id="nazov_predmetu"><br>
	CSV subor:<br>
	<input type="file" name="csv_subor" id="csv_subor"><br>
	Oddelovac csv suboru:<br>
	<input type="text" name="oddelovac_csv" id="oddelovac_csv"><br>
	
	<input type="submit" value="Submit" name="submit"><br>
	
	</form>
	
	<?php
	$query_select = "SELECT * FROM Predmety;";
	$result = mysqli_query($conn, $query_select);
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
			for ($s=0; $s < $num2; $s++) {
				echo '<td>' . $data[$s] . '</td>';
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
        <p>&copy; The Crew 2019</p>
    </footer>
</body>
</html>