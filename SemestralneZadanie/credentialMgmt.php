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
            //header("location:emailCredentials.php"); 
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
                <input type="submit" id="submitBtn" value="Submit">
            </form>
        </div>
    </article>
    

    <footer>
        <p>&copy; The Crew 2019</p>
    </footer>
</body>
</html>