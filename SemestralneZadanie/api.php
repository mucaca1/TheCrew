<?php
/*
http://147.175.121.210:4159/SemestralneZadanie/api.php/text/page/language => return JSON textove retazce
*/
?>



<?php
include_once "config.php";  //include database. Use $conn.

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$dataToJson = array();
switch ($method) {
    case 'GET':{
        $request_type = $request[0];
        if($request_type == "text"){
            //mysqli_set_charset($conn,"utf8");
            $sql = "SELECT l.text FROM language l WHERE l.page_name='" . $request[1] . "' AND l.language='" . $request[2] . "'";
            //echo $sql;
            $result = $conn->query($sql);
                if($result != TRUE){
                    $data = array("text" => "error");
                }
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $text = $row['text'];
                        $data = array("text" => $text);
                        array_push($dataToJson, $data);
                    }
                }
        }
        break;
    }
    default:
}
echo json_encode($dataToJson);
$conn->close();

?>

