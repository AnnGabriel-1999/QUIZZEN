<?php

    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../controllers/ErrorController.php';

    //Instantiate Database Class
     $database = new Database();
    $db = $database->connect();
    $quizzes = new Quiz($db);
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

    $quizzes->type_name = $data->type_name;
    $quizzes->duration = $data->duration;
    
    if ($quizzes->setType()){
        echo json_encode(array('success' => 'Quiz part added.','num parts' => $quizzes->countParts()));
    }else{
        echo json_encode(array('message' => 'There is an error.')); 
    }

?>