<?php
 //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db);
    
    $headerArr = array();

    $result = $quiz->getQuizSummaryHeader($_GET['quiz_id'],$_GET['section_id']);

    if($datarow = $result->fetch(PDO::FETCH_ASSOC)){
        
        $headerData = array(
            'quiz_id' => $datarow['quiz_id'],
            'quiz_title' => $datarow['quiz_title'],
            'mirror_id' => $datarow['mirror_id'],
            'fname' => $datarow['fname'],
            'mname' => $datarow['mname'],
            'lname' => $datarow['lname'],
            'time_started' => $datarow['time_started'],
            'part_type' => $datarow['part_type'],
            'section_id' => $datarow['section_id'],
            'section' => $datarow['section'],
            'course' => $datarow['course'],
            'course_prefix' => $datarow['course_prefix']
        );
        array_push($headerArr, $headerData);
    }

    echo json_encode($headerArr);
?>