<!DOCTYPE html>
<?php
include_once "config.php";  //include database. Use $conn.
session_start();
$language = "sk";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}

if(isset($_GET['language'])){
    $_SESSION['language'] = $_GET['language'];
    $language = $_GET['language'];
}
if(isset($_POST['editordata']) && isset($_POST['id_template'])){
	
	$sql45 = "UPDATE mail_template SET TEMPLATE='".$_POST['editordata']."' WHERE ID=".$_POST['id_template'];
	if ($conn->query($sql45) === TRUE) {
		//echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
}

?>
<?php
        $page_name = explode(".", basename($_SERVER['PHP_SELF']));
        //include_once "config.php";  //include database. Use $conn.
        
        
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
	
	<!-- include libraries(jQuery, bootstrap) -->
	<link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> 
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 

	<!-- include summernote css/js -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.css" rel="stylesheet">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote.js"></script>
	<script>
	function fillText(){
		$.get("getTemplates.php?choice=" + $('#first-choice').val(), function(data){
			$('#summernote').summernote('code', data);
		});
	}
	</script>
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
		
			
			<button onClick="fillText()">Load template</button><br>
			<form method="post">
			<select id="first-choice" name="id_template">
				
				<?php 
				$sql96 = "SELECT ID FROM mail_template";
				$result96 = $conn->query($sql96);
				while ($row = $result96->fetch_assoc()){
					echo "<option value=\"". $row['ID'] ."\">" . $row['ID'] . "</option>";
				}
				?>
			
			</select> 
			  <textarea action="templateEditor.php" id="summernote" name="editordata"></textarea>
			  <input type="submit" id="submitBtn" value="SaveTemplate">
			</form>
        </div>
    </article>
    

    <footer>
	<p>&copy; The Crew 2019 - Lendáč, Krč, Szalay, Czerwinski, Tran Minh</p>
    </footer>
<script>

$(document).ready(function() {
	$('#summernote').summernote();
});
</script>
</body>
</html>