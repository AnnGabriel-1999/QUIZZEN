<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';

    $database = new Database();
    $db = $database->connect();

    $quiz = new Quiz($db);

    $data = json_decode(file_get_contents('php://input'));

    $quiz->recordQuiz($data->section_id , $data->admin_id , $data->quiz_id , $data->room_id , $data->quiz_token);
?>