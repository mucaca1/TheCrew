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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>
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
	
	<form action="addPredmet.php" method="post" enctype="multipart/form-data">
	<h3><div id='pridaniePredmetuText'>Pridanie predmetu</h3><!-- Pridanie Predmetu -->
	<div id='skolskyRokText'>Školský rok<br><!-- Skolsky rok: -->
	<input type="text" name="skolsky_rok" id="skolsky_rok"><br>
	<div id='nazovPredmetuText'>Názov predmetu<br><!-- Nazov predmetu: --><br>
	<input type="text" name="nazov_predmetu" id="nazov_predmetu"><br>
	<div id='CSVSuborText'>CSV Súbor<br><!-- CSV subor: --><br><br>
	<input type="file" name="csv_subor" id="csv_subor"><br>
	<div id='oddelovacText'>Oddelovač csv<br><!-- Oddelovac csv suboru: --><br><br><br>
	<input type="text" name="oddelovac_csv" id="oddelovac_csv"><br>
	
	<input type="submit" value="Submit" name="submit"><br>
	
	</form>
	
	<?php
	echo '<div id="tables">';
	$query_select = "SELECT * FROM Predmety;";
	$result = mysqli_query($conn, $query_select);
	$number_of_table = 0;
	while (list($pk, $skolsky_rok, $nazov_predmetu, $csv_subor, $oddelovac_csv) = $result->fetch_row()) {
		echo $skolsky_rok . "<br>" . '<a href="deletePredmet.php?id=' . $pk . '">' . $nazov_predmetu . '</a>' . "<br>";
		echo '<input type="button" value="Create PDF" 
            id="btPrint" onclick="createPDF(' . $number_of_table . ')" />';
		//$csvArray = str_getcsv($csv_subor);
		$my_file = 'test.csv';
		$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
		$data = $csv_subor;
		fwrite($handle, $data);
		fclose($handle);
		$row = 1;
		if (($handle = fopen("test.csv", "r")) !== FALSE) {
			echo '<table id="' . $number_of_table .'">';
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
		$number_of_table++;
	}
	echo "<div>";
	?>
	
	<script>
	function createPDF(id) {
	var pdf = new jsPDF('p', 'pt', 'letter');
            // source can be HTML-formatted string, or a reference
            // to an actual DOM element from which the text will be scraped.
			
            source = $('#tables')[id];
			window.alert(source);
            // we support special element handlers. Register them with jQuery-style 
            // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
            // There is no support for any other type of selectors 
            // (class, of compound) at this time.
            specialElementHandlers = {
                // element with id of "bypass" - jQuery style selector
                '#bypassme': function(element, renderer) {
                    // true = "handled elsewhere, bypass text extraction"
                    return true
                }
            };
            margins = {
                top: 80,
                bottom: 60,
                left: 40,
                width: 522
            };
            // all coords and widths are in jsPDF instance's declared units
            // 'inches' in this case
            pdf.fromHTML(
                    source, // HTML string or DOM elem ref.
                    margins.left, // x coord
                    margins.top, {// y coord
                        'width': margins.width, // max width of content on PDF
                        'elementHandlers': specialElementHandlers
                    },
            function(dispose) {
                // dispose: object with X, Y of the last line add to the PDF 
                //          this allow the insertion of new lines after html
                pdf.save('Test.pdf');
            }
            , margins);
        }
	</script>

    <footer>
    <p>&copy; The Crew 2019 - Lendáč, Krč, Szalay, Czerwinski, Tran Minh</p>
    </footer>
</body>
</html>