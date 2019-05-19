<script type="text/javascript" src="JS/myscript.js"></script>
<?php
include_once "config.php";  //include database. Use $conn.


$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
switch ($method) {
    case 'GET':{
        $request_type = $request[0];
        if($request_type == "getChanges"){    //https://147.175.121.210:4159/SemestralneZadanie/upload.php/getChanges/Student_team.team_id/user.id
            $id = $request[1];
            $sql = "SELECT t.teams_id FROM Team_Student ts JOIN Teams t ON ts.team_id = t.teams_id WHERE ts.student_id = " . $id;
            $result = $conn->query($sql);
            $return_set;
            $team_number = array();
            //kolko timov mam
            while($row = $result->fetch_assoc()){
                array_push($team_number, $row['teams_id']);
            }

            //info o vsetkych timoch
            $table_number = 0;
            foreach ($team_number as $team) {

                $sql = "SELECT s.subject_name, s.year, t.admin_accept FROM Subject s JOIN Teams t ON t.subject_id = s.subject_id WHERE t.teams_id = " . $team . "";
                
                $return_set[$table_number]['team_id'] = $team;
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    if($row['admin_accept'] != null){
                        $return_set[$table_number]['admin_accept'] = $row['admin_accept'];
                    }
                    $return_set[$table_number]['subject_name'] = $row['subject_name'];
                    $return_set[$table_number]['year'] = $row['year'];
                }

                $sql = "SELECT t.points FROM Teams t WHERE t.teams_id = " . $team;
                $result = $conn->query($sql);
                $isPointsNull = true;
                while($row = $result->fetch_assoc()){
                    if($row['points'] != null){
                        $return_set[$table_number]['points'] = $row['points'];
                        $isPointsNull = false; // ak hodnotenie este nie je
                    }
                    else{
                        $isPointsNull = true;
                    }
                }
                $sql = "SELECT u.id, u.username, u.email, ts.point, ts.agree, t.team_lider_id FROM Teams t JOIN Team_Student ts ON ts.team_id = t.teams_id JOIN users u ON u.id = ts.student_id WHERE t.teams_id = " . $team;
                $result = $conn->query($sql);
                $user_index = 0;
                while($row = $result->fetch_assoc()){
                    $return_set[$table_number][$user_index]['id'] = $row['id'];
                    $return_set[$table_number][$user_index]['username'] = $row['username'];
                    $return_set[$table_number][$user_index]['email'] = $row['email'];
                    if($isPointsNull){
                        //ak nemaju hodnotenie neviem nastavit.
                        $return_set[$table_number][$user_index]['point'] = NULL;
                        $return_set[$table_number][$user_index]['agree'] = NULL;
                    }
                    else{
                        // ak maju, uvazujem dve moznosti:
                        // a.) hodnotenie je NULL a som team lider mozem vypisovat, alebo nik nie je team lider
                        // b.) hodnotenie je NULL a nie som team lider mozem cakat ak team leader nie je.
                        // c.) hodnotenie je zadane a mam na vyber suhlasit alebo nesuhlasit.


                        //vypisanie inputu a/b
                        
                        
                        if($row['point'] != null){
                            $return_set[$table_number][$user_index]['point'] = $row['point'];
                        }
                        if($row['team_lider_id'] == null){
                            $return_set[$table_number][$user_index]['enable'] = true;
                        }
                        else if ($row['team_lider_id']  == $id){
                            $return_set[$table_number][$user_index]['enable'] = true;
                        }
                        else{
                            $return_set[$table_number][$user_index]['enable'] = false;
                        }
                        //vypisanie skuhlasu nesuhlsu c
                        if ($row['point']  != NULL){
                            //suhlas/nesuhlas

                            if($row['agree'] != null){
                                if($row['agree'] != 0){
                                    $return_set[$table_number][$user_index]['agree'] = true;
                                }
                                else{
                                    $return_set[$table_number][$user_index]['agree'] = false;
                                }
                            }
                            else{
                                if($row['id'] == $id){
                                //ak som to ja
                                    $return_set[$table_number][$user_index]['button'] = true;
                                }
                                else{
                                    $return_set[$table_number][$user_index]['button'] = false;
                                }
                            }
                        }
                    }
                    $user_index++;
                }
                $table_number++;
            }  
        }
        else if($request_type == "getDataForAdmin"){    //https://147.175.121.210:4159/SemestralneZadanie/upload.php/getDataForAdmin/Subject.subject_id
            $id = $request[1];
            $sql = "SELECT t.teams_id FROM Subject s JOIN Teams t ON s.subject_id = t.subject_id WHERE s.subject_name = '" . $id . "'";
            $result = $conn->query($sql);
            $return_set;
            $team_number = array();
            //kolko timov mam
            while($row = $result->fetch_assoc()){
                array_push($team_number, $row['teams_id']);
            }

            //info o vsetkych timoch
            $table_number = 0;
            foreach ($team_number as $team) {

                $sql = "SELECT s.subject_name, s.year, t.admin_accept FROM Subject s JOIN Teams t ON t.subject_id = s.subject_id WHERE t.teams_id = " . $team . "";
                
                $return_set[$table_number]['team_id'] = $team;
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    if($row['admin_accept'] != null){
                        $return_set[$table_number]['admin_accept'] = $row['admin_accept'];
                    }
                    $return_set[$table_number]['subject_name'] = $row['subject_name'];
                    $return_set[$table_number]['year'] = $row['year'];
                }

                $sql = "SELECT t.points FROM Teams t WHERE t.teams_id = " . $team;
                $result = $conn->query($sql);
                $isPointsNull = true;
                while($row = $result->fetch_assoc()){
                    if($row['points'] != null){
                        $return_set[$table_number]['points'] = $row['points'];
                        $isPointsNull = false; // ak hodnotenie este nie je
                    }
                    else{
                        $isPointsNull = true;
                    }
                }
                $sql = "SELECT u.id, u.username, u.email, ts.point, ts.agree, t.team_lider_id FROM Teams t JOIN Team_Student ts ON ts.team_id = t.teams_id JOIN users u ON u.id = ts.student_id WHERE t.teams_id = " . $team;
                $result = $conn->query($sql);
                $user_index = 0;
                while($row = $result->fetch_assoc()){
                    $return_set[$table_number][$user_index]['id'] = $row['id'];
                    $return_set[$table_number][$user_index]['username'] = $row['username'];
                    $return_set[$table_number][$user_index]['email'] = $row['email'];
                    if($isPointsNull){
                        //ak nemaju hodnotenie neviem nastavit.
                        $return_set[$table_number][$user_index]['point'] = NULL;
                        $return_set[$table_number][$user_index]['agree'] = NULL;
                    }
                    else{
                        // ak maju, uvazujem dve moznosti:
                        // a.) hodnotenie je NULL a som team lider mozem vypisovat, alebo nik nie je team lider
                        // b.) hodnotenie je NULL a nie som team lider mozem cakat ak team leader nie je.
                        // c.) hodnotenie je zadane a mam na vyber suhlasit alebo nesuhlasit.


                        //vypisanie inputu a/b
                        
                        
                        if($row['point'] != null){
                            $return_set[$table_number][$user_index]['point'] = $row['point'];
                        }
                        if($row['team_lider_id'] == null){
                            $return_set[$table_number][$user_index]['enable'] = true;
                        }
                        else if ($row['team_lider_id']  == $id){
                            $return_set[$table_number][$user_index]['enable'] = true;
                        }
                        else{
                            $return_set[$table_number][$user_index]['enable'] = false;
                        }
                        //vypisanie skuhlasu nesuhlsu c
                        if ($row['point']  != NULL){
                            //suhlas/nesuhlas

                            if($row['agree'] != null){
                                if($row['agree'] != 0){
                                    $return_set[$table_number][$user_index]['agree'] = true;
                                }
                                else{
                                    $return_set[$table_number][$user_index]['agree'] = false;
                                }
                            }
                            else{
                                if($row['id'] == $id){
                                //ak som to ja
                                    $return_set[$table_number][$user_index]['button'] = true;
                                }
                                else{
                                    $return_set[$table_number][$user_index]['button'] = false;
                                }
                            }
                        }
                    }
                    $user_index++;
                }
                $table_number++;
            } 
        }
        else if($request_type == "showTable"){

            $academicYear = $request[1];
            $subjectName = $request[2];

            $sqlSubjectID = "SELECT subject_id FROM Subject WHERE year='".$academicYear."' AND subject_name='".$subjectName."' LIMIT 1";
            $resultSubjectID = $conn->query($sqlSubjectID);

            echo $subjectName . " " . $academicYear;
            echo "<br>";
            echo "<br>";

            if($resultSubjectID->num_rows > 0) {

                $rowSubjectID = $resultSubjectID->fetch_assoc();

                $sqlAllTeamIDs = "SELECT teams_id FROM Teams";
                $resultAllTeamIDs = $conn->query($sqlAllTeamIDs);

                if ($resultAllTeamIDs->num_rows > 0) {

                    while ($rowAllTeamIDs = $resultAllTeamIDs->fetch_assoc()) {

                        $sqlTable = "SELECT users.email, users.full_name, Team_Student.point, Team_Student.agree
                        FROM users
                        LEFT JOIN Team_Student ON Team_Student.student_id = users.id
                        LEFT JOIN Teams ON Teams.teams_id = Team_Student.team_id WHERE Team_Student.team_id = '".$rowAllTeamIDs["teams_id"]."' AND Teams.subject_id='".$rowSubjectID["subject_id"]."'";
                        $resultTable = $conn->query($sqlTable);

                        if($resultTable->num_rows > 0) {

                            echo "Team" . " " . $rowAllTeamIDs["teams_id"];
//                            echo '<input type ="hidden" id="teamID" value ='.$rowAllTeamIDs["teams_id"].'>';
                            echo "<br>";
                            echo "Body:" . "<input type='number' id='points_".$rowAllTeamIDs["teams_id"]."'>";
                            echo "<input type='button' id='changePoints_".$rowAllTeamIDs["teams_id"]."' name='changePoints' value='Change' onclick='setPoints(this)'>";
                            echo "<table id='tableTable'>";
                            echo "<tr><th>".Email."</th>";
                            echo "<th>".Meno."</th>";
                            echo "<th>".Body."</th>";
                            echo "<th>".Suhlas."</th></tr>";

                            if ($resultTable->num_rows > 0) {
                                while ($rowTable = $resultTable->fetch_assoc()) {
                                    echo "<tr><td>" . $rowTable["email"] . "</td><td>" . $rowTable["full_name"] . "</td><td>" . $rowTable["point"] . "</td><td>" . $rowTable["agree"] . "</td></tr>";
                                }
                            }
                            echo "</table>";
                            echo "<br>";
                        }
                    }
                }
            }
        }
        break;
    }
    case 'POST':{
        $request_type = $request[0];
        if($request_type == "uploads"){    //https://147.175.121.210:4159/SemestralneZadanie/upload.php/uploads/{napis na button positive/negative}/users.id/Student_team.team_id
            if($request[1] == "button"){
                $submit;
                if($request[2] == "positive"){
                    $submit = 1;
                }
                else{
                    $submit = 0;
                }
                

                $sql = "UPDATE Team_Student ts SET ts.agree = '" . $submit . "' WHERE ts.team_id = " . $request[4] . " AND ts.student_id = " . $request[3];
                $return_set["sql"] = $sql;
                $result = $conn->query($sql);
            }
            else if($request[1] == "value"){    //https://147.175.121.210:4159/SemestralneZadanie/upload.php/value/{body [0-9]+}/users.id/Student_team.team_id
                $value = $request[2];
                $team_id = $request[4];
                $student_id = $request[3];
                $sql = "UPDATE Team_Student t SET t.point = " . $value . " WHERE t.student_id = " . $student_id . " AND t.team_id = " . $team_id;
                $return_set["sql"] = $sql;
                $result = $conn->query($sql);

                $sql = "UPDATE Team_Student t SET t.agree = NULL WHERE t.student_id = " . $student_id . " AND t.team_id = " . $team_id;
                $return_set["sql1"] = $sql;
                $result = $conn->query($sql);

                $sql = "UPDATE Teams t SET t.team_lider_id = " . $student_id . " WHERE t.teams_id = " . $$request[5];
                $return_set["sql2"] = $sql;
                $result = $conn->query($sql);
            }
            else if($request[1] == "admin"){   //https://147.175.121.210:4159/SemestralneZadanie/upload.php/admin/{bool hodnota 0/1}/Teams.teams_id
                $value = $request[2];
                $team_id = $request[3];
                $sql = "UPDATE Team_Student t SET t.point = " . $value . " WHERE t.team_id = " . $team_id;
                $return_set["sql"] = $sql;
                $result = $conn->query($sql);

                $sql = "UPDATE Teams t SET t.team_lider_id = 1 WHERE t.teams_id = " . $team_id;
                $return_set["sql1"] = $sql;
                $result = $conn->query($sql);
            }
            else if($request[1] == "admin"){   //https://147.175.121.210:4159/SemestralneZadanie/upload.php/admin/{body}/Teams.teams_id
                $value = $request[2];
                $team_id = $request[3];
                $sql = "UPDATE Team t SET t.points = " . $value . " WHERE t.teams_id = " . $team_id;
                $return_set["sql"] = $sql;
                $result = $conn->query($sql);
            }
        }
        break;
    }
    default:
}
//echo var_dump($return_set);
echo json_encode($return_set);
$conn->close();

?>