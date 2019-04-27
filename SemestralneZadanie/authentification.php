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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="./JS/script.js"></script>
    
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?php while($row = $result->fetch_assoc()){ echo $row['text']; } ?></title>
        
    <!--Zakladne CSS-->
    <link href="./CSS/style.css" media="all" rel="stylesheet" type="text/css"/>
    <!--CSS pre tlac-->
    <link rel="stylesheet" href="./CSS/print-style.css" type="text/css" media="print,projection">
</head>


<?php
if(!isset($_SESSION['accountID'])){
    header("Location:index.php");
}

if(isset($_GET['logoff'])){
    unset ($_SESSION['accountID']);
    header("Location:index.php");
}

//LDAP
if(isset($_POST['username']) && isset($_POST['pass'])){
    $username = $_POST['username'];
    $password = $_POST['pass'];
    $sql = "SELECT u.id ,u.pass, u.type FROM users u WHERE u.username='" . $username . "'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $hash = $row['pass'];
        $type = $row['type'];
        
        if(password_verify($password,$hash)){
            echo "Password is valid! Welcome " . $type;
            $_SESSION['accountID'] = $row['id'];
        }
        else{
            header("Location:index.php?invalid_pass=true");
        }
    }
    else{
        $basedn='ou=People,dc=stuba,dc=sk';
        $ds = @ldap_connect('ldap://ldap.stuba.sk', 636);
        //nastavovanie ldap
        @ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        //tzv bind cize overenie mena hesla
        $ldapBindResult = @ldap_bind($ds, 'uid='. $username.',ou=People,dc=stuba,dc=sk', $password);
        if (!$ldapBindResult)
        {
            @ldap_close($ds);
            header("Location:index.php?invalid_pass=true");
        }
        //filtrujeme co chceme dostat
        $ldapFilter = array("displayName","uid", "employeetype", "uisid", "cn", "sn", "givenname", "mail");
        //hladame
        $ldapSearchResult = @ldap_search($ds, $basedn, 'uid='. $username, $ldapFilter);
        $info = ldap_get_entries($ds, $ldapSearchResult);
        //print_r($info);

        /*for ($i=0; $i<$info["count"]; $i++) {
            //echo "dn is: ". $data[$i]["dn"] ."<br />";
            echo "User: ". $info[$i]["cn"][0] ."<br />";
            echo "User: ". $info[$i]["uisid"][0] ."<br />";
            
            if(isset($info[$i]["mail"][0])) {
                echo "Email: ". $info[$i]["mail"][0] ."<br /><br />";
            } else {
                echo "Email: None<br /><br />";
            }
        }*/

        if ($ldapSearchResult)
        {
            for ($i=0; $i<$info["count"]; $i++) {
                $username = $info[$i]["cn"][0];
                $number = $info[$i]["uisid"][0];
                $type = $info[$i]["employeetype"][0];
                $email = "";
                if(isset($info[$i]["mail"][3])) {
                    $email = $info[$i]["mail"][3];
                }
                $sql = "SELECT u.id, u.username FROM users u WHERE u.username='" . $username . "' AND u.number='" . $number . "'";
                $result = $conn->query($sql);
                if($result->num_rows <= 0){
                    
                    $sql = "INSERT INTO users (username, email, number, type) VALUES ('$username', '$email', '$number', '$type')";

                    if($conn->query($sql) == true){
                        echo '<script>console.log("New record")</script>';
                    }        
                    else{
                        echo '<script>console.log("Error: ' . $sql . '<br>' . $conn->error .')</script>';
                    }
                    $sql = "SELECT u.id, u.username FROM users u WHERE u.username='" . $username . "' AND u.number='" . $number . "'";
                    $result = $conn->query($sql);
                    while( $row = $result->fetch_assoc() ) {
                        $_SESSION['accountID'] = $row['id'];
                    }
                }
                else{
                    while( $row = $result->fetch_assoc() ) {
                        $_SESSION['accountID'] = $row['id'];
                    }
                }
            }
        }
        @ldap_close($ds);
        
    }
    header("Location:welcomePage.php");
}


?>






<body>
    <?php include "menubar.php";?>
    <h1>The Crew</h1>
    <article>
        <div class="content">
        </div>
    </article>
    

    <footer>
        <p>&copy; The Crew 2019</p>
    </footer>
</body>
</html>