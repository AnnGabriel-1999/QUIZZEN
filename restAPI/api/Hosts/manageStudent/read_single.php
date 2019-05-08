<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');


    include_once '../../../config/Database.php';
    include_once '../../../models/Users.php';
    include_once'../../../controllers/ErrorController.php';


    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Users Class
    $users = new Users($db);

    //Get ID from URL
    $users->stud_id = $_GET['stud_id'];

    $result = $users->singleStudent();

    $rowcount = $result->rowCount();

    if($rowcount > 0){

    $sec_arr = array();
        if ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                extract($row);
                $quiz_item = array (
                    'student_id' => $student_id,
                    'section_id' => $section_id,
                    'fname' => $fname,
                    'mname' => $mname,
                    'lname' => $lname,
                    'status' => $status,
                    'course_id' => $course_id
                );
            array_push($sec_arr , $quiz_item);
        }

        echo json_encode($sec_arr,JSON_PRETTY_PRINT);

    }else{
        echo json_encode(array('message' => 'NO FUCKING STUDENTS FOUND.'));
    }
