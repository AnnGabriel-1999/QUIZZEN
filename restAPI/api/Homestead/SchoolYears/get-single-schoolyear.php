<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    include_once '../../../config/Database.php';
    include_once '../../../models/Quiz.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db); 

    $quiz->schoolyear_id = $_GET['schID'];
    $result  = $quiz->singleSY();

    $row = $result->fetch(PDO::FETCH_ASSOC);

    $quiz_item = array(
        'schoolyear_id' =>$row['schoolyear_id'],
        'start' =>$row['start'],
        'end' =>$row['end'],
    );
    echo json_encode($quiz_item);
?>