<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../models/Universal.php';
    include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db);
    $univ = new Universal($db); 
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    $quizToken = $quiz->getQuizToken($data->room_id);

    if($quiz->deletePrevRecord($quizToken , $data->section_id) && $univ->delete1('quiz_takers', 'quiz_token', $quizToken)){
        echo json_encode(array('success' => 'niceone'));
    }else{
        echo json_encode(array('error' => 'notnice'));
    }

    
    
?>