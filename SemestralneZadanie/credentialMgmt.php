<!DOCTYPE html>
<?php
session_start();
include_once("csvReader.php");

$language = "sk";
if(isset($_SESSION['language'])){
    $language = $_SESSION['language'];
}

if(isset($_GET['language'])){
    $_SESSION['language'] = $_GET['language'];
    $language = $_GET['language'];
}

if(isset($_FILES["uploadedFile"]))
{
    if(isset($_POST['action'])){
        $dlm = $_POST['delimiter'];
        $action = $_POST['action'];
        $csvArray = readCSVFile($_FILES["uploadedFile"]["tmp_name"],$dlm);
        $_SESSION["csvData"] = $csvArray;
        $_SESSION["dlm"] = $dlm;
        
        if($action == "gen") //chceme generovat hesla
        {            
            header("location:genCredentials.php");
        }
        else if($action == "email") //chceme rozposlat email
        {
			if(isset($_POST['smtp_username']) && isset($_POST['smtp_password']) && isset($_POST['smtp_from_email']) && isset($_POST['smtp_from_name']) && isset($_POST['smtp_subject']) && isset($_POST['template_id']) && isset($_POST['text_type'])){
				$_SESSION["smtp_username"] = $_POST['smtp_username'];
				$_SESSION["smtp_password"] = $_POST['smtp_password'];
				$_SESSION["smtp_from_email"] = $_POST['smtp_from_email'];
				$_SESSION["smtp_from_name"] = $_POST['smtp_from_name'];
				$_SESSION["smtp_subject"] = $_POST['smtp_subject'];
				$_SESSION["template_id"] = $_POST['template_id'];
				$_SESSION["text_type"] = $_POST['text_type'];
				
				$currentDir = getcwd();
				$uploadDirectory = "/uploads/";

				$errors = []; // Store all foreseen and unforseen errors here

				//$fileExtensions = ['jpeg','jpg','png']; // Get all the file extensions

				$fileName = $_FILES['uploadedAttachmentFile']['name'];
				$fileSize = $_FILES['uploadedAttachmentFile']['size'];
				$fileTmpName  = $_FILES['uploadedAttachmentFile']['tmp_name'];
				$fileType = $_FILES['uploadedAttachmentFile']['type'];
				$fileExtension = strtolower(end(explode('.',$fileName)));
				if (null != basename($fileName))
				{
					$uploadPath = $currentDir . $uploadDirectory . basename($fileName); 
				}
				if (isset($_FILES["uploadedAttachmentFile"])) {
					/*
					if (! in_array($fileExtension,$fileExtensions)) {
						$errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
					}
					*/
					if ($fileSize > 2000000) {
						$errors[] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
					}

					if (empty($errors)) {
						$didUpload = move_uploaded_file($fileTmpName, $uploadPath);

						if ($didUpload) {
							echo "The file " . basename($fileName) . " has been uploaded";
						} else {
							echo "An error occurred somewhere. Try again or contact the admin";
						}
					} else {
						foreach ($errors as $error) {
							echo $error . "These are the errors" . "\n";
						}
					}
				}

				$_SESSION["attachmentFilePath"] = $uploadPath;
				header("location:emailCredentials.php"); 
			}
        }
    }
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
<!-- JS tabulka -->
<script src="JS/jquery.tabledit.min.js"></script>
<script src="JS/jquery.dataTables.min.js"></script>
<script src="JS/dataTables.bootstrap4.min.js"></script>

<!-- CSS tabulka -->
<link rel="stylesheet" href="CSS/dataTables.bootstrap4.min.css">
<script>
    $(document).ready(function(){
        $('#historia').DataTable();
    });
</script>
    <?php
	include "menubar.php";
	echo "<script> document.getElementById('login_user_name').innerHTML='". $userInfo[0] ."' </script>";
    echo "<script> initText(document.getElementById('logoffButton'), 'logoff','".$language."') </script>";
	?>
    <h1>The Crew</h1>
    <article>
        <div class="content">
            <form action="credentialMgmt.php" method="post" enctype="multipart/form-data">
                <label>File:<br> <input type="file" name="uploadedFile" accept=".csv"></label><br>
                

                <label>Action:<br>
                <input type="radio" name="action" value="gen"> Generate credentials<br>
                <input type="radio" name="action" value="email"> Email credentials<br></label><br>
				
                <label>Delimiter:<br>
                <select name="delimiter">
                    <option selected value=",">,</option>
                    <option value=";">;</option>
                </select></label><br><br>
				
				<label>Email:<br>
				<label>Template ID: 
				<select name="template_id">
				<?php 
				$sql2 = "SELECT ID FROM mail_template";
				$result2 = $conn->query($sql2);
				while ($row = $result2->fetch_assoc()){
				echo "<option value=\"". $row['ID'] ."\">" . $row['ID'] . "</option>";
				}
				?>
				</select></label><br>
				SMTP STUBA Username:<input type="text" name="smtp_username"><br>
				SMTP STUBA Password:<input type="password" name="smtp_password"><br>
				SMTP From/Reply To Email:<input type="text" name="smtp_from_email"><br>
				SMTP From/Reply To Name:<input type="text" name="smtp_from_name"><br>
				SMTP Subject:<input type="text" name="smtp_subject"><br>
				<label>Attachment:<br> <input type="file" name="uploadedAttachmentFile"></label><br>
				
				<label>Html / plain text:<br>
                <select name="text_type">
                    <option selected value="true">html</option>
                    <option value="false">plain text</option>
                </select></label><br><br>
				
                <input type="submit" id="submitBtn" value="Submit">
            </form><br><br>
			<table id="historia" class="table table-hover table-sm">
				<thead class="thead-dark">
					<tr>
						<th>ID</th>
						<th>Sent</th>
						<th>Name</th>
						<th>Subject</th>
						<th>Template_ID</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$sql77 = "SELECT * FROM mail_log";

					$result77 = $conn->query($sql77);
					
					
					while ($row77 = $result77->fetch_assoc()) {
						?>
						  <tr>
							<td><?php echo $row77["ID"]; ?></td>
							<td><?php echo $row77["sent"]; ?></td>
							<td><?php echo $row77["name"]; ?></td>
							<td><?php echo $row77["subject"]; ?></td>
							<td><?php echo $row77["template_id"]; ?></td>
						  </tr>
					 <?php
					}
					?>
				</tbody>
			</table>
		</div>
    </article>
    

    <footer>
        <p>&copy; The Crew 2019</p>
    </footer>
</body>
</html>