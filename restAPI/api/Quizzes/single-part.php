<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    //Instantiate Database Class
    $database = new Database();
    $db = $database->connect();

    //Instantiate Quiz Class
    $quiz = new Quiz($db); 

    //Get ID from URL
    $quiz->part_id = isset($_GET['partID']) ? $_GET['partID'] : die();
    
    //Get Post
    $result = $quiz->singlePart();

    $row = $result->fetch(PDO::FETCH_ASSOC);

    $quiz_item = array (
                'part_title' =>$row['part_title'],
                'duration' => $row['duration'],
                'type' => $row['type'] 
            );
    
    echo json_encode($quiz_item);