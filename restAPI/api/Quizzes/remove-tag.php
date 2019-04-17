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
    $result = $quiz->removeTag($_GET['tagID'], $_GET['quizID'], $_GET['operation']);

    if($result){
        echo json_encode(
            array(
                'success' => "na-delete"
            )
        );
    }else{
        echo json_encode(
            array(
                'failed' => "di na-delete"
            )
        );
    }