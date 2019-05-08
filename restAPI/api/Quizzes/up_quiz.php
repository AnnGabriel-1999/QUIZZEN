<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';

    // Instantiate Classes
    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db);
    $data = json_decode(file_get_contents('php://input'));

    //TEST IF THERE IS AN EXISTING STREAM SI ADMIN
    if($quiz->checkStream($data->admin_id , $data->quiz_id)){
        echo json_encode(array('error' => 'There is Quiz you just uploaded fucking wait man'));
    }else{
        if($quiz->StreamQuiz($data->admin_id , $data->section_id , $data->quiz_id)){
            echo json_encode(array('success' => 'Quiz No.'.$data->quiz_id.' Successfully Streaming'));   
        }else{
            echo json_encode(array('error' => 'Has an error'));   
        }
    }
