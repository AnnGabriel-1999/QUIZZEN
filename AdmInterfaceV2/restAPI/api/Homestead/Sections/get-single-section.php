<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    include_once '../../../config/Database.php';
    include_once '../../../models/Universal.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Universal($db); 

  
    $result  = $quiz->selectAll2('sections', 'section_id', $_GET['secID']);

    $row = $result->fetch(PDO::FETCH_ASSOC);

    $quiz_item = array(
        'section' =>$row['section'],
        'year_level' =>$row['year_level']
    );
    echo json_encode($quiz_item);
?> 