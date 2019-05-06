<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db); 
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

        if($errorCont->checkField($data->quizTitle, "New Quiz Title",1,250)){
            if($errorCont->checkField($data->description, "New Quiz Description",1,300)){
                $quiz->quizID = $data->quizID;
                $quiz->quizTitle = $data->quizTitle;
                $quiz->description = $data->description;

                if($quiz->updateQuiz()){
                    echo json_encode( array('success' => 'Updating of Quiz Success.') );
                }else{
                    echo json_encode( array('message' => 'Updating of Quiz Failed.') );
                }
            }
        }
    
    if($errorCont->errors != null){
        echo json_encode( $errorCont->errors );
    }
?>