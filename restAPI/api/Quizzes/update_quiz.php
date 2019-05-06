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

        if($errorCont->checkField($_POST['quizTitle'], "New Quiz Title",1,250)){
            if($errorCont->checkField($_POST['description'], "New Quiz Description",1,300)){
                 if(isset($_FILES['file']['tmp_name'])) {
                $filepath = '../../../AdmInterfaceV2/uploads/quiz/'.$_FILES['file']['name'];
                $quiz->filepath = $filepath;
            }
                $quiz->quizID = $_POST['quizID'];
                $quiz->quizTitle = $_POST['quizTitle'];
                $quiz->description =  $_POST['description'];

                if($quiz->updateQuiz()){
                     echo json_encode(array('success' => 'Quiz updated successfully!'));
                    if(isset($_FILES['file']['tmp_name'])) {
                        move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
                    }
                }else{
                    echo json_encode( array('error' => 'Updating of Quiz Failed.') );
                }
            }
        }
    
    if($errorCont->errors != null){
        echo json_encode( $errorCont->errors );
    }
?>