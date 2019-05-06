<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Methods, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quiz.php';
    include_once '../../controllers/ErrorController.php';

    $database = new Database();
    $db = $database->connect();
    $quiz = new Quiz($db);
    $errorCont = new ErrorController();

    if($errorCont->checkField($_POST['question'], 'Question', 0, 1001)){
        if($errorCont->checkField($_POST['correct'], 'Answer', 0, 201)){
            if(isset($_FILES['file']['tmp_name'])) {
                $filepath = '../../../AdmInterfaceV2/uploads/quiz/'.$_FILES['file']['name'];
                $quiz->filepath = $filepath;
            }
            $quiz->quizID = $_POST['quiz_id'];
            $quiz->part_id = $_POST['part_id'];
            $quiz->question = $_POST['question'];
            $quiz->correct = $_POST['correct'];
                             
                    if($quiz->GenericAddQuestion()){
                        if($quiz->GenericInsertQuestion()){
                             if(isset($_FILES['file']['tmp_name'])){
                                 move_uploaded_file($_FILES['file']['tmp_name'],$filepath);
                             }
                            echo json_encode(
                                array(
                                    'success' => 'Question added successfully'
                                     )
                                        );
                                    }else{
                                        echo json_encode(
                                            array(
                                                'error' => 'There is an error in inserting the correct answer.'
                                            )
                                        );
                                    }                                    
                                }else{  
                                     echo json_encode(
                                        array(
                                            'error' => 'There is an error in adding questions'
                                        )
                                    );
                                }
                           }
                       }
            if($errorCont->errors != null) {
                echo json_encode(
                    $errorCont->errors
                );
            }