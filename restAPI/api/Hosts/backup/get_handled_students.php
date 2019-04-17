<?php
 //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Sections.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $sections = new Sections($db);
    $sections->setSectionID($_GET['section_id']);
    $sections->setSchoolYear($_GET['schoolyear_id']);
    $result = $sections->viewSectionsStudents();
    $rowcount = $result->rowCount();

    $studentsArr = array();

    if($rowcount > 0){
        while ($datarow = $result->fetch(PDO::FETCH_ASSOC)){
            $studentData = array(
                'student_id' => $datarow['student_id'],
                'fname' => $datarow['fname'],
                'mname' => $datarow['mname'],
                'lname' => $datarow['lname'],
                'status' => $datarow['status'],
                'section_id' => $datarow['section_id']
            );
            array_push($studentsArr, $studentData);
        }

        echo json_encode($studentsArr);
    }else{
        echo json_encode(array('error' => 'No Handled Students' ));
    }
    
?>