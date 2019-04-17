<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';
    include_once '../../../models/Sections.php';
    include_once '../../../controllers/ErrorController.php';
    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    //Instantiate Users Class
    $univ = new Universal($db);
    $secs = new Sections($db);
    $studentData = array();

    $secs->setSectionID($_GET['section_id']);
    $secs->setSchoolYear($_GET['syrid']);
    $res = $secs->viewSectionsStudents();
    
    if($res->rowCount() > 0) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $studentInfo = array(
                'student_id' => $student_id,
                'section_id' => $section_id,
                'status' => $status,
                'fname' => $fname,
                'mname' => $mname,
                'lname' => $lname
            );
            array_push($studentData, $studentInfo);
        }
        echo json_encode ($studentData); 
    }else{
        echo json_encode (
            array(
                'message' => 'No registered student'
            )
        );
    }