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
    //Instantiate Quiz Class
    $quiz = new Quiz($db);
     //Instatiate Error Controller
    $errorCont = new ErrorController();

    //Get Raw Data
    $data = json_decode(file_get_contents('php://input'));

 	// VALIDATION

    if ($errorCont->checkField($_POST['new_question'], "New Question" , 0, 200)) {
         if(isset($_FILES['file']['tmp_name'])) {
                $filepath = '../../../AdmInterfaceV2/uploads/quiz/'.$_FILES['file']['name'];
                $quiz->filepath = $filepath;
            }
                $quiz->new_question = $_POST['new_question'];
                $quiz->quizID = $_POST['quizID'];
                $quiz->partID = $_POST['partID'];
                $quiz->question_id = $_POST['question_id'];
                $quiz->correct = $_POST['correct'];

                if( $quiz->updateTrueorFalse() ){
                     echo json_encode(array('success' => 'Question updated successfully!'));
                    if(isset($_FILES['file']['tmp_name'])) {
                        move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
                    }
                }else{
                    echo json_encode(array('message' => 'T/F Update failed.') );
                }
            }


    if($errorCont->errors != null){
        echo json_encode(
            $errorCont->errors
        );
    }


?>
