<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../models/Users.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    $users = new Users($db);
    $studentInfo = array();


    $users->setStudentID($_GET['student_id']);
   
    $res = $users->getSingleData();
    
    if($res->rowCount() > 0) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $studentData = array(
                'student_id' => $student_id,
                'fname' => $fname,
                'mname' => $mname,
                'lname' => $lname,
                'sec_id' => $section_id,
                'section' => $section,
                'course' => $course,
                'courseID' => $course_id
            );
            array_push($studentInfo, $studentData);
        }
        echo json_encode ($studentInfo); 
    }else{
        echo json_encode (
            array(
                'error' => 'No registered student in this student number'
            )
        );
    }

   