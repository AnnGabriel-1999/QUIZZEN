<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
  //  header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Users.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';

    // Instantiate Classes
    $database = new Database();
    $db = $database->connect();
    $users = new Users($db);
    $univ = new Universal($db);
    $errorCont = new ErrorController();
    $studentInfo['student'] = array();
    
 //   $data = json_decode(file_get_contents('php://input'));

    //if($errorCont->checkField($data->studentNumber, 'Student Number', 10, 11)){
       
        $res = $univ->selectAll('students', 'student_id', $_GET['studentNum'], 'status', 'ACTIVE');

        if($res->rowCount() > 0){
            echo json_encode(
                array('message' => 'This student number is already used')
            );
        }else{
            $users->setStudentID($_GET['studentNum']);
            $res = $users->fetchStudentInfo();

            if ($res->rowCount() < 1){
                echo json_encode(
                    array ('message' => 'Student number not found')
                );
            }elseif($res->rowCount()==1){
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    $studentData = array(
                        
                            'student_id' => $student_id,
                            'fname' => $fname,
                            'mname' => $mname, 
                            'lname' => $lname, 
                            'course' => $course,
                            'section' => $section
                        
                       
                    );
                    array_push($studentInfo['student'], $studentData);
                }
                echo json_encode($studentInfo['student']);
            }
        }
  // }
    
//    if($errorCont->errors != null){
//         echo json_encode($errorCont->errors);        
//    }